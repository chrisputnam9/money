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
            $response->body_data = [
                'transactions' => array_values(Transaction_Model::getListing()),
            ];
            $response->finalize();
        }
    }
}
Index_Controller::route();
