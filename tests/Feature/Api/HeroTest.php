<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HeroTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $user = User::firstOrCreate([
            'name' => 'Guest',
            'email' => 'guest@'.parse_url(env('APP_URL'))['host'],
        ], [
            'password' => bcrypt('guest'),
        ]);

        $response = $this->actingAs($user)
            ->get('/hero');
        var_dump($response);
        $response->assertStatus(200);
    }
}
