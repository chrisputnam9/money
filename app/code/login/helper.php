<?php
namespace MCPI;

/**
 * Login Helper Class
 */
class Login_Helper extends Core_Helper_Abstract
{

    const SESSION_KEY = 'MCPI_Login';

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
        $session = self::getSession(self::SESSION_KEY);

        if (!$session->is('status', 'logged_in'))
            return false;

        if ($privilege == "*")
            return true;
    }

    /**
     * Get current user info
     */
    public static function getCurrentUser()
    {
        $session = self::getSession(self::SESSION_KEY);

        if (!$session->is('status', 'logged_in'))
            return false;

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
