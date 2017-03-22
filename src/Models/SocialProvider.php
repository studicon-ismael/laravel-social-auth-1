<?php

namespace ZFort\SocialAuth\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SocialProvider
 *
 * @param string $slug
 * @param string $label
 * @package Social\Models
 * @property int $id
 * @property string $label
 * @property string $slug
 * @property string $created_at
 * @property string $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 * @method static \Illuminate\Database\Query\Builder|\ZFort\SocialAuth\Models\SocialProvider whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\ZFort\SocialAuth\Models\SocialProvider whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\ZFort\SocialAuth\Models\SocialProvider whereLabel($value)
 * @method static \Illuminate\Database\Query\Builder|\ZFort\SocialAuth\Models\SocialProvider whereSlug($value)
 * @method static \Illuminate\Database\Query\Builder|\ZFort\SocialAuth\Models\SocialProvider whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SocialProvider extends Model
{
    /**
     * {@inheritdoc}
     */
    protected $fillable = ['slug', 'label'];

    /**
     * {@inheritdoc}
     */
    public $timestamps = false;

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(config('auth.providers.users.model'), 'user_has_social_provider');
    }
}
