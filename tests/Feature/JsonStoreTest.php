<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Testing\Fluent\AssertableJson;


class JsonStoreTest extends TestCase
{
    public function test_whithout_bearer()
    {
        $response = $this->getJson('/store');
        $response->assertStatus(401); // unauthorized
    
        $response = $this->postJson('/store');
        $response->assertStatus(401); // unauthorized
    }

    public function test_without_data()
    {   
        $user = User::all()->random();

        Artisan::call('gettoken', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $token = Artisan::output();

        $response = $this
                      ->withHeaders(['Authorization'=>'Bearer '.$token,
                                   'Accept' => 'application/json'])
                      ->postJson('/store');

        $response->assertStatus(422);
        $response->assertJson([
           'message' => 'The data field is required.',
        ]);

        $response = $this
            ->withHeaders(['Authorization'=>'Bearer '.$token,
                'Accept' => 'application/json'])
            ->getJson('/store');

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'The data field is required.',
        ]);     
    }

    public function test_store_success()
    {   
        $user = User::all()->random();

        Artisan::call('gettoken', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $token = Artisan::output();
        echo $token."\n";

        $response = $this
                        ->withHeaders(['Authorization'=>'Bearer '.$token,
                            'Accept' => 'application/json'])
                        ->postJson('/store', ['data' => json_encode($user)]);
       
                        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Success!',
        ]);
           
        $response->assertJsonStructure([
            'message',
            'id',
            'alltime',
            'dbtime',
            'memory',
        ]);

        $response = $this
                        ->withHeaders(['Authorization'=>'Bearer '.$token,
                            'Accept' => 'application/json'])
                        ->getJson('/store?data='.urlencode(json_encode($user)));

            $response->assertStatus(200);
            $response->assertJson([
                'message' => 'Success!',
            ]);

        $response->assertJsonStructure([
            'message',
            'id',
            'alltime',
            'dbtime',
            'memory',
        ]);
    }

}
