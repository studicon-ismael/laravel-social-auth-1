<?php

namespace ZFort\SocialAuth\Test;

class EnvironmentTest extends TestCase
{
    /** @test */
    public function facebook_provider_exists()
    {
        $social_model = config('social-auth.models.social');

        $this->assertTrue($social_model::whereSlug('facebook')->exists());
    }

    /** @test */
    public function google_provider_exists()
    {
        $social_model = config('social-auth.models.social');

        $this->assertTrue($social_model::whereSlug('google')->exists());
    }
}