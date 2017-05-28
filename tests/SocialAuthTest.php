<?php

namespace ZFort\SocialAuth\Test;

use ZFort\SocialAuth\Exceptions\SocialGetUserInfoException;

class SocialAuthTest extends TestCase
{
    protected $testEmail = 'test.user@test.com';

    public function test_auth_social()
    {
        $this->socialiteMock->setEmail($this->testEmail)->create();

        $this->get(route('social.callback', $this->social));

        $this->assertDatabaseHas('users', ['email' => $this->testEmail]);
    }

    public function test_attach_social()
    {
        $User = $this->getTestUser();

        $this->actingAs($User)->get(route('social.callback', $this->social));

        $this->assertTrue($User->socials()->where('slug', $this->social['social'])->exists());
    }

    public function test_request_exception()
    {
        $this->disableExceptionHandling();

        $this->socialiteMock->withRequestException()->create();

        $this->expectException(SocialGetUserInfoException::class);

        $this->get(route('social.callback', $this->social));
    }

    public function test_null_user_return()
    {
        $this->disableExceptionHandling();

        $this->socialiteMock->withNullUser()->create();

        $this->expectException(SocialGetUserInfoException::class);

        $this->get(route('social.callback', $this->social));
    }
}
