<?php

namespace ZFort\SocialAuth\Events;

class SocialUserAttached extends SocialEvent
{
    public $user;
    public $social;
    public $data;

    /**
     * SocialUserAuthenticated constructor.
     * @param array $user
     * @param $social
     * @param $data
     */
    public function __construct($user, $social, $data)
    {
        $this->user = $user;
        $this->social = $social;
        $this->data = $data;
    }
}
