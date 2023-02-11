<?php
namespace MCPI;

class Transaction_Cron extends Core_Cron_Abstract
{
    /**
     * Run every tick
     */
    protected function minute()
    {
		// echo " -- Running Transaction_Cron::minute()\n";
        $table = Transaction_Recurrance_Model::$table;
        $today = $this->now->format('Y-m-d') . ' 00:00:00';

        $sql = 'SELECT t.*'
            . ' FROM ' . $table . ' t'
            . ' WHERE t.date_start <= "' . $today . '"'
            . '   AND (t.date_end IS NULL OR t.date_end >= "' . $today . '")'
        ;

        $recurrances = Transaction_Recurrance_Model::get($sql);
		// echo " --- ".count($recurrances)." recurrances found to be caught up\n";

        foreach ($recurrances as $recurrance)
        {
			// echo " ---- cactching up recurrance ID ${recurrance['main_transaction_id']}\n";
            Transaction_Recurrance_Controller::catchup($recurrance);
        }
    }
}
