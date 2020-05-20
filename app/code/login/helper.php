<?php
namespace MCPI;

/**
 * Login Helper Class
 */
class Login_Helper extends Core_Helper_Abstract
{

    protected static $user_session_data = null;
    protected static $user_session_file =  SESSION_FILE;

    /**
     * Try to login with given username/password
     */
    public static function login($username, $password, $hash='hash', $remember)
    {
        require_once DIR_CONFIG . 'users.php';
        foreach ($_USERS as $id => $user)
        {
            if ($username == $user['name'])
            {
                if (self::verify($password, $user[$hash]))
                {
                    self::saveUserSession(
                        $username,
                        [
                            'remember' => $remember,
                        ]
                    );
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Keep session active
     */
    public static function freshenSession()
    {
        $session_data = self::getUserSessionData();
        $token = $session_data['login_token'];
        if (isset($session_data['sessions'][$token]))
        {
            $expire = $session_data['sessions'][$token];
            /*
                4 Days:
                4 * 24 * 60 * 60 = 345600
            */
            if ($expire - time() < 345600) // If expiring in less than 4 days, extend
            {
                // todo remove current token and add new
            }
        }
    }

    /**
     * Log the current user out - remove current token & cookies
     */
    public static function logout()
    {
        $session_data = self::getUserSessionData();
        $token = $session_data['login_token'];

        setcookie('money_token', "", 1, '/', COOKIE_DOMAIN, true);
        setcookie('money_username', "", 1, '/', COOKIE_DOMAIN, true);
        setcookie('money_remember', "", 1, '/', COOKIE_DOMAIN, true);

        $all_sessions = self::getAllSessionData();
        unset($all_sessions[$username][$token]);
        self::saveAllSessionData($all_sessions);
    }

    /**
     * Check if session user has privilege level
     *  * - any privilege, just checks logged_in
     */
    public static function check($privilege="*")
    {
        $session_data = self::getUserSessionData();

        $token = $session_data['login_token'];
        if (isset($session_data['sessions'][$token]))
        {
            $expire = $session_data['sessions'][$token];
            if ($expire > time())
            {
                if ($privilege == "*")
                {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Get all session tokens, pre-filtering expired data
     */
    public static function getAllSessionData()
    {
        $all_sessions_json = file_get_contents(self::$user_session_file);
        $all_sessions = json_decode($all_sessions_json, true);

        $clean_sessions = [];

        if (is_array($all_sessions))
        {
            foreach ($all_sessions as $username => $sessions)
            {

                // Clean up data
                $sessions = array_filter($sessions, function ($_expire) {
                    return ($_expire >= time());
                });

                $clean_sessions[$username] = $sessions;

            }
        }

        return $clean_sessions;
    }

    /**
     * Save all session tokens
     */
    public static function saveAllSessionData($all_sessions)
    {
        $all_sessions_json = json_encode($all_sessions, JSON_PRETTY_PRINT);

        $success = file_put_contents(self::$user_session_file, $all_sessions_json);
        if (!$success)
        {
            throw new \Exception("Failed to save updated session data to '".self::$user_session_file."'");
        }

        return $success;
    }

    /**
     * Get All User Session Data
     */
    public static function getUserSessionData($username=null)
    {
        if (is_null(self::$user_session_data))
        {
            $all_sessions = self::getAllSessionData();

            if (is_null($username))
            {
                $username = empty($_COOKIE['money_username']) ? '' : $_COOKIE['money_username'];
            }
            $login_token = empty($_COOKIE['money_token']) ? '' : $_COOKIE['money_token'];
            $remember = !empty($_COOKIE['money_remember']);

            $user_sessions = [];
            if (!empty($all_sessions[$username]) and is_array($all_sessions[$username]))
            {
                $user_sessions = $all_sessions[$username];
            }

            self::$user_session_data = [
                'username' => $username,
                'login_token' => $login_token,
                'remember' => $remember,
                'sessions' => $user_sessions,
            ];
        }

        return self::$user_session_data;
    }

    /**
     * Save user session
     */
    public static function saveUserSession($username, $new_data, $save_fresh_token=true)
    {
        $user_session_data = self::getUserSessionData($username);
        $user_session_data = array_merge($user_session_data, $new_data);

        $remember = empty($user_session_data['remember']) ? "" : $user_session_data['remember'];

        $now = time();
        $expire = $remember
            ? ($now + 432000) // 5 days in the future
            : ($now + 3600); // 1 Hour

        if ($save_fresh_token)
        {
            if (empty($username))
            {
                throw new \Exception('Missing username, this is odd...');
            }

            $new_login_token = password_hash($username . $now . random_bytes(20), PASSWORD_DEFAULT);


            $cookie_expire = $remember ? $expire : 0; // end of session of not set to remember

            $user_sessions = $user_session_data['sessions'];

            $user_sessions[$new_login_token] = $expire;

            // Store login token in json
            $all_sessions = self::getAllSessionData();
            $all_sessions[$username] = $user_sessions;
            self::saveAllSessionData($all_sessions);

        }
        
        // Set Cookies
        setcookie('money_token', $new_login_token, $cookie_expire, '/', COOKIE_DOMAIN, true);
        setcookie('money_username', $username, $cookie_expire, '/', COOKIE_DOMAIN, true);
        if ($remember)
        {
            setcookie('money_remember', '1', $cookie_expire, '/', COOKIE_DOMAIN, true);
        }

    }

    /**
     * Get current user info
     */
    public static function getCurrentUser()
    {

        $user_session_data = self::getUserSessionData();

        $username = empty($user_session_data['username']) ? false : $user_session_data['username'];

        if (empty($username))
        {
            die('Something is wrong with your login');
        }

        $api_key = "";

        require_once DIR_CONFIG . 'users.php';
        foreach ($_USERS as $id => $user)
        {
            if ($username == $user['name'])
            {
                $api_key = $user['api_key'];
            }
        }

        $data = [
            'name' => $username,
            'api_key' => $api_key,
        ];

        return $data;
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
