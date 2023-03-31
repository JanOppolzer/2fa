<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\SearchUsers;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SearchUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function search_users_component_can_render(): void
    {
        $component = Livewire::test(SearchUsers::class);

        $component->assertStatus(200);
    }

    /** @test */
    public function users_index_page_contains_search_users_component(): void
    {
        $admin = User::factory()->create(['manager' => false, 'admin' => true]);
        User::factory(10)->create();

        $this->assertCount(11, User::all());

        $this
            ->actingAs($admin)
            ->get(route('users.index'))
            ->assertSeeLivewire(SearchUsers::class);
    }

    /** @test */
    public function search_users_component_can_search_users(): void
    {
        $alice = User::factory()->create(['manager' => false, 'admin' => true]);
        $bob = User::factory()->create();

        $this->assertCount(2, User::all());

        Livewire::test(SearchUsers::class)
            ->set('search', $alice->name)
            ->assertSet('search', $alice->name)
            ->assertSee($alice->name)
            ->assertDontSee($bob->name);
    }
}
