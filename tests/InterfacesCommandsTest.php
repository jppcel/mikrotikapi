<?php
/**
 * Created by PhpStorm.
 * User: Jorge
 * Date: 19/04/2017
 * Time: 01:17
 */

use jjsquady\MikrotikApi\Commands\Interfaces;
use jjsquady\MikrotikApi\Core\Collection;
use jjsquady\MikrotikApi\Core\QueryBuilder;
use jjsquady\MikrotikApi\Entity\Ethernet;
use jjsquady\MikrotikApi\Facades\MikrotikFacade as Mikrotik;
use Orchestra\Testbench\TestCase;

class InterfacesCommandsTest extends TestCase
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
        $client = Mikrotik::connect(['192.168.0.20','admin','']);
        return $client;
    }

    public function test_if_is_instanciable()
    {
        $this->assertInstanceOf(Interfaces::class, new Interfaces($this->getConn()));
    }

    public function test_binds_client_statically()
    {
        $this->assertInstanceOf(Interfaces::class, Interfaces::bind($this->getConn()));
    }

    public function test_get_collection_thought_bind()
    {
        $this->assertInstanceOf(Collection::class, Interfaces::bind($this->getConn())->ethernet()->all());
    }

    public function test_if_base_command_was_set()
    {
        $icomm = new Interfaces($this->getConn());
        $this->assertEquals('/interface', $icomm->getBaseCommand());
    }

    public function test_if_a_command_returns_a_querybuilder()
    {
        $icomm = new Interfaces($this->getConn());
        $this->assertInstanceOf(QueryBuilder::class, $icomm->ethernet());
    }

    public function test_if_all_method_result_its_a_collection()
    {
        $icomm = new Interfaces($this->getConn());
        $this->assertInstanceOf(Collection::class, $icomm->ethernet()->all());
    }

    public function test_if_an_collection_item_its_a_entity()
    {
        $icomm = new Interfaces($this->getConn());
        $interface = $icomm->ethernet()->all()->first();
        $this->assertInstanceOf(Ethernet::class, $interface);
    }

    public function test_if_first_method_returns_an_entity()
    {
        $icomm = new Interfaces($this->getConn());
        $interface = $icomm->ethernet()->first();
        $this->assertInstanceOf(Ethernet::class, $interface);
    }

    public function test_if_entity_converts_to_array()
    {
        $icomm = new Interfaces($this->getConn());
        $interface = $icomm->ethernet()->first()->toArray();
        $this->assertEquals(true, is_array($interface));
    }

    public function test_if_a_collection_coverts_to_array()
    {
        $icomm = new Interfaces($this->getConn());
        $interfaces = $icomm->ethernet()->all()->toArray();
        $this->assertEquals(true, is_array($interfaces));
    }

    public function test_if_interface_itself_returns_a_query_collection()
    {
        $icomm = new Interfaces($this->getConn());
        $interfaces = $icomm->interface()->all();
        $this->assertInstanceOf(Collection::class, $interfaces);
    }

    public function test_bonding_command()
    {
        $icomm = new Interfaces($this->getConn());
        $interfaces = $icomm->bonding()->all();
        $this->assertInstanceOf(Collection::class, $interfaces);
    }
}
