<?php
namespace MCPI;
use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;

/**
 * Main HTML View
 */
class Html_View
{

    public $_template = 'main';
    public $body = '';
    public $body_template = '';

    protected $_fresh = true;

    /**
     * Constructor
     */
    public function __construct($parameters = array())
    {
        $this->set($parameters);
    }

    /**
     * Set parameters
     */
    public function set($parameters)
    {
        foreach ($parameters as $key => $value)
        {
            $this->$key = $value;
        }
    }

    /**
     * Render body template
     */
    public function render_body()
    {
        if (!empty($this->body_template))
            return $this->render($this->body_template);
        else
            return $this->body;
    }

    /**
     * Render template
     */
    public function render($template='')
    {
        if (empty($template))
        {
            if ($this->_fresh)
                $template = $this->_template;
            else
                return 'Empty';
        }

        $data = $this->_fresh
            ? $this
            : $this->body_data
        ;


        $this->_fresh = false;
        $args = [
            'template_class_prefix' => '__MCPI_' . $template . '_',
            'cache' => DIR_TMP . 'mustache',
            'loader' => new Mustache_Loader_FilesystemLoader(DIR_TEMPLATES, array('extension' => 'tpl')),
        ];
        $partials = DIR_TEMPLATES . $template;
        if(is_dir($partials))
            $args['partials_loader'] = new Mustache_Loader_FilesystemLoader($partials, array('extension' => 'tpl'));
        $m = new Mustache_Engine($args);

        return $m->render($template, $data);
    }

}
