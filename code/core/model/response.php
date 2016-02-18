<?php
namespace MCPI;

/**
 * Response Singleton
 */
class Core_Model_Response
{
    protected static $instance = null;

    public $format = 'html';

    public $title = 'Money.cpi';
    public $body = '';
    public $body_template = '';
    public $body_data = array();

    protected $header = '200 OK';

    protected $codes_allowed = array(
        '200' => '200 OK',
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
            $this->header = $this->codes_allowed[$code];
        }
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
            header("HTTP/1.0 " . $this->header);
            echo $view->render();
        }
        else
        {
            throw New \Exception('Invalid Format - ' . $this->format);
        }
        exit;
    }
}
