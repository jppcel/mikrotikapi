<?php
/**
 * Created by PhpStorm.
 * User: Jorge
 * Date: 18/04/2017
 * Time: 05:05
 */

use jjsquady\MikrotikApi\Core\Auth;
use jjsquady\MikrotikApi\Core\Client;
use jjsquady\MikrotikApi\Exceptions\ConnectionException;
use jjsquady\MikrotikApi\Facades\MikrotikFacade as Mikrotik;
use Orchestra\Testbench\TestCase;


class AuthTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [jjsquady\MikrotikApi\MikrotikServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Mikrotik' => jjsquady\MikrotikApi\Facades\MikrotikFacade::class
        ];
    }

    public function getConn()
    {
        $conn = Mikrotik::connect(['192.168.0.20','admin','']);
        return $conn;
    }

    public function test_is_istanciable()
    {
        $this->assertInstanceOf(Auth::class, new Auth('192.168.0.20', 'admin', ''));
    }

    public function test_if_auth_accepts_array_args()
    {
        $array = ['192.168.0.20','admin', ''];
        $this->assertInstanceOf(Auth::class, new Auth(...$array));
    }

    public function test_if_connects_and_authenticate()
    {
        Mikrotik::connect(new Auth('192.168.0.20', 'admin', ''));
        $this->assertTrue(Mikrotik::isConnected());
    }

    public function test_if_connection_returns_client_instance()
    {
        $client = Mikrotik::connect(new Auth('192.168.0.20', 'admin', ''));
        $this->assertInstanceOf(Client::class, $client);
    }

//    public function test_if_throws_connection_exception()
//    {
//        $this->expectException(ConnectionException::class);
//
//        Mikrotik::connect(new Auth('192.168.0.20', 'admin2', ''));
//
//    }

}
