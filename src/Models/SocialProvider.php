<?php

namespace Social\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SocialProvider
 *
 * @param string $slug
 * @param string $label
 *
 * @package Social\Models
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
