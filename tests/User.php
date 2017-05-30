<?php
namespace ZFort\SocialAuth\Test;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use ZFort\SocialAuth\Traits\UserSocialite;
use Illuminate\Foundation\Auth\Access\Authorizable;
use ZFort\SocialAuth\Contracts\SocialAuthenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements AuthorizableContract, AuthenticatableContract, SocialAuthenticatable
{
    use Authorizable, Authenticatable;
    use UserSocialite;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['email', 'avatar'];
    public $timestamps = false;
    protected $table = 'users';
}