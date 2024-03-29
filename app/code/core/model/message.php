<?php
namespace MCPI;
use \Exception;

/**
 * Core Message
 *  - Handles messaging, logging, errors, etc.
 */
class Core_Model_Message extends Core_Abstract
{
    public $environment = "html";

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
        if (defined('APP_ENVIRONMENT'))
        {
            $this->environment = APP_ENVIRONMENT;
        }
    }

    /**
     * Log output
     */
    public function _log($message, $header=false)
    {
        $log_method = "log_" . $this->environment;

        $lines = [];
        if ($header)
        {
            $lines[]= "================================";
            $lines[]= strtoupper($header) . ":";
            $lines[]= "--------------------------------";
        }

        if(is_array($message))
        {
            $message = print_r($message, true);
        }
        else if (!is_string($message))
        {
            ob_start();
            var_dump($message);
            $message = ob_get_clean();
        }

        $lines= array_merge($lines, explode("\n", $message));

        $output = "";
        $timestamp = date('Y-m-d H:i:s');
        foreach ($lines as $line)
        {
            $output.= $timestamp . " | " . $line . "\n";
        }

        $this->$log_method($output);
    }
        // HTML
        protected function log_html($output)
        {
            echo "<pre>";
            echo $output;
            echo "</pre>";
        }
        // CLI
        protected function log_cli($output)
        {
            echo $output;
        }
		// JSON - log to file
        protected function log_json($output)
        {
            file_put_contents(DIR_TMP . DS . 'log' . DS . 'message.log', $output, FILE_APPEND);
        }

    // Error action
    function _error($error, $die=true)
    {
        if (!$error instanceof Exception)
            $error = new Exception($error);

        $this->log($error->getTraceAsString(), "ERROR: " . $error->getMessage());
            
        if ($die) die;
    }

}
