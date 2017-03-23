<?php

namespace ZFort\SocialAuth\Contracts;

use Laravel\Socialite\Contracts\User;

interface SocialAuthenticatable
{
    /**
     * User socials relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function socials();

    /**
     * Check whether user attached to social network
     *
     * @param string $slug
     * @return bool
     */
    public function isAttached(string $slug): bool;

    /**
     * Attach social network provider to the user
     *
     * @param $social
     * @param string $social_id
     * @param string $token
     * @param int $expires_in
     */
    public function attachSocial($social, string $social_id, string $token, int $expires_in = null);

    /**
     * @param User $socialUser
     * @return array
     */
    public function mapSocialData(User $socialUser);

    /**
     * Get model email field name
     *
     * @return string
     */
    public function getEmailField(): string;
}
