<?php
namespace MCPI;

abstract class Core_Shell_Abstract extends Core_Abstract
{
    /**
     * Constructor
     */
    public function __construct($args)
    {
        $this->args = $args;
    }

    /**
     * Main Run function
     */
    public abstract function run();

    /**
     * Locate shell model based on args
     *  - instantiate and run OR
     *  - show error if not found
     */
    public static function route($args)
    {
        array_shift($args);
        $shortname = trim(array_shift($args));

        $code_dir = opendir(DIR_CODE);
        while ($dir = readdir($code_dir))
        {
            if (in_array($dir, ['.','..']) or !is_dir(DIR_CODE . DS . $dir)) continue;
            $dir = ucwords(trim($dir));
            $shortname = ucwords(trim($shortname));
            $class_name = "MCPI\\" . $dir . '_Shell_' . $shortname;
            
            if (class_exists($class_name))
            {
                $instance = new $class_name($args);
                $instance->run();
                exit;
            }
        }

        self::error('Invalid command, class not found');
    }
}
