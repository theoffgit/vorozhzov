<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use App\Models\Json;

class UpdateTest extends TestCase
{
    public function test_whithout_bearer()
    {
        $response = $this->getJson('/update');
        $response->assertStatus(401); // unauthorized

        $response = $this->postJson('/update');
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


    public function test_update_success()
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
                        ->postJson('/store', ['data' => json_encode($user)]);

                        $response->assertStatus(200);
        $respData = json_decode($response->original);
        //echo $respData->id."\n";

        $response = $this
                        ->withHeaders(['Authorization'=>'Bearer '.$token,
                            'Accept' => 'application/json'])
                        ->postJson('/update', ['id' =>  $respData->id, 'data' => '$data->name=NewNameByPost']);
        
        $response->assertStatus(200);
        $json = Json::where('id', '=', $respData->id)->first();
        $data = json_decode($json->data);
   
        $this->assertEquals($data->name, 'NewNameByPost');

        $response = $this
                        ->withHeaders(['Authorization'=>'Bearer '.$token,
                            'Accept' => 'application/json'])
                        ->getJson('/update?id='.$respData->id.'&data='.urlencode('$data->name=NewNameByGet'));

        $response->assertStatus(200);
        $json = Json::where('id', '=', $respData->id)->first();
        $data = json_decode($json->data);
   
        $this->assertEquals($data->name, 'NewNameByGet');        
    }
}
