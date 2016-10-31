<?php
namespace MCPI;

use \DateTime;

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

    public function catchup($from=null,$to=null)
    {
        if (is_null($from) and is_null($to))
        {
            $to = new DateTime();
        }

        var_dump($from);
        var_dump($to);
        die;
    }
}
