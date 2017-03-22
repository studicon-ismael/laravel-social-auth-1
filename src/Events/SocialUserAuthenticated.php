<?php

namespace ZFort\SocialAuth\Events;

use Illuminate\Contracts\Auth\Authenticatable;

class SocialUserAuthenticated extends SocialEvent
{
    public $user;

    /**
     * SocialUserAuthenticated constructor.
     * @param Authenticatable $user
     */
    public function __construct(Authenticatable $user)
    {
        $this->user = $user;
    }
}
