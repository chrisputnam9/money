<?php
namespace MCPI;

/**
 * Transaction Recurrance Type Month
 *  - Repeat every X months on the Y day of the month
 */
class Transaction_Recurrance_Type_Month extends Transaction_Recurrance_Type_Abstract
{

    protected static $type = 'month';
    protected static $fields = array(
        'month_count',
        'month_day',
    ); 

    /**
     * Check Data and throw error if there's an issue
     */
    public function checkData($data)
    {
        if (
            empty($data['month_count'])
            or
            empty($data['month_day'])
        ) {
            self::error('Missing required data.  Go back and try again.', true);
        }
    }

    /**
     * Catch up repetitions
     */
    public function catchup()
    {
        $data = $this->getRecurringData();
        $config = $data['recurrance_data'];

        $start = $this->getStart();
        $d = $start->format('d');
        $m = $start->format('m');
        $Y = $start->format('Y');
        // Get on to the correct day of the month
        $start->setDate($Y , $m , $config['month_day']);
            
        $end = $this->getEnd();

        // Will use this to iterate below
        $mod_string = '+' . $config['month_count'] . ' months';

        $this->catchupByModString($start, $end, $mod_string);
    }
}
