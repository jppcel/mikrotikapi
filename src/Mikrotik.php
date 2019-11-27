<?php
/**
 * Created by PhpStorm.
 * User: Jorge
 * Date: 17/04/2017
 * Time: 21:15
 */

namespace jjsquady\MikrotikApi;

use Exception;
use jjsquady\MikrotikApi\Core\Auth;
use jjsquady\MikrotikApi\Core\Client;
use jjsquady\MikrotikApi\Exceptions\ConnectionException;
use jjsquady\MikrotikApi\Exceptions\WrongArgumentTypeException;

/**
 * Class Mikrotik
 * @package jjsquady\MikrotikApi
 */
class Mikrotik
{

    /**
     * @var Auth
     */
    protected $auth;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var
     */
    protected $connected;

    /**
     * Mikrotik constructor.
     */
    public function __construct()
    {
        //TODO: some...
    }

    /**
     * @param $auth
     * @return Client
     */
    public function connect($auth)
    {
        if (!$auth instanceof Auth) {
            $this->auth = $this->getAuth($auth);
        } else {
            $this->auth = $auth;
        }

        try {

            return $this->getClient($this->auth);

        } catch (ConnectionException $e) {

            echo $e->getMessage();

        }

    }

    /**
     *
     */
    public function close()
    {
        $this->client->close();
    }

    /**
     * @return mixed
     */
    public function isConnected()
    {
        return $this->connected;
    }

    /**
     * @return Client
     */
    public function client()
    {
        return $this->client;
    }

    /**
     * @return Auth
     */
    public function auth()
    {
        return $this->auth;
    }

    /**
     * @param $auth Auth
     * @return Auth
     * @throws WrongArgumentTypeException
     */
    private function getAuth($auth)
    {
        if (!is_array($auth)) {
            throw new WrongArgumentTypeException("Array or Auth::class", gettype($auth));
        }

        $auth = new Auth(...$auth);
        return $auth;
    }

    /**
     * @param Auth $auth
     * @return Client
     * @throws ConnectionException
     */
    private function getClient(Auth $auth)
    {
        try {
            $this->client = new Client(...
                [$this->auth->getHost(), $this->auth->getUsername(), $this->auth->getPassword(true)]);

            $this->connected = true;

            return $this->client;

        } catch (Exception $e) {

            throw new ConnectionException($auth->getHost());

        }
    }

}