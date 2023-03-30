<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Tests\TestCase;

class UserProfileControllerTest extends TestCase
{
    /** @test */
    public function it_redirects_to_users_profile(): void
    {
        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->get(route('profile'))
            ->assertRedirect(route('users.show', $user));
    }
}
