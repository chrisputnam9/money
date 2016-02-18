<?php
namespace MCPI;

/**
 * Abstract Controller
 */
class Core_Controller_Abstract
{
    /**
     * Return request singleton
     */
    static public function getRequest()
    {
        return Core_Model_Request::instance();
    }

    /**
     * Return response singleton
     */
    static public function getResponse()
    {
        return Core_Model_Response::instance();
    }

    /**
     * Return session singleton
     */
    static public function getSession()
    {
        return Core_Model_Session::instance();
    }
}
