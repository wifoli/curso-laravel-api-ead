<?php

namespace Tests\Feature\Api;

use App\Models\Lesson;
use App\Models\Support;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SupportTest extends TestCase
{
    use UtilsTraits;

    public function test_get_my_supports_unauthenticated()
    {
        $response = $this->getJson('/my-supports');

        $response->assertStatus(401);
    }

    public function test_get_my_supports()
    {
        $user = $this->createUser();

        Support::factory()->count(50)->create();
        Support::factory()->count(50)->create([
            'user_id' => $user->id
        ]);

        $token = $this->createTokenToUser($user);

        $response = $this->getJson('/my-supports', $this->defaultHeaders($token));

        $response
            ->assertStatus(200)
            ->assertJsonCount(50, 'data');
    }

    public function test_get_supports_unauthenticated()
    {
        $response = $this->getJson('/supports');

        $response->assertStatus(401);
    }

    public function test_get_supports()
    {
        Support::factory()->count(50)->create();

        $response = $this->getJson('/supports', $this->defaultHeaders());

        $response
            ->assertStatus(200)
            ->assertJsonCount(50, 'data');
    }

    public function test_get_supports_filter_lesson()
    {
        $lesson = Lesson::factory()->create();
        Support::factory()->count(10)->create([
            'lesson_id' => $lesson->id
        ]);
        Support::factory()->count(50)->create();

        $payload = ['lesson' => $lesson->id];

        $response = $this->json('get', '/supports', $payload, $this->defaultHeaders());

        $response
            ->assertStatus(200)
            ->assertJsonCount(10, 'data');
    }

    public function test_get_supports_filter_status()
    {
        Support::factory()->count(50)->create([
            'status' => 'A'
        ]);
        Support::factory()->count(50)->create([
            'status' => 'P'
        ]);
        Support::factory()->count(50)->create([
            'status' => 'C'
        ]);

        $payload = ['status' => 'A'];

        $response = $this->json('get', '/supports', $payload, $this->defaultHeaders());

        $response
            ->assertStatus(200)
            ->assertJsonCount(50, 'data');
    }

    public function test_get_supports_filter_filter()
    {
        $description = 'Esta foi a descrição escolhida para usar no filtro';

        Support::factory()->count(50)->create([
            'description' => $description
        ]);
        Support::factory()->count(200)->create();

        $payload = ['filter' => $description];

        $response = $this->json('get', '/supports', $payload, $this->defaultHeaders());

        $response
            ->assertStatus(200)
            ->assertJsonCount(50, 'data');
    }

    public function test_create_support_unauthenticated()
    {
        $response = $this->getJson('/supports');

        $response->assertStatus(401);
    }

    public function test_create_support_error_validation()
    {
        $response = $this->postJson('/supports', [], $this->defaultHeaders());

        $response->assertStatus(422);
    }

    public function test_create_support()
    {
        $lesson = Lesson::factory()->create();
        $payload = [
            'lesson' => $lesson->id,
            'status' => 'P',
            'description' => 'Uma Descrição',
        ];

        $response = $this->postJson('/supports', $payload, $this->defaultHeaders());

        $response->assertStatus(201);
    }
}
