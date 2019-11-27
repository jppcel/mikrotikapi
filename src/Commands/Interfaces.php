<?php
/**
 * Created by PhpStorm.
 * User: Jorge
 * Date: 18/04/2017
 * Time: 00:17
 */

namespace jjsquady\MikrotikApi\Commands;

use jjsquady\MikrotikApi\Entity\Bonding;
use jjsquady\MikrotikApi\Entity\Ethernet;
use jjsquady\MikrotikApi\Entity\InterfaceEntity;

/**
 * Class InterfaceCommand
 * @package MiKontrol\Http\MikrotikApi\Commands
 */
class Interfaces extends Command
{

    /**
     * @var array
     */
    protected $commands = [
        'interface' => InterfaceEntity::class,
        'ethernet'  => Ethernet::class,
        'bonding'   => Bonding::class
    ];

    /**
     * @var array
     */
    protected $commandsAlias = [
        'interface' => ''
    ];

    /**
     * @var string
     */
    protected $base_command = '/interface';

    public function ethernet()
    {
        return $this->__call("ethernet", null);
    }

    public function interface()
    {
        return $this->__call("interface", null);
    }

    public function bonding()
    {
        return $this->__call("bonding", null);
    }

}