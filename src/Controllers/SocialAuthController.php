<?php

namespace ZFort\SocialAuth\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use ZFort\SocialAuth\Events\SocialUserAttached;
use ZFort\SocialAuth\Events\SocialUserAuthenticated;
use ZFort\SocialAuth\Events\SocialUserCreated;
use ZFort\SocialAuth\Events\SocialUserDetached;
use ZFort\SocialAuth\Models\SocialProvider;

use GuzzleHttp\Exception\RequestException;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Laravel\Socialite\Contracts\Factory as Socialite;
use ZFort\SocialAuth\Exceptions\SocialGetUserInfoException;
use ZFort\SocialAuth\Exceptions\SocialUserAttachException;
use Laravel\Socialite\Contracts\User as SocialUser;
use Illuminate\Contracts\Auth\Authenticatable;

/**
 * Class SocialAuthController
 * @package App\Http\Controllers
 *
 * Provide social auth logic
 */
class SocialAuthController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use RedirectsUsers;

    /**
     * Redirect path
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * @var Guard auth provider instance
     */
    protected $auth;

    /**
     * @var Socialite
     */
    protected $socialite;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $userModel;

    /**
     * SocialAuthController constructor. Register Guard contract dependency
     * @param Guard $auth
     * @param Socialite $socialite
     */
    public function __construct(Guard $auth, Socialite $socialite)
    {
        $this->auth = $auth;
        $this->socialite = $socialite;
        $this->redirectTo = config('social-auth.redirect');

        $className = config('auth.providers.users.model');
        $this->userModel = new $className;
    }

    /**
     * If there is no response from the social network, redirect the user to the social auth page
     * else make create with information from social network
     *
     * @param Request $request injected by IoC container
     * @param SocialProvider $social bound by "Route model binding" feature
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function getAccount(Request $request, SocialProvider $social)
    {
        $provider = $this->socialite->driver($social->slug);

        return $provider->redirect();
    }


    /**
     * Redirect callback for social network
     * @param Request $request
     * @param SocialProvider $social
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws SocialGetUserInfoException
     * @throws SocialUserAttachException
     */
    public function callback(Request $request, SocialProvider $social)
    {
        $provider = $this->socialite->driver($social->slug);

        $SocialUser = null;

        // try to get user info from social network
        try {
            $SocialUser = $provider->user();
        } catch (RequestException $e) {
            throw new SocialGetUserInfoException($social, $e->getMessage());
        }

        // if we have no social info for some reason
        if (!$SocialUser) {
            throw new SocialGetUserInfoException($social, 'Can\'t get users data from ' . $social->label);
        }

        // if user is guest
        if (!$this->auth->check()) {
            return $this->register($request, $social, $SocialUser);
        }

        //If someone already attached current socialProvider account
        if ($this->socialUserQuery($social, $SocialUser->getId())->exists()) {
            throw new SocialUserAttachException(
                back()->withErrors('Somebody already attached this account'),
                $social
            );
        }

        // if user already attached
        if ($request->user()->isAttached($social->slug)) {
            throw new SocialUserAttachException(
                back()->withErrors('User already attached ' . $social->label . ' social provider'),
                $social
            );
        }

        return $this->attach($request, $social, $SocialUser);
    }

    /**
     * Detaches social account for user
     *
     * @param Request $request
     * @param SocialProvider $social
     * @return array
     * @throws SocialUserAttachException
     */
    public function detachAccount(Request $request, SocialProvider $social)
    {
        $result = $request->user()->socials()->detach($social->id);

        if (!$result) {
            throw new SocialUserAttachException(
                back()->withErrors('Error while user detached ' . $social->label . ' social provider'),
                $social
            );
        }

        event(new SocialUserDetached($request->user(), $social, $result));

        return back();
    }

    /**
     * Gets user by unique social identifier
     *
     * @param SocialProvider $social
     * @param string $key
     * @return mixed
     */
    protected function getUserByKey(SocialProvider $social, string $key)
    {
        return $this->socialUserQuery($social, $key)->first();
    }

    /**
     * Create new system user by social user data
     *
     * @param SocialProvider $social
     * @param SocialUser $socialUser
     */
    protected function createNewUser(SocialProvider $social, SocialUser $socialUser)
    {
        $NewUser = $this->userModel->create(
            $this->userModel->mapSocialData($socialUser)
        );

        $NewUser->avatar = $socialUser->getAvatar();

        $NewUser->attachSocial(
            $social,
            $socialUser->getId(),
            $socialUser->token,
            $socialUser->expiresIn
        );

        event(new SocialUserCreated($NewUser));

        return $NewUser;
    }

    /**
     * @param Request $request
     * @param SocialProvider $social
     * @param SocialUser $socialUser
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function register(Request $request, SocialProvider $social, SocialUser $socialUser)
    {
        //Checks by socialProvider identifier if user exists
        $exist_user = $this->getUserByKey($social, $socialUser->getId());

        //Checks if user exists with current socialProvider identifier, auth if does
        if ($exist_user) {
            $this->login($exist_user);

            return redirect($this->redirectPath());
        }

        //Checks if account exists with socialProvider email, auth and attach current socialProvider if does
        $exist_user = $this->userModel->where('email', $socialUser->getEmail())->first();
        if ($exist_user) {
            $this->login($exist_user);

            return $this->attach($request, $social, $socialUser);
        }

        //If account for current socialProvider data doesn't exist - create new one
        $new_user = $this->createNewUser($social, $socialUser);
        $this->login($new_user);

        return redirect($this->redirectPath());
    }

    /**
     * Login user
     *
     * @param Authenticatable $user
     */
    protected function login(Authenticatable $user)
    {
        $this->auth->login($user);
        event(new SocialUserAuthenticated($user));
    }

    /**
     * @param Request $request
     * @param SocialProvider $social
     * @param SocialUser $socialUser
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function attach(Request $request, SocialProvider $social, SocialUser $socialUser)
    {
        $request->user()->attachSocial(
            $social,
            $socialUser->getId(),
            $socialUser->token,
            $socialUser->expiresIn
        );

        event(new SocialUserAttached($request->user(), $social, $socialUser));

        return redirect($this->redirectPath());
    }

    /**
     * @param SocialProvider $social
     * @param string $key
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    protected function socialUserQuery(SocialProvider $social, string $key)
    {
        return $social->users()->wherePivot(config('social-auth.foreign_keys.socials'), $key);
    }
}
