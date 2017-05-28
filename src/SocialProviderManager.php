<?php

namespace ZFort\SocialAuth;

use ZFort\SocialAuth\Models\SocialProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use ZFort\SocialAuth\Events\SocialUserCreated;
use ZFort\SocialAuth\Events\SocialUserAttached;
use Laravel\Socialite\Contracts\User as SocialUser;
use ZFort\SocialAuth\Contracts\SocialAuthenticatable;

class SocialProviderManager
{
    /**
     * @var SocialProvider
     */
    protected $social;

    /**
     * SocialProviderManager constructor.
     * @param SocialProvider $social
     */
    public function __construct(SocialProvider $social)
    {
        $this->social = $social;
    }

    /**
     * @param string $key
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function socialUserQuery(string $key)
    {
        return $this->social->users()->wherePivot(config('social-auth.foreign_keys.socials'), $key);
    }

    /**
     * Gets user by unique social identifier.
     *
     * @param string $key
     * @return mixed
     */
    public function getUserByKey(string $key)
    {
        return $this->socialUserQuery($key)->first();
    }

    /**
     * @param SocialAuthenticatable $user
     * @param SocialUser $socialUser
     */
    public function attach(SocialAuthenticatable $user, SocialUser $socialUser)
    {
        $user->attachSocial(
            $this->social,
            $socialUser->getId(),
            $socialUser->token,
            $socialUser->expiresIn
        );

        event(new SocialUserAttached($user, $this->social, $socialUser));
    }

    /**
     * Create new system user by social user data.
     *
     * @param Authenticatable $userModel
     * @param SocialProvider $social
     * @param SocialUser $socialUser
     * @return Authenticatable
     */
    public function createNewUser(
        Authenticatable $userModel,
        SocialProvider $social,
        SocialUser $socialUser
    ): Authenticatable {
        $NewUser = $userModel->create(
            $userModel->mapSocialData($socialUser)
        );

        $NewUser->attachSocial(
            $social,
            $socialUser->getId(),
            $socialUser->token,
            $socialUser->expiresIn
        );

        event(new SocialUserCreated($NewUser));

        return $NewUser;
    }
}
