<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;


class GetTokenArtisanTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_token()
    {
        $users = User::get();
        if(!$users->count()){
            $user=User::factory()->count(1)->create();
        }
        
        $user = User::all()->random();
        Artisan::call('gettoken', [
            'email' => $user->email,
            'password' => 'gfhjkm321',
        ]);
    
        $token = trim(Artisan::output());
        $this->assertNotEquals("user with such credentials doesn't exists", $token);
    }
}
