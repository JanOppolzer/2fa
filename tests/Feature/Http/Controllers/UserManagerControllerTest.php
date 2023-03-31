<?php

namespace Tests\Feature\Http\Controllers;

use App\Ldap\User as LdapUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use LdapRecord\Laravel\Testing\DirectoryEmulator;
use Tests\TestCase;

class UserManagerControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_admin_can_grant_manager_rights(): void
    {
        DirectoryEmulator::setup('default');

        $admin = User::factory()->create(['manager' => false, 'admin' => true]);
        $user = User::factory()->create(['manager' => false, 'admin' => false]);

        LdapUser::create([
            config('ldap.user_id') => preg_replace('/@.*$/', '', $user->uniqueid),
        ]);

        $this->assertTrue(Auth::guest());
        $this->assertFalse($user->manager);

        $this
            ->followingRedirects()
            ->actingAs($admin)
            ->post(route('users.grant_manager', $user))
            ->assertOk();

        $user->refresh();

        $this->assertTrue($user->manager);
        $this->assertEquals(route('users.show', $user), url()->current());

        DirectoryEmulator::tearDown();
    }

    /** @test */
    public function an_admin_can_revoke_manager_rights(): void
    {
        DirectoryEmulator::setup('default');

        $admin = User::factory()->create(['manager' => false, 'admin' => true]);
        $user = User::factory()->create(['manager' => true, 'admin' => false]);

        LdapUser::create([
            config('ldap.user_id') => preg_replace('/@.*$/', '', $user->uniqueid),
        ]);

        $this->assertTrue(Auth::guest());
        $this->assertTrue($user->manager);

        $this
            ->followingRedirects()
            ->actingAs($admin)
            ->delete(route('users.revoke_manager', $user))
            ->assertOk();

        $user->refresh();

        $this->assertFalse($user->manager);
        $this->assertEquals(route('users.show', $user), url()->current());

        DirectoryEmulator::tearDown();
    }
}
