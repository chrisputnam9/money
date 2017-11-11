<?php
namespace MCPI;

use DateTime;

class Core_Shell_Cron extends Core_Shell_Abstract
{
    public function run()
    {
        $now = new DateTime();
        $start = new DateTime("@0");
        $timediff = $now->diff($start);

        $code_dir = opendir(DIR_CODE);
        while ($dir = readdir($code_dir))
        {
            if (in_array($dir, ['.','..']) or !is_dir(DIR_CODE . DS . $dir)) continue;

            $dir = ucwords(trim($dir));
            $class_name = "MCPI\\" . $dir . '_Cron';

            if (class_exists($class_name))
            {
                $instance = new $class_name($now, $timediff);
                $instance->run();
            }

        }
    }
}
