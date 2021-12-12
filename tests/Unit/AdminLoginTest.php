<?php
namespace Zekini\CrudGenerator\Tests\Unit;

use Zekini\CrudGenerator\Tests\TestCase;

class AdminLoginTest extends TestCase
{
    
    /**
     * test_admin_can_login
     *
     * @return void
     */
    public function test_admin_can_login()
    {
        $data = [
            'email'=> 'testemail@gmail.com',
            'password'=> 'password'
        ];
        $uri = url('/admin/login');

        $response = $this->post($uri, $data);

        $response->dump();
    }
}