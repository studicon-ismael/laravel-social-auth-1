<?php

namespace ZFort\SocialAuth\Traits;

use ZFort\SocialAuth\Events\SocialUserAuthenticated;
use ZFort\SocialAuth\Models\SocialProvider;
use DateInterval;
use Laravel\Socialite\Contracts\User;

trait UserSocialite
{
    /**
     * User socials relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function socials()
    {
        $social_pivot_table_name = config('social-auth.table_names.user_has_social_provider');

        return $this->belongsToMany(SocialProvider::class, $social_pivot_table_name);
    }

    /**
     * Check whether user attached to social network
     *
     * @param $slug
     * @return mixed
     */
    public function isAttached(string $slug): bool
    {
        return $this->socials()->where(['slug' => $slug])->exists();
    }

    /**
     * Attach social network provider to the user
     *
     * @param SocialProvider $social
     * @param string $social_id
     * @param string $token
     * @param int $expires_in
     */
    public function attachSocial($social, string $social_id, string $token, int $expires_in = null)
    {
        $data = compact('social_id', 'token');

        $expires_in = $expires_in
            ? date_create('now')
                ->add(DateInterval::createFromDateString($expires_in . ' seconds'))
                ->format($this->getDateFormat())
            : false;

        if ($expires_in) {
            $data['expires_in'] = $expires_in;
        }

        $this->socials()->attach($social, $data);

        event(new SocialUserAuthenticated($this, $social, $data));
    }

    /**
     * @param User $socialUser
     * @return array
     */
    public function mapSocialData(User $socialUser)
    {
        $raw = $socialUser->getRaw();
        $name = $socialUser->getName() ?? $socialUser->getNickname();
        $name = $name ?? $socialUser->getEmail();

        $result = [
            'email' => $socialUser->getEmail(),
            'name' => $name,
            'verified' => $raw['verified'] ?? true,
            'token' => $socialUser->token,
            'avatar' => $socialUser->getAvatar(),
            'expiresIn' => $socialUser->expiresIn
        ];

        return $result;
    }

    /**
     * Get model email field name
     *
     * @return string
     */
    public function getEmailField(): string
    {
        return 'email';
    }
}
