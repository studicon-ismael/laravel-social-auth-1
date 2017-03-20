<?php

namespace Social\Events;


class SocialUserAttached extends SocialEvent
{
    public $user;
    public $social;
    public $data;

    /**
     * SocialUserAuthenticated constructor.
     * @param array $user
     */
    public function __construct($user, $social, $data)
    {
        $this->user = $user;
        $this->social = $social;
        $this->data = $data;
    }
}

