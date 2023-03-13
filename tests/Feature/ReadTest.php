<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Json;

class ReadTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_read_without_id()
    {
        $response = $this->get('/read');

        $response->assertRedirectToRoute('json.list');
    }


    public function test_read_success()
    {
        $json = Json::all()->random();

        $response = $this->get('/read?id='.$json->id);

        $response->assertStatus(200);
        $response->assertSee('read');
    }
}
