<?php

namespace MadWeb\SocialAuth\Test;

class ControllerTestTest extends TestCase
{
    public function test_redirection()
    {
        $response = $this->get(route('social.auth', $this->social));

        $response->assertRedirect('https://facebook.com/oauth');
    }

    public function test_callback()
    {
        $response = $this->get(route('social.callback', $this->social));

        $response->assertRedirect(url(config('social-auth.redirect')));
    }
}
