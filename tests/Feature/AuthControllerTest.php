<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    public function testLoginRouteShowsRestrictionForGuestInRussia(): void
    {
        $this->withHeader('CF-IPCountry', 'RU')
            ->get(route('login'))
            ->assertOk()
            ->assertSee('Похоже, вы находитесь в Российской Федерации.')
            ->assertSee('alert border-danger mb-0', false)
            ->assertDontSee('Войти через GitHub');
    }

    public function testLoginRouteShowsGithubLoginForGuestOutsideRussia(): void
    {
        $this->withHeader('CF-IPCountry', 'DE')
            ->get(route('login'))
            ->assertOk()
            ->assertSee('Войти через GitHub')
            ->assertDontSee('Похоже, вы находитесь в Российской Федерации.');
    }

    public function testLoginRouteFailsClosedWithoutCloudflareCountry(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Похоже, вы находитесь в Российской Федерации.')
            ->assertDontSee('Войти через GitHub');
    }

    public function testGithubLoginRouteIsUnavailableInRussia(): void
    {
        $this->withHeader('CF-IPCountry', 'RU')
            ->get(route('auth.login'))
            ->assertRedirect(route('login'));
    }

    public function testGithubCallbackRouteIsUnavailableInRussia(): void
    {
        $this->withHeader('CF-IPCountry', 'RU')
            ->get(route('auth.callback'))
            ->assertRedirect(route('login'));
    }

    public function testLoginRedirectsAuthenticatedUser(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->get(route('login'))
            ->assertRedirect();
    }

    public function testLogoutRedirectsToHome(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->post(route('logout'))
            ->assertRedirect(route('home'));
    }
}
