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
        while ($dir = readdir($code_dir))
        {
            if (in_array($dir, ['.','..']) or !is_dir(DIR_CODE . DS . $dir)) continue;
            $file_path = DIR_CODE . DS . $dir . DS . 'controller.php';
            if (is_file($file_path))
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
