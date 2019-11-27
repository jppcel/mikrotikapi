<?php
/**
 * Created by PhpStorm.
 * User: Jorge
 * Date: 18/04/2017
 * Time: 03:48
 */

namespace jjsquady\MikrotikApi\Contracts;


interface ClientContract
{
    function connect();
    function isConnected();
    function api();
    function write($command, $params = true);
    function read($pretty = true);
}