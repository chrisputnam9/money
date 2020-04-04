<?php
namespace MCPI;

/**
 * Login Controller
 */
class User_Controller extends Core_Controller_Abstract
{

    /**
     * Route login paths
     */
    static public function route()
    {
        $request = self::getRequest();
        $response = self::getResponse();

        if ($request->index(0,'user'))
        {
            $response->menu['user']['class'] = 'active';
            $response->main_data['show_menu'] = true;
            $response->body_template = 'user';
            $response->body_data = [
                'user' => Login_Helper::getCurrentUser(),
            ];
        }

        $response->finalize();
    }

}
User_Controller::route();
