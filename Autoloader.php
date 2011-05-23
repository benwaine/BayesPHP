<?php

namespace BayesPHP;

class Autoloader
{

    public static function registerAutoload()
    {
        spl_autoload_register(__NAMESPACE__ . '\Autoloader::load');
    }

    public static function load($class)
    {

        $paths = explode("\\", $class, 2);

        if (isset($paths[1]))
        {
            $file = $paths[1] . '.php';
            $fullPath =  __dir__ . '/' . $file;

            if (\file_exists($fullPath))
            {
                require_once __dir__ . '/' . $file;
            } else
            {
                return false;
            }
        } else
        {
            return false;
        }
    }

}

?>
