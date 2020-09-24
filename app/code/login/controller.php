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
            $response->body_data = ['remember' => 1];

            // Login form submitted?
            if ($request->is('post'))
            {
                $data = self::loginPost();
                $response->body_data = $data;
            }

            // Already logged in?
            if (Login_Helper::check())
            {
                $response->redirect($request->get('redirect') ?? "/");
            }

            $response->finalize();
        }

        if ($request->index(0,'logout'))
        {
            Login_Helper::logout();
            self::getResponse()->redirect('/login');
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
            return [
                'error' => 'Please specify both username and password',
                'username' => $request->post('username'),
                'password' => $request->post('password'),
                'remember' => $request->post('remember'),
            ];
        }

        if (Login_Helper::login($request->post('username'), $request->post('password'), 'hash', $request->post('remember')))
        {
            self::getResponse()->redirect($request->get('redirect') ?? "/");
        }

        return ['error' => 'Incorrect username or password'];
    }

    /**
     * Check if logged in
     * redirect to path if not
     */
    public static function redirect($path='/login', $privilege='*')
    {
        $request = self::getRequest();
        $is_api = $request->api_request;
        $authorized = false;
        if ($is_api)
        {
            $authorized = Login_Helper::login(
                @$request->body['authentication']['username'],
                @$request->body['authentication']['api_key'],
                'api_key_hash'
            );
        }

        self::route();
        if (!$authorized and !Login_Helper::check($privilege))
        {
            if ($is_api or $request->post('ajax') or $_SERVER['REQUEST_METHOD'] == 'OPTIONS')
            {
                die(json_encode(['error' => 'Not Authorized']));
            }

            self::getResponse()->redirect($path, array(
                'redirect' => $request->url
            ));
        }

        if (!$is_api)
        {
            Login_Helper::freshenSession();
        }
    }

}
Login_Controller::route();
