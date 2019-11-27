<?php
/**
 * Created by PhpStorm.
 * User: Jorge
 * Date: 19/04/2017
 * Time: 00:20
 */

namespace jjsquady\MikrotikApi\Contracts;


/**
 * Interface AuthContract
 * @package jjsquady\MikrotikApi\Contracts
 */
interface AuthContract
{
    /**
     * @return mixed
     */
    function getHost();

    /**
     * @return mixed
     */
    function getUsername();

    /**
     * @param bool $plainText
     * @return mixed
     */
    function getPassword($plainText = false);
}