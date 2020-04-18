<?php
namespace MCPI;

/**
 * Login Helper Class
 */
class Login_Helper extends Core_Helper_Abstract
{

    protected static $user_session_data = null;
    protected static $user_session_file =  '/home/chris/web-data/user_session_money'.(IS_DEVELOPMENT ? '-dev' : '').'.json';

    /**
     * Try to login with given username/password
     */
    public static function login($username, $password, $hash='hash')
    {
        require_once DIR_CONFIG . 'users.php';
        foreach ($_USERS as $id => $user)
        {
            if ($username == $user['name'])
            {
                if (self::verify($password, $user[$hash]))
                {
                    $session = self::getSession(self::SESSION_KEY);
                    $session->set('status', 'logged_in');
                    $session->set('user', $username);
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Check if session user has privilege level
     *  * - any privilege, just checks logged_in
     */
    public static function check($privilege="*")
    {
        $session_data = self::getUserSessionData();
        die;
        echo "<pre>";
        var_dump($session_data);
        echo "</pre>";
        die;

        if (!$session->is('status', 'logged_in'))
            return false;

        if ($privilege == "*")
            return true;
    }

    /**
     * Get All User Session Data
     */
    public static function getUserSessionData()
    {
        if (is_null(self::$user_session_data))
        {
            $user_session_json = file_get_contents(self::$user_session_file);
            $sessions = json_decode($user_session_json, true);

            if (!is_array($sessions))
            {
                $sessions = [];
            }

            $username = empty($_COOKIE['cmp_goals_username']) ? '' : $_COOKIE['cmp_goals_username'];
            $login_token = empty($_COOKIE['cmp_goals_token']) ? '' : $_COOKIE['cmp_goals_token'];
            $remember = empty($_COOKIE['cmp_goals_remember']) ? false : true;

            self::$user_session_data = [
                'username' => $username,
                'login_token' => $login_token,
                'remember' => $remember,
                'sessions' => $sessions,
            ];
        }

        return self::$user_session_data;
    }

    /**
     * Save user session
     */
    public static function saveUserSession($data)
    {
    }

    /**
     * Get current user info
     */
    public static function getCurrentUser()
    {

        $username = $session->get('user');
        $api_key = "";

        require_once DIR_CONFIG . 'users.php';
        foreach ($_USERS as $id => $user)
        {
            if ($username == $user['name'])
            {
                $api_key = $user['api_key'];
            }
        }

        return [
            'name' => $username,
            'api_key' => $api_key,
        ];

    }

    public static function hash($password)
    {
       return password_hash($password, PASSWORD_DEFAULT);
    }

    public static function verify($password, $hash)
    {
        return password_verify($password, $hash);
    }

}
