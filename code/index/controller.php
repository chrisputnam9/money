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
        if (empty($request->index()))
        {
            $response = self::getResponse();
            $response->body_template = 'index';
            $response->finalize();
        }
    }
}
Index_Controller::route();
