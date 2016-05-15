<?php
namespace MCPI;

/**
 * Core Abstract
 *  - Contains methods useful in all classes
 */
class Core_Abstract
{
    /**
     * Return message singleton
     */
    static function getMessage()
    {
        return Core_Model_Message::instance();
    }

        /* Message Helpers */
        /*******************/

        // show an error message
        static function error($message, $type=null)
        {
            self::getMessage()->showError($message, $type);
        }

    /**
     * Return request singleton
     */
    static function getRequest()
    {
        return Core_Model_Request::instance();
    }

    /**
     * Return response singleton
     */
    static function getResponse()
    {
        return Core_Model_Response::instance();
    }

    /**
     * Return session singleton
     */
    static function getSession($key=null)
    {
        return Core_Model_Session::instance($key);
    }
}