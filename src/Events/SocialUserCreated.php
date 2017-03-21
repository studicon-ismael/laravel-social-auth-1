<?php

namespace ZFort\SocialAuth\Events;

class SocialUserCreated extends SocialEvent
{
    public $user;

    /**
     * SocialUserAuthenticated constructor.
     * @param array $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }
}

