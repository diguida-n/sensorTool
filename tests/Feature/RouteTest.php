<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class RouteTest extends TestCase
{
    
    /** @test */
    public function test_home()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /** @test */
    public function test_login()
    {
        $response = $this->get('/admin/login');
        $response->assertStatus(200);
    }

    /** @test */
    public function test_registration_blocked()
    {
        try {
            $response = $this->get('/admin/register');
        } catch (HttpException $e) {
            $this->assertTrue($e->getStatusCode()==403);
        }
       
    }

}
