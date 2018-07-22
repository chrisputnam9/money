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

    public $is_development = IS_DEVELOPMENT;

    public $menu = [];

    public $body = '';
    public $body_data = [];
    public $body_template = '';

    protected $_fresh = true;

    /**
     * Constructor
     */
    public function __construct($parameters = [])
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
            if (($key == 'main_data'))
            {
                foreach ($value as $_key => $_value)
                {
                    $this->$_key = $_value;
                }
            }
            else
            {
                $this->$key = $value;
            }
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
            {
                $this->menu = array_values($this->menu);
                $template = $this->_template;
            }
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
            'cache' => DIR_TMP . 'tpl',
            'loader' => new Mustache_Loader_FilesystemLoader(DIR_TEMPLATES, array('extension' => 'tpl')),
        ];
        $partials = DIR_TEMPLATES . $template;
        if(is_dir($partials))
            $args['partials_loader'] = new Mustache_Loader_FilesystemLoader($partials, array('extension' => 'tpl'));
        $m = new Mustache_Engine($args);

        return $m->render($template, $data);
    }

    /**
     * Render style_tag
     */
    public function style_tag()
    {
        return function ($file)
        {
            $uri = $this->_asset_info($file, 'css');
            if (empty($uri))
            {
                return '<b>STYLESHEET NOT FOUND: ' . $file . '</b><br>';
            }

            return '<link rel="stylesheet" href="' . $uri . '" >';
        };
    }

    /**
     * Render script tag
     */
    public function script_tag()
    {
        return function ($file)
        {
            $uri = $this->_asset_info($file, 'js');
            if (empty($uri))
            {
                return '<b>SCRIPT NOT FOUND: ' . $file . '</b><br>';
            }

            return '<script src="' . $uri . '"></script>';
        };
    }

    /**
     * Get asset info
     */
    protected function _asset_info ($file, $type)
    {
        // Check zones in order, return as soon as file is found
        foreach (['core', 'vendor'] as $zone) 
        {
            $path = "/assets/" . $zone . "/" . $type . "/" . $file;
            $filepath = DIR_PUBLIC . $path;
            if (is_file($filepath))
            {
                return $path . "?v=" . filemtime($filepath);
            }
        }

        return false;
    }

}
