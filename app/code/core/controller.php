<?php
namespace MCPI;

/**
 * Core Controller
 *  - Routes the entire app
 */
class Core_Controller extends Core_Controller_Abstract
{
    static public function route()
    {
        Login_Controller::redirect('/login');
        $code_dir = opendir(DIR_CODE);
        while ($file = readdir($code_dir))
        {
            $file_path = DIR_CODE . DS . $file . DS . 'controller.php';
            if (is_file(DIR_CODE . DS . $file . DS . 'controller.php'))
            {
                require_once($file_path);
            }
        }
        $response = self::getResponse();
        $response->body = 'Unable to find valid route';
        $response->setCode('404');
        $response->finalize();
    }
}
