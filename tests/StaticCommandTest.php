<?php

/**
 * Created by PhpStorm.
 * User: Jorge
 * Date: 19/04/2017
 * Time: 07:26
 */

use jjsquady\MikrotikApi\Commands\Interfaces;
use jjsquady\MikrotikApi\Core\Collection;
use jjsquady\MikrotikApi\Entity\GeneticEntity;
use jjsquady\MikrotikApi\Entity\InterfaceEntity;
use jjsquady\MikrotikApi\Facades\MikrotikFacade as Mikrotik;
use Orchestra\Testbench\TestCase;

class StaticCommandTest extends TestCase
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
        $client = Mikrotik::connect(['192.168.0.20', 'admin', '']);
        return $client;
    }

    public function test_execute_commmand()
    {
        $this->assertInstanceOf(Interfaces::class,
            Interfaces::bind($this->getConn())->executeCommand('/interface/print'));
    }

    public function test_execute_command_sync_response_collection()
    {
        $response = Interfaces::bind($this->getConn())->executeCommand('/interface/print')->get();
        $this->assertInstanceOf(Collection::class, $response);
    }

    public function test_execute_command_sync_response_array()
    {
        $response = Interfaces::bind($this->getConn())
                              ->executeCommand('/interface/print')
                              ->get()->toArray();
        var_dump($response);
        $this->assertEquals(true, is_array($response));
    }

    public function test_returns_an_generic_entity()
    {
        $response = Interfaces::bind($this->getConn())->executeCommand('/interface/print')->get()->first();
        $this->assertInstanceOf(GeneticEntity::class, $response);
    }

    public function test_returns_an_user_defined_entity()
    {
        $response = Interfaces::bind($this->getConn())->executeCommand('/interface/print')->get(InterfaceEntity::class)->first();
        $this->assertInstanceOf(InterfaceEntity::class, $response);
    }

}
