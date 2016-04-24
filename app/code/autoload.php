<?php
namespace MCPI;

// Constants
require_once __DIR__ . DIRECTORY_SEPARATOR . "constants.php";

// Config
require_once DIR_CONFIG . "general.php";

// Composer Autoloader
require_once DIR_ROOT . DS . "vendor" . DS . "autoload.php";

/**
 * Main autoloader
 */
class Autoload
{
    static public function load($class_name)
    {
        $class_name = preg_replace('/^\\\\?MCPI\\\\/', '', $class_name);
        $file_name = __DIR__ . DS . strtolower(str_replace('_', DS, $class_name)) . ".php";
        if (file_exists($file_name))
        {
            require_once $file_name;
            return true;
        }
        else
            return false;
    }
}
spl_autoload_register('\MCPI\Autoload::load');
