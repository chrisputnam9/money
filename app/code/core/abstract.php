<?php
namespace MCPI;

/**
 * Core Abstract
 *  - Contains methods useful in all classes
 */
class Core_Abstract
{

    /**
     * Return budget menu singleton
     */
    static function getBudgetMenu()
    {
        return Budget_Model_Menu::instance();
    }

    /**
     * Return date filter singleton
     */
    static function getDateFilter()
    {
        return Core_Model_Datefilter::instance();
    }

    /**
     * Return message singleton
     */
    static function getMessage()
    {
        return Core_Model_Message::instance();
    }

        /* Message Helpers */
        /*******************/

        // show a message
        static function log($message, $type=false)
        {
            self::getMessage()->_log($message, $type);
        }

        // show an error message
        static function error($message, $die=false)
        {
            self::getMessage()->_error($message, $die);
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
