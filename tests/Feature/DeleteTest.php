<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use App\Models\Json;

class DeleteTest extends TestCase
{
    public function test_whithout_bearer()
    {
        $response = $this->getJson('/delete');
        $response->assertStatus(401); // unauthorized

        $response = $this->postJson('/delete');
        $response->assertStatus(401); // unauthorized
    }

    public function test_without_id()
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
                      ->postJson('/delete');

        $response->assertStatus(422);
        $response->assertJson([
           'message' => 'The id field is required.',
        ]);

        $response = $this
            ->withHeaders(['Authorization'=>'Bearer '.$token,
                'Accept' => 'application/json'])
            ->getJson('/delete');

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'The id field is required.',
        ]);
    }

    public function test_delete_wrong_user()
    {
        $user = User::all()->random();
        $json = Json::where('user_id', '<>', $user->id)->first();

        Artisan::call('gettoken', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $token = Artisan::output();

        $response = $this
                        ->withHeaders(['Authorization'=>'Bearer '.$token,
                            'Accept' => 'application/json'])
                        ->postJson('/delete', ['id' => $json->id]);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 0,
        ]);

        
        $response = $this
                        ->withHeaders(['Authorization'=>'Bearer '.$token,
                            'Accept' => 'application/json'])
                        ->getJson('/delete?id='.$json->id);

            $response->assertStatus(200);
            $response->assertJson([
                'message' => '0',
            ]);        
    }

    public function test_delete_success()
    {
        $json = Json::all()->random();
        $user = User::where('id', '=', $json->user_id)->first();        

        Artisan::call('gettoken', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $token = Artisan::output();

        $response = $this
                        ->withHeaders(['Authorization'=>'Bearer '.$token,
                            'Accept' => 'application/json'])
                        ->postJson('/delete', ['id' => $json->id]);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 1,
        ]); 
    }
}
