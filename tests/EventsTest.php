<?php

namespace ZFort\SocialAuth\Test;

use Mockery;
use Illuminate\Support\Facades\Event;
use ZFort\SocialAuth\Models\SocialProvider;
use ZFort\SocialAuth\SocialProviderManager;
use ZFort\SocialAuth\Events\SocialUserCreated;
use ZFort\SocialAuth\Events\SocialUserAttached;
use ZFort\SocialAuth\Events\SocialUserDetached;
use ZFort\SocialAuth\Events\SocialUserAuthenticated;

class EventsTest extends TestCase
{
    protected $testEmail = 'some.mail@mail.com';

    public function setUp()
    {
        parent::setUp();

        Event::fake();
    }

    public function test_social_user_created()
    {
        $this->socialiteMock->setEmail($this->testEmail)->create();

        $this->expectsEvents(SocialUserCreated::class);

        $this->get(route('social.callback', $this->social));
    }

    public function test_social_user_attach()
    {
        $Manager = new SocialProviderManager(SocialProvider::first());

        $SocialUser = Mockery::mock(\Laravel\Socialite\Two\User::class);
        $SocialUser->token = 'random-token';
        $SocialUser->expiresIn = 5000;
        $SocialUser->shouldReceive('getId')->andReturn('random-id');

        $this->expectsEvents(SocialUserAttached::class);

        $Manager->attach($this->getTestUser(), $SocialUser);
    }

    public function test_social_user_authenticated()
    {
        $this->socialiteMock->create('token', 'social-id');

        $User = $this->getTestUser();

        $User->socials()->attach(
            SocialProvider::whereSlug($this->social['social'])->first(),
            [
                'social_id' => 'social-id',
                'token' => 'token',
            ]
        );

        $this->expectsEvents(SocialUserAuthenticated::class);

        $this->get(route('social.callback', $this->social));
    }

    public function test_social_user_detach()
    {
        $this->socialiteMock->create('token', 'social-id');

        $User = $this->getTestUser();

        $User->socials()->attach(
            SocialProvider::whereSlug($this->social['social'])->first(),
            [
                'social_id' => 'social-id',
                'token' => 'token',
            ]
        );

        $this->expectsEvents(SocialUserDetached::class);

        $this->actingAs($User)->get(route('social.detach', $this->social));
    }
}
