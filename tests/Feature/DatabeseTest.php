<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class DatabaseTest extends TestCase
{
    /**
     * Can be used as seeder.
     *
     * @return void
     */

    //use RefreshDatabase;

     public function test_user_database()
    {
        $users = User::get();

        User::factory()->count(5)->create();

        $this->assertDatabaseCount('users', ($users->count() + 5));
    }

}
