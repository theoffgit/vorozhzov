<?php

namespace Tests\Feature;

use Tests\TestCase;

class CreateFormTest extends TestCase
{    
    public function test_create_form()
    {
        $response = $this->get('/create');

        $response->assertStatus(200);
        $response->assertSee('create form');

    }
}
