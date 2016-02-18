<?php
namespace MCPI;

/**
 * Request Singleton
 */
class Core_Model_Request
{
    protected static $instance = null;

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
        $this->uri = trim(explode("?", $request_uri)[0], "/");
        $this->uri_segments = explode('/', $this->uri);

        $this->get = $_GET;
        $this->post = $_POST;
    }
}
