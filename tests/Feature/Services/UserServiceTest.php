<?php

namespace Tests\Feature\Services;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use LdapRecord\Laravel\Testing\DirectoryEmulator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_throws_exception(): void
    {
        DirectoryEmulator::setup('default');

        $user = User::factory()->create();

        $this->expectException(NotFoundHttpException::class);

        $userService = new UserService();
        $userService->getLdapUser($user);

        DirectoryEmulator::tearDown();
    }
}
