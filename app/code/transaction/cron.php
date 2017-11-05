<?php
namespace MCPI;

class Transaction_Cron extends Core_Cron_Abstract
{
    /**
     * Run every day at midnight
     */
    protected function day()
    // protected function minute()
    {
        self::log('Updating repeat crons', 'Transaction Cron - Daily');
    }
}
