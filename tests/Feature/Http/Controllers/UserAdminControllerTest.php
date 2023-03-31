<?php

namespace Tests\Feature\Http\Controllers;

use App\Ldap\User as LdapUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use LdapRecord\Laravel\Testing\DirectoryEmulator;
use Tests\TestCase;

class UserAdminControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_admin_can_grant_admin_rights(): void
    {
        DirectoryEmulator::setup('default');

        $admin = User::factory()->create(['manager' => false, 'admin' => true]);
        $user = User::factory()->create(['manager' => false, 'admin' => false]);

        LdapUser::create([
            config('ldap.user_id') => preg_replace('/@.*$/', '', $user->uniqueid),
        ]);

        $this->assertTrue(Auth::guest());
        $this->assertFalse($user->admin);

        $this
            ->followingRedirects()
            ->actingAs($admin)
            ->post(route('users.grant_admin', $user))
            ->assertOk();

        $user->refresh();

        $this->assertTrue($user->admin);
        $this->assertEquals(route('users.show', $user), url()->current());

        DirectoryEmulator::tearDown();
    }

    /** @test */
    public function an_admin_can_revoke_admin_rights(): void
    {
        DirectoryEmulator::setup('default');

        $admin = User::factory()->create(['manager' => false, 'admin' => true]);
        $user = User::factory()->create(['manager' => false, 'admin' => true]);

        LdapUser::create([
            config('ldap.user_id') => preg_replace('/@.*$/', '', $user->uniqueid),
        ]);

        $this->assertTrue(Auth::guest());
        $this->assertTrue($user->admin);

        $this
            ->followingRedirects()
            ->actingAs($admin)
            ->delete(route('users.revoke_admin', $user))
            ->assertOk();

        $user->refresh();

        $this->assertFalse($user->admin);
        $this->assertEquals(route('users.show', $user), url()->current());

        DirectoryEmulator::tearDown();
    }
}
