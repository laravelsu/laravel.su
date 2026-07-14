<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('services.ipinfo.token', 'test-token');
    }

    public function testLoginRouteShowsRestrictionForGuestInRussia(): void
    {
        Http::fake([
            'api.ipinfo.io/lite/*' => Http::response(['country_code' => 'RU']),
        ]);

        $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.10'])
            ->get(route('login'))
            ->assertOk()
            ->assertSee('Похоже, вы находитесь в Российской Федерации.')
            ->assertSee('alert border-danger mb-0', false)
            ->assertDontSee('Войти через GitHub');

        Http::assertSent(fn (Request $request) => $request->url()
            === 'https://api.ipinfo.io/lite/203.0.113.10?token=test-token');
    }

    public function testLoginRouteShowsGithubLoginForGuestOutsideRussia(): void
    {
        Http::fake([
            'api.ipinfo.io/lite/*' => Http::response(['country_code' => ' de ']),
        ]);

        $this->withServerVariables(['REMOTE_ADDR' => '198.51.100.20'])
            ->get(route('login'))
            ->assertOk()
            ->assertSee('Войти через GitHub')
            ->assertDontSee('Похоже, вы находитесь в Российской Федерации.');
    }

    public function testLoginRouteFailsClosedWhenIpInfoIsUnavailable(): void
    {
        Http::fake(Http::failedConnection());

        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Похоже, вы находитесь в Российской Федерации.')
            ->assertDontSee('Войти через GitHub');
    }

    public function testLoginRouteFailsClosedWithoutIpInfoToken(): void
    {
        config()->set('services.ipinfo.token');
        Http::preventStrayRequests();

        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Похоже, вы находитесь в Российской Федерации.')
            ->assertDontSee('Войти через GitHub');

        Http::assertNothingSent();
    }

    public function testLoginRouteFailsClosedWhenIpInfoReturnsInvalidCountry(): void
    {
        Http::fake([
            'api.ipinfo.io/lite/*' => Http::response(['country_code' => null]),
        ]);

        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Похоже, вы находитесь в Российской Федерации.')
            ->assertDontSee('Войти через GitHub');
    }

    public function testGithubLoginRouteIsUnavailableInRussia(): void
    {
        Http::fake([
            'api.ipinfo.io/lite/*' => Http::response(['country_code' => 'RU']),
        ]);

        $this
            ->get(route('auth.login'))
            ->assertRedirect(route('login'));
    }

    public function testGithubCallbackRouteIsUnavailableInRussia(): void
    {
        Http::fake([
            'api.ipinfo.io/lite/*' => Http::response(['country_code' => 'RU']),
        ]);

        $this
            ->get(route('auth.callback'))
            ->assertRedirect(route('login'));
    }

    public function testLoginRedirectsAuthenticatedUser(): void
    {
        $user = User::factory()->create(['github_id' => ((int) User::max('github_id')) + 1]);
        $this->actingAs($user);

        $this->get(route('login'))
            ->assertRedirect();
    }

    public function testLogoutRedirectsToHome(): void
    {
        $user = User::factory()->create(['github_id' => ((int) User::max('github_id')) + 1]);
        $this->actingAs($user);

        $this->post(route('logout'))
            ->assertRedirect(route('home'));
    }
}
