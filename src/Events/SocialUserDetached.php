<?php

namespace Social\Events;


class SocialUserDetached extends SocialEvent
{
    public $user;
    public $social;
    public $isSuccess;

    /**
     * SocialUserAuthenticated constructor.
     * @param array $user
     */
    public function __construct($user, $social, $isSuccess)
    {
        $this->user = $user;
        $this->social = $social;
        $this->isSuccess = $isSuccess;
    }
}

