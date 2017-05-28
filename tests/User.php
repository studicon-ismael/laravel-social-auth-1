<?php
namespace ZFort\SocialAuth\Test;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use ZFort\SocialAuth\Contracts\SocialAuthenticatable;
use ZFort\SocialAuth\Traits\UserSocialite;

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