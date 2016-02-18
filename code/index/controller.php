<?php
namespace MCPI;

/**
 * Index Controller
 */
class Index_Controller extends Core_Controller_Abstract
{
    static public function route()
    {
        $request = self::getRequest();
        if (empty($request->uri_segments[0]))
        {
            $response = self::getResponse();
            $response->body_template = 'index';
            $response->finalize();
        }
    }
}
Index_Controller::route();
