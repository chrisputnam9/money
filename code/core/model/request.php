<?php
namespace MCPI;

/**
 * Request Singleton
 */
class Core_Model_Request extends Core_Model_Abstract
{
    protected static $instance = null;

    public $url;
    public $uri;
    public $uri_segments;

    /**
     * Singleton - get instance
     */
    static public function instance()
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
        $request_uri = empty($_SERVER['REQUEST_URI']) ? "" : $_SERVER['REQUEST_URI'];
        $this->url = $request_uri;
        $this->uri = trim(explode("?", $request_uri)[0], "/");
        $this->uri_segments = explode('/', $this->uri);
    }

    /**
     * Get GET value
     */
    public function get($key)
    {
        return empty($_GET[$key])
            ? false
            : $_GET[$key]
        ;
    }

    /**
     * Get POST value
     */
    public function post($key)
    {
        return empty($_POST[$key])
            ? false
            : $_POST[$key]
        ;
    }

    /**
     * Check given index in path
     *  - compare with test if passed
     */
    public function index($i=0,$test=false)
    {
        $value = isset($this->uri_segments[$i])
            ? $this->uri_segments[$i]
            : false
        ;

        return ($test === false)
            ? $value
            : ($value == $test)
        ;
    }

    /**
     * Check request method
     */
    public function is($type)
    {
        return (strtolower($type) == strtolower($_SERVER['REQUEST_METHOD']));
    }
}
