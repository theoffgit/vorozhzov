<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListTest extends TestCase
{
    public function test_list()
    {
        $response = $this->get('/list');

        $response->assertStatus(200);
        $response->assertSee('create form');

    }
}
