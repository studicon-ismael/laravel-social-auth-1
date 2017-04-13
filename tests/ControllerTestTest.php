<?php
namespace ZFort\SocialAuth\Test;

class ControllerTestTest extends TestCase
{
    protected $social = ['social' => 'facebook'];

    public function test_redirection()
    {
        $response = $this->get(route('social.auth', $this->social));

        $this->assertContains('https://www.facebook.com', $response->getTargetUrl());
    }

    public function test_callback()
    {
        $response = $this->get(route('social.callback', $this->social));

        dd($response->getContent());

        $this->assertTrue(true);
    }
}
