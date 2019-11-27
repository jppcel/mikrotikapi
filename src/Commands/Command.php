<?php
/**
 * Created by PhpStorm.
 * User: Jorge
 * Date: 17/04/2017
 * Time: 21:31
 */

namespace jjsquady\MikrotikApi\Commands;

use jjsquady\MikrotikApi\Contracts\CommandContract as CommandInterface;
use jjsquady\MikrotikApi\Core\Client;
use jjsquady\MikrotikApi\Core\QueryBuilder;
use jjsquady\MikrotikApi\Core\Request;
use jjsquady\MikrotikApi\Entity\Entity;
use jjsquady\MikrotikApi\Entity\GeneticEntity;
use jjsquady\MikrotikApi\Exceptions\CommandException;
use jjsquady\MikrotikApi\Exceptions\InvalidCommandException;
use jjsquady\MikrotikApi\Support\EntityUtils;
use Exception;

/**
 * Class Command
 * @package MiKontrol\Http\MikrotikApi\Commands
 */
abstract class Command implements CommandInterface
{
    use EntityUtils;

    /**
     * @var mixed
     */
    private $_response;


    /**
     * @var boolean
     */
    private $_async;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Entity
     */
    protected $entityClass;

    /**
     * @var string
     */
    protected $base_command;

    /**
     * @var \PEAR2\Net\RouterOS\Request
     */
    protected $request;

    /**
     * @var array
     */
    protected $commands = [];

    /**
     * @var array
     */
    protected $commandsAlias = [];

    /**
     * @var QueryBuilder
     */
    protected $query;


    /**
     * Command constructor.
     * @param Client $client
     * @throws CommandException
     */
    public function __construct(Client $client)
    {
        if (empty($this->base_command) || is_null($this->base_command)) {
            throw new CommandException("Missing \$base_command property. Set the \$base_command property with a valid command.");
        }

        $this->client  = $client;
        $this->request = new Request($this->base_command);
    }


    /**
     * @param Client $client
     * @return static
     */
    public static function bind(Client $client)
    {
        $instance          = new static($client);
        $instance->client  = $client;
        return $instance;
    }


    /**
     * @param $command
     * @param null $args
     * @return $this
     */
    public function executeCommand($command, $args = null)
    {
        $this->request = new Request($command);

        $this->processCommandArgs($args);

        $this->_response = $this->processCommand();

        return $this;

    }

    /**
     * @param null $entityClass
     * @return \jjsquady\MikrotikApi\Core\Collection
     */
    public function get($entityClass = null)
    {

        $this->entityClass = (is_null($entityClass)) ?
            GeneticEntity::class :
            $entityClass;

        return $this->convertArrayToEntities($this->_response, $this->entityClass);
    }

    /**
     * @return mixed
     */
    public function getBaseCommand()
    {
        return $this->base_command;
    }

    /**
     * @param array $args
     * @return string
     */
    protected function buildCommandPath(array $args)
    {
        if (!is_array($args)) {
            //TODO: throw exception
        }

        if (empty(array_last($args))) {
            return implode('', $args);
        }

        return implode("/", $args);
    }

    /**
     * @param $name
     * @param $arguments
     * @return QueryBuilder
     * @throws InvalidCommandException
     */
    public function __call($name, $arguments)
    {
        if (array_key_exists($name, $this->commands)) {
            $command = array_key_exists($name, $this->commandsAlias) ? $this->commandsAlias[$name] : $name;
            $fullCommand = $this->buildCommandPath([$this->base_command, $command]);
            return $this->buildNewQuery($this->commands[$name], $fullCommand);
        }

        throw new InvalidCommandException($name);
    }

    /**
     * @param $entityClass
     * @param $command
     * @return QueryBuilder
     */
    protected function buildNewQuery($entityClass, $command)
    {
        $this->query = new QueryBuilder($entityClass, $command, $this->client);
        return $this->query;
    }

    /**
     * @return \PEAR2\Net\RouterOS\ResponseCollection
     */
    private function processCommand()
    {
        return $this->client->sendSync($this->request);
    }

    /**
     * @param $args
     */
    private function processCommandArgs($args)
    {
        if (!is_array($args)) {
            return;
        }

        array_map(function ($arg) {
            $this->request->setArgument($arg);
        }, $args);
    }

}