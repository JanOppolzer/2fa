<?php

namespace Tests\Feature\Http\Controllers;

use App\Ldap\User as LdapUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use LdapRecord\Laravel\Testing\DirectoryEmulator;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function unauthenticated_users_are_redirected_to_login_page(): void
    {
        $this->assertTrue(Auth::guest());

        $this
            ->get(route('users.index'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function a_user_cannot_see_the_list_of_users(): void
    {
        $user = User::factory()->create(['manager' => false, 'admin' => false]);

        $this->assertTrue(Auth::guest());

        $this
            ->actingAs($user)
            ->get(route('users.index'))
            ->assertForbidden();
    }

    /** @test */
    public function a_manager_can_see_the_list_of_users(): void
    {
        $manager = User::factory()->create(['manager' => true, 'admin' => false]);

        $this->assertTrue(Auth::guest());

        $this
            ->actingAs($manager)
            ->get(route('users.index'))
            ->assertOk();

        $this->assertEquals(route('users.index'), url()->current());
    }

    /** @test */
    public function an_admin_can_see_the_list_of_users(): void
    {
        $admin = User::factory()->create(['manager' => false, 'admin' => true]);

        $this->assertTrue(Auth::guest());

        $this
            ->actingAs($admin)
            ->get(route('users.index'))
            ->assertOk();

        $this->assertEquals(route('users.index'), url()->current());
    }

    /** @test */
    public function a_user_can_see_their_details(): void
    {
        DirectoryEmulator::setup('default');

        $user = User::factory()->create(['manager' => false, 'admin' => false]);

        LdapUser::create([
            config('ldap.user_id') => preg_replace('/@.*$/', '', $user->uniqueid),
        ]);

        $this->assertTrue(Auth::guest());

        $this
            ->actingAs($user)
            ->get(route('users.show', $user))
            ->assertOk()
            ->assertSeeInOrder([
                __('users.show', ['name' => $user->name]),
                __('common.name'),
                $user->name,
                __('common.uniqueid'),
                $user->uniqueid,
                __('common.email'),
                $user->email,
                __('common.2fa_status'),
                __('common.enable_2fa'),
            ]);

        DirectoryEmulator::tearDown();
    }

    /** @test */
    public function a_user_cannot_see_others_details(): void
    {
        $user = User::factory()->create(['manager' => false, 'admin' => false]);
        $other_user = User::factory()->create();

        $this->assertTrue(Auth::guest());

        $this
            ->actingAs($user)
            ->get(route('users.show', $other_user))
            ->assertForbidden();

        $this->assertEquals(route('users.show', $other_user), url()->current());
    }

    /** @test */
    public function a_manager_can_see_others_details(): void
    {
        DirectoryEmulator::setup('default');

        $manager = User::factory()->create(['manager' => true, 'admin' => false]);
        $user = User::factory()->create();

        LdapUser::create([
            config('ldap.user_id') => preg_replace('/@.*$/', '', $user->uniqueid),
        ]);

        $this->assertTrue(Auth::guest());

        $this
            ->actingAs($manager)
            ->get(route('users.show', $user))
            ->assertSeeInOrder([
                __('users.show', ['name' => $user->name]),
                __('common.name'),
                $user->name,
                __('common.uniqueid'),
                $user->uniqueid,
                __('common.email'),
                $user->email,
                __('common.2fa_status'),
                __('common.enable_2fa'),
            ]);

        DirectoryEmulator::tearDown();
    }

    /** @test */
    public function an_admin_can_see_others_details(): void
    {
        DirectoryEmulator::setup('default');

        $admin = User::factory()->create(['manager' => false, 'admin' => true]);
        $user = User::factory()->create();

        LdapUser::create([
            config('ldap.user_id') => preg_replace('/@.*$/', '', $user->uniqueid),
        ]);

        $this->assertTrue(Auth::guest());

        $this
            ->actingAs($admin)
            ->get(route('users.show', $user))
            ->assertSeeInOrder([
                __('users.show', ['name' => $user->name]),
                __('common.name'),
                $user->name,
                __('common.uniqueid'),
                $user->uniqueid,
                __('common.email'),
                $user->email,
                __('common.2fa_status'),
                __('common.enable_2fa'),
            ]);

        DirectoryEmulator::tearDown();
    }

    /** @test */
    public function a_user_can_setup_2fa(): void
    {
        DirectoryEmulator::setup('default');

        $user = User::factory()->create(['manager' => false, 'admin' => false]);
        LdapUser::create([
            config('ldap.user_id') => preg_replace('/@.*$/', '', $user->uniqueid),
        ]);

        $this->assertTrue(Auth::guest());

        $this
            ->followingRedirects()
            ->actingAs($user)
            ->patch(route('users.update', $user))
            ->assertOk()
            ->assertSeeText(__('users.scan_qr'));

        $this->assertEquals(route('users.show', $user), url()->current());

        DirectoryEmulator::tearDown();
    }

    /** @test */
    public function a_user_cannot_disable_2fa(): void
    {
        DirectoryEmulator::setup('default');

        $user = User::factory()->create(['manager' => false, 'admin' => false]);

        LdapUser::create([
            config('ldap.user_id') => preg_replace('/@.*$/', '', $user->uniqueid),
            'tokenSeeds' => Str::random(10),
        ]);

        $this->assertTrue(Auth::guest());

        $this
            ->actingAs($user)
            ->delete(route('users.destroy', $user))
            ->assertForbidden();

        DirectoryEmulator::tearDown();
    }

    /** @test */
    public function a_manager_can_disable_2fa_to_them(): void
    {
        DirectoryEmulator::setup('default');

        $manager = User::factory()->create(['manager' => true, 'admin' => false]);

        LdapUser::create([
            config('ldap.user_id') => preg_replace('/@.*$/', '', $manager->uniqueid),
            'tokenSeeds' => Str::random(10),
        ]);

        $this->assertTrue(Auth::guest());

        $this
            ->followingRedirects()
            ->actingAs($manager)
            ->delete(route('users.destroy', $manager))
            ->assertOk()
            ->assertSeeText(__('common.2fa_disabled'));

        $this->assertEquals(route('users.show', $manager), url()->current());

        DirectoryEmulator::tearDown();
    }

    /** @test */
    public function a_manager_can_disable_2fa_to_a_user(): void
    {
        DirectoryEmulator::setup('default');

        $manager = User::factory()->create(['manager' => true, 'admin' => false]);
        $user = User::factory()->create();

        LdapUser::create([
            config('ldap.user_id') => preg_replace('/@.*$/', '', $user->uniqueid),
            'tokenSeeds' => Str::random(10),
        ]);

        $this->assertTrue(Auth::guest());

        $this
            ->followingRedirects()
            ->actingAs($manager)
            ->delete(route('users.destroy', $user))
            ->assertOk()
            ->assertSeeText(__('common.2fa_disabled'));

        $this->assertEquals(route('users.show', $user), url()->current());

        DirectoryEmulator::tearDown();
    }

    /** @test */
    public function an_admin_can_disable_2fa_to_them(): void
    {
        DirectoryEmulator::setup('default');

        $admin = User::factory()->create(['manager' => false, 'admin' => true]);

        LdapUser::create([
            config('ldap.user_id') => preg_replace('/@.*$/', '', $admin->uniqueid),
            'tokenSeeds' => Str::random(10),
        ]);

        $this->assertTrue(Auth::guest());

        $this
            ->followingRedirects()
            ->actingAs($admin)
            ->delete(route('users.destroy', $admin))
            ->assertOk()
            ->assertSeeText(__('common.2fa_disabled'));

        $this->assertEquals(route('users.show', $admin), url()->current());

        DirectoryEmulator::tearDown();
    }

    /** @test */
    public function an_admin_can_disable_2fa_to_a_user(): void
    {
        DirectoryEmulator::setup('default');

        $admin = User::factory()->create(['manager' => false, 'admin' => true]);
        $user = User::factory()->create();

        LdapUser::create([
            config('ldap.user_id') => preg_replace('/@.*$/', '', $user->uniqueid),
            'tokenSeeds' => Str::random(10),
        ]);

        $this->assertTrue(Auth::guest());

        $this
            ->followingRedirects()
            ->actingAs($admin)
            ->delete(route('users.destroy', $user))
            ->assertOk()
            ->assertSeeText(__('common.2fa_disabled'));

        $this->assertEquals(route('users.show', $user), url()->current());

        DirectoryEmulator::tearDown();
    }
}
