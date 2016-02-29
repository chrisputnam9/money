<?php
namespace MCPI;

/**
 * Transaction Controller
 */
class Transaction_Controller extends Core_Controller_Abstract
{
    static public function route()
    {
        $request = self::getRequest();
        if ($request->index(0,'transaction'))
        {
            $response = self::getResponse();

            if ($request->index(1,'image'))
                $response->body_template = 'transaction_image';
            if ($request->index(1,'form'))
                $response->body_template = 'transaction_form';

            $response->finalize();
        }
    }
}
Transaction_Controller::route();
