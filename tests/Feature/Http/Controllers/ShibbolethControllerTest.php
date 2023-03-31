<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class ShibbolethControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function show_message_if_no_shibboleth_available(): void
    {
        $this
            ->get(route('login'))
            ->assertOk()
            ->assertSeeText('login');
    }

    /** @test */
    public function shibboleth_login_redirects_correctly(): void
    {
        $this
            ->withServerVariables(['Shib-Handler' => 'http://localhost'])
            ->get('login');

        $this->assertEquals('http://localhost/login', url()->current());
    }

    /** @test */
    public function an_active_user_can_log_in(): void
    {
        $user = User::factory()->create();

        $this->assertTrue(Auth::guest());

        $this
            ->followingRedirects()
            ->withServerVariables([
                'uniqueId' => $user->uniqueid,
                'cn' => $user->name,
                'mail' => $user->email,
            ])
            ->get('auth');

        $this->assertEquals(route('home'), url()->current());
        $this->assertTrue(Auth::check());
    }

    /** @test */
    public function a_blocked_user_cannot_log_in(): void
    {
        $user = User::factory()->create(['active' => false]);

        $this->assertTrue(Auth::guest());

        $this
            ->followingRedirects()
            ->withServerVariables([
                'uniqueId' => $user->uniqueid,
                'cn' => $user->name,
                'mail' => $user->email,
            ])
            ->get('auth')
            ->assertSeeText('You are blocked.');

        $this->assertEquals('http://localhost/auth', url()->current());
    }

    /** @test */
    public function a_user_can_log_out(): void
    {
        $user = User::factory()->create();

        $this->assertTrue(Auth::guest());

        Auth::login($user);
        Session::regenerate();

        $this->assertTrue(Auth::check());

        $this
            ->actingAs($user)
            ->get(route('logout'))
            ->assertRedirect('http://localhost/Shibboleth.sso/Logout');
    }
}
