<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSaldo()
    {
        $response = $this->get('/api/saldo/68742');

        $response->assertStatus(200);
    }

    public function testSacar()
    {
        $response = $this->get('/api/sacar/68742/100');

        $response->assertStatus(200);
    }

    public function testDepositar()
    {
        $response = $this->get('/api/depositar/68742/100');

        $response->assertStatus(200);
    }
}
