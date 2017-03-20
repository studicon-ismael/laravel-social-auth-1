<?php

namespace Social\Traits;

use Social\Events\SocialUserAuthenticated;
use Social\Models\SocialProvider;
use DateInterval;
use Laravel\Socialite\Two\User;

trait UserSocialite
{
    /**
     * User socials relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function socials()
    {
        $social_pivot_table_name = config('social.table_names.user_has_social_provider');

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
        return  $this->socials()->where(['slug' => $slug])->exists();
    }

    /**
     * Attach social network provider to the user
     *
     * @param SocialProvider $social
     * @param string $social_id
     * @param string $token
     * @param int $expires_in
     */
    public function attachSocial(SocialProvider $social, string $social_id, string $token, int $expires_in = null)
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
     * @param User $social_user
     * @return array
     */
    public function mapSocialFields(User $social_user)
    {
        $raw = $social_user->getRaw();
        $name = $social_user->getNickname() ?? $social_user->getName();
        $name = $name ?? $social_user->getEmail();

        $result = [
            'email' => $social_user->getEmail(),
            'first_name' => $name,
            'verified' => isset($raw['verified']) ? $raw['verified'] : true,
            'token' => $social_user->token,
            'avatar' => $social_user->getAvatar(),
            'expiresIn' => $social_user->expiresIn
        ];

        return $result;
    }
}
