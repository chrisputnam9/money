<?php
namespace MCPI;

/**
 * Request Singleton
 */
class Core_Model_Request extends Core_Model_Abstract
{
    protected static $instance = null;

    public $headers;

    public $url;
    public $uri;
    public $uri_segments;

    public $api_request = false;
    public $body = null;

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
        $this->headers = getallheaders();
        $this->url = $request_uri;
        $this->uri = trim(explode("?", $request_uri)[0], "/");
        $this->uri_segments = explode('/', $this->uri);

        $this->api_request = (isset($this->headers['Content-Type']) and $this->headers['Content-Type'] == 'application/json');
        if ($this->api_request)
        {
            $body = file_get_contents('php://input');
            $this->body = json_decode($body, true);
        }
    }

    /**
     * Get FILES value
     */
    public function file($key=false)
    {
        if ($key === false)
            return (!empty($_FILES));

        return empty($_FILES[$key])
            ? false
            : $_FILES[$key]
        ;
    }

    /**
     * Get GET value
     */
    public function get($key=false, $sanitize='string')
    {
        $sanitize = 'FILTER_SANITIZE_' . strtoupper($sanitize);
        if (!defined($sanitize))
            die('invalid sanitze option: ' . $sanitize);

        if ($key === false)
            return (!empty($_GET));

        return empty($_GET[$key])
            ? null
            : filter_var($_GET[$key], constant($sanitize))
        ;
    }

    /**
     * Get POST value
     */
    public function post($key=false, $sanitize='string')
    {
        $sanitize = 'FILTER_SANITIZE_' . strtoupper($sanitize);
        if (!defined($sanitize))
            die('invalid sanitze option: ' . $sanitize);

        if ($key === false)
            return (!empty($_POST));

        return empty($_POST[$key])
            ? null
            : filter_var($_POST[$key], constant($sanitize))
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

    /**
     * Generate URL based on current data combined with changes
     */
    public function url($base=null, $query_args=[])
    {
        if (is_null($base)) $base = $this->uri_segments;
        if (is_array($base)) $base = join("/", $base);
        $base = trim($base, " \t\n\r\0\x0B/");

        $query_args = array_merge($_GET, $query_args);
        $query = http_build_query($query_args);

        return '/' . $base . '?' . $query;
    }
}
