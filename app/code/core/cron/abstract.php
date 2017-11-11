<?php
namespace MCPI;

/**
 * Core Cron Abstract
 */
class Core_Cron_Abstract extends Core_Abstract
{
    public $now;
    public $timediff;

    /**
     * Constructor
     */
    public function __construct($now, $timediff)
    {
        $this->now = $now;
        $this->timediff = $timediff;
    }

    /**
     * Run crons based on timediff
     *  - We assume here cron is running every min.
     *  - It could run less often theoretically
     */
    public function run() {
        // On the hour
        if ($this->timediff->m == 0)
        {
            // hour 0 - midnight
            if ($this->timediff->h == 0)
            {
                // Daily Job
                if (is_callable([$this, 'day'])) $this->day();
            }

            // Hourly job
            if (is_callable([$this, 'hour'])) $this->hour();
        }

        // Minutely job
        if (is_callable([$this, 'minute'])) $this->minute();

    }
}
