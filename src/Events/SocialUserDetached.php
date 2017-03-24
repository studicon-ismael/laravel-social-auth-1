<?php

namespace ZFort\SocialAuth\Events;

use Illuminate\Contracts\Auth\Authenticatable;

class SocialUserDetached extends SocialEvent
{
    /**
     * @var Authenticatable
     */
    public $user;

    /**
     * @var object
     */
    public $social;

    /**
     * @var bool
     */
    public $isSuccess;

    /**
     * SocialUserAuthenticated constructor.
     * @param Authenticatable $user
     * @param $social
     * @param bool $isSuccess
     */
    public function __construct(Authenticatable $user, $social, bool $isSuccess)
    {
        $this->user = $user;
        $this->social = $social;
        $this->isSuccess = $isSuccess;
    }
}
