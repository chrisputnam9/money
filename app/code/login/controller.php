<?php
namespace MCPI;

/**
 * Login Controller
 */
class Login_Controller extends Core_Controller_Abstract
{

    /**
     * Route login paths
     */
    static public function route()
    {
        $request = self::getRequest();
        if ($request->index(0,'login'))
        {
            $response = self::getResponse();
            $response->body_template = 'login';

            if ($request->is('post'))
            {
                $data = self::loginPost();
                $response->body_data = $data;
            }

            $response->finalize();
        }
    }

    /**
     * Process login request
     */
    protected static function loginPost()
    {
        $request = self::getRequest();
        if (
            empty($request->post('username'))
            or empty($request->post('password'))
        ){
            return ['error' => 'Please specify both username and password'];
        }

        if (Login_Helper::login($request->post('username'), $request->post('password')))
        {
            self::getResponse()->redirect($request->get('redirect'));
        }

        return ['error' => 'Incorrect username or password'];
    }

    /**
     * Check if logged in
     * redirect to path if not
     */
    public static function redirect($path, $privilege="*")
    {
        self::route();
        if (!Login_Helper::check($privilege))
        {
            self::getResponse()->redirect($path, array(
                'redirect' => self::getRequest()->url
            ));
        }
    }

}
Login_Controller::route();
