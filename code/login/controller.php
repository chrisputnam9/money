<?php
namespace MCPI;

/**
 * Login Controller
 */
class Login_Controller extends Core_Controller_Abstract
{
    /**
     * Check if logged in
     * redirect to path if not
     */
    public function redirect($path, $privilege="*")
    {
        if (!self::check($privilege))
        {
            self::getResponse()->redirect($path);
        }
    }

    /**
     * Check if session user has privilege level
     *  * - any privilege, just checks logged_in
     */
    public function check($privilege="*")
    {
        $session = self::getSession();

        if (!$session->logged_in)
            return false;

        if ($privilege == "*")
            return true;
    }
}

