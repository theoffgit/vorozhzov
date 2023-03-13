<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Json;

class UpdateFormTest extends TestCase
{
    public function test_update_form_without_id()
    {
        $response = $this->get('/updateform');

        $response->assertRedirectToRoute('json.list');
    }


    public function test_update_form_success()
    {
        $json = Json::all()->random();

        $response = $this->get('/updateform?id='.$json->id);

        $response->assertStatus(200);
        $response->assertSee('update form');
    }
}
