<?php
namespace MCPI;

use \DateTime;

/**
 * Transaction Recurrance Type Abstract
 *  - Recurrance types inherit this structure and methods
 */
class Transaction_Recurrance_Type_Abstract extends Transaction_Recurrance_Model
{
    protected static $fields = [];

    /**
     * Constructor
     * @param transaction_id
     */
    public function __construct($transaction_id)
    {
        $this->_recurring_fields = array_merge(static::$fields, [
            'date_start',
            'date_end',
        ]);

        parent::__construct($transaction_id);
    }

    /**
     * Generic catchup logic with date modification string
     */
    public function catchupByModString($next, $end, $mod_string)
    {
        $i = 1;
        $prev = clone $next;
        while ($next->modify($mod_string) <= $end)
        {
            if ($i > 100)
                self::error('Too many repeats during catchup.  Something went wrong.', true);

            // 12 hrs: 60*60*12 = 43200
            if (($next->getTimestamp() - $prev->getTimestamp()) < 43200)
                self::error('Repeat too frequent during catchup.  Something went wrong.', true);

            $this->addChild($next);

            $i++;
            $prev = clone $next;
        }
    }

    /**
     * Get start date for catchup
     */
    protected function getStart()
    {
        $data = $this->getRecurringData();
        $config = $data['recurrance_data'];

        if (!isset($config['date_start']))
        {
            self::error('Missing date start for recurrance catchup');
        }

        return new DateTime($config['date_start']);
    }

    /**
     * Get end date for catchup
     */
    protected function getEnd()
    {
        $data = $this->getRecurringData();
        $config = $data['recurrance_data'];

        if (!isset($config['date_end']))
        {
            self::error('Missing date end for recurrance catchup');
        }

        $now = new DateTime('today 00:00:00');

        // Is there valid end date?
        if (!empty($config['date_end']))
        {
            $end = new DateTime($config['date_end']);

            // Is the end date in the past?
            if ($now > $end)
            {
                return $end;
            }
        }

        return $now;
    }

    /**
     * Catchup repetitions
     */
    public function catchup()
    {
        self::error('Implement this in child classes');
    }

    /**
     * Catchup repetitions to given dates
     */
    public function checkData($data)
    {
        self::error('Implement this in child classes');
    }

}

