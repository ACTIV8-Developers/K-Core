<?php
namespace Core\Routing;

/**
 * Class ExecutableFactory
 * @package Core\Routing
 */
class ExecutableFactory
{
    /**
     * @param $class
     * @param $function
     * @param $params
     * @return Executable
     */
    public static function make($class, $function, $params = [])
    {
        return new Executable($class, $function, $params);
    }
}