<?php
namespace MCPI;

/**
 * Response Singleton
 */
class Core_Model_Response extends Core_Model_Abstract
{
    protected static $instance = null;

    public $format = 'html';

    public $title = 'Money.cpi';
    public $body = '';
    public $body_template = '';
    public $body_data = array();

    protected $status = '200 OK';

    protected $codes_allowed = array(
        '200' => '200 OK',
        '301' => 'Moved Permanently',
        '302' => 'Found',
        '404' => '404 Not Found',
    );

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
    }

    /**
     * Set Code - based on available codes
     */
    public function setCode($code)
    {
        if (!empty($this->codes_allowed[$code]))
        {
            $this->status = $this->codes_allowed[$code];
        }
        return $this;
    }

    /**
     * Redirect
     */
    public function redirect($url='/', $data=[], $code='302')
    {
        if (!is_array($data))
        {
            $code = $data;
            $data = array();
        }

        $url_parts = parse_url($url);
        $url_path = empty($url_parts['path']) ? '' : $url_parts['path'];
        $url_data = [];
        if (!empty($url_parts['query']))
            parse_str($url_parts['query'], $url_data);
        $url_data = array_merge($url_data, $data);
        $query_string = http_build_query($url_data);
        $url = $url_path . (empty($query_string) ? '' : '?' . $query_string);
        $this->setCode($code);

        if ($this->getRequest()->post('ajax'))
        {
            header('Content-Type: application/json');
            die(json_encode([
                'location' => $url
            ]));
        }


        header("HTTP/1.0 " . $this->status);
        header("Location: " . $url);
        exit;
    }

    /**
     * Finalize - output and exit
     */
    public function finalize()
    {
        $view_class = "MCPI\\" . ucwords(strtolower($this->format)) . "_View";
        if (class_exists($view_class))
        {
            $view = new $view_class(array(
                'title' => $this->title,
                'body' => $this->body,
                'body_template' => $this->body_template,
                'body_data' => $this->body_data,
            ));
            header("HTTP/1.0 " . $this->status);
            echo $view->render();
        }
        else
        {
            throw New \Exception('Invalid Format - ' . $this->format);
        }
        exit;
    }
}
