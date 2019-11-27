<?php
/**
 * Created by PhpStorm.
 * User: Jorge
 * Date: 17/04/2017
 * Time: 22:54
 */

namespace jjsquady\MikrotikApi\Entity;

use jjsquady\MikrotikApi\Core\QueryBuilder;
use ReflectionClass;

/**
 * Class Entity
 * @package MiKontrol\Http\MikrotikApi\Commands\Entity
 */
abstract class Entity
{

    protected $query;

    protected $directory;

    /**
     * @var
     */
    protected $fillable;

    public function __construct(array $params, $query = null)
    {
        if ($query instanceof QueryBuilder) {
            $this->_query = $query;
        }

        if (is_null($this->directory)) {
            $this->directory = (new ReflectionClass($this))->getShortName();
        }

        $check = is_array($this->fillable);

        foreach ($params as $param => $value) {
            if ($check) {
                in_array($param, $this->fillable) ? $this->{$param} = $value : null;
            } else {
                $this->{$param} = $value;
            }
        }
    }

    public function toArray()
    {
        return get_object_vars($this);
    }

    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return json_encode($this);
    }

    /**
     * @param $property
     * @return null
     */
    public function __get($property)
    {
        $propertyClean = $this->convertToDashes($property);

        if (isset($this->fillable)) {
            return $this->getFillableProperty($propertyClean);
        }

        return property_exists($this, $propertyClean) ? $this->{$propertyClean} : null;
    }

    /**
     * If $fillable array its set, then looks into this array for properties
     * @param $property
     * @return null
     */
    private function getFillableProperty($property)
    {
        return in_array($property, $this->fillable) ?
            property_exists($this, $property) ?
                $this->{$property} :
                null :
            null;
    }

    /**
     * Convertes a camelCase property to hyphened-case (myProp -> my-prop)
     * @param $property
     * @return string
     */
    private function convertToDashes($property)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $property, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('-', $ret);
    }
}