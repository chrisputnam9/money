<?php
namespace MCPI;

class Transaction_Cron extends Core_Cron_Abstract
{
    /**
     * Run every tick
     */
    protected function minute()
    {
        $table = Transaction_Recurrance_Model::$table;
        $today = $this->now->format('Y-m-d') . ' 00:00:00';

        $sql = 'SELECT t.*'
            . ' FROM ' . $table . ' t'
            . ' WHERE t.date_start <= "' . $today . '"'
            . '   AND (t.date_end IS NULL OR t.date_end >= "' . $today . '")'
        ;
        $recurrances = Transaction_Recurrance_Model::get($sql);

        foreach ($recurrances as $recurrance)
        {
            Transaction_Recurrance_Controller::catchup($recurrance);
        }
    }
}
