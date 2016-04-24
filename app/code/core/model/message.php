<?php
namespace MCPI;
use \Exception;

/**
 * Core Message
 *  - Handles messaging, logging, errors, etc.
 */
class Core_Model_Message extends Core_Abstract
{

    protected static $instance = null;

    /**
     * Singleton - get instance
     */
    static function instance()
    {
        if (is_null(self::$instance))
        {
            self::$instance = new Self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    protected function __construct()
    {
    }

    // Error action
    // TODO have this use resposne and output correct error code
    function showError($error, $type='general')
    {
        echo "<b>" . ucwords($type) . "</b><br/>";

        if ($error instanceof Exception)
            echo $error->getMessage();
        elseif (is_string($error))
            echo $error;
        else
        {
            echo "<pre>";
            if (is_array($error))
                print_r($error);
            else
                var_dump($error);
            echo "</pre>";
        }
            
        die;
    }

}
