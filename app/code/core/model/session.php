<?php
namespace MCPI;

/**
 * Session Singleton
 */
class Core_Model_Session extends Core_Model_Abstract
{
    const DEFAULT_KEY = 'MCPI_Default';

    protected static $instance = [];

    protected $key = null;

    /**
     * Singleton - get instance
     */
    static public function instance($key=self::DEFAULT_KEY)
    {
        if (!isset(self::$instance[$key]))
        {
            self::$instance[$key] = new Self($key);
        }
        return self::$instance[$key];
    }

    /**
     * Constructor
     */
    protected function __construct($key)
    {
        session_start();
        if (!isset($_SESSION[$key]))
            $_SESSION[$key] = [];

        $this->key = $key;
    }

    /**
     * Set value
     */
    public function set($key, $value)
    {
        $_SESSION[$this->key][$key] = $value;
    }

    /**
     * Get value
     */
    public function get($key)
    {
        return empty($_SESSION[$this->key][$key])
            ? false
            : $_SESSION[$this->key][$key]
        ;
    }

    /**
     * Check value against test
     */
    public function is($key, $test, $strict=true)
    {
        $value = $this->get($key);
        return $strict
            ? ($value === $test)
            : ($value == $test)
        ;
    }

}
