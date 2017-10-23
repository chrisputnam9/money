<?php
namespace MCPI;

use DateTime;

/**
 * Budget Model
 */
class Budget_Model extends Core_Model_Dbo
{
    protected static $table = 'transaction';

    public $month_start;
    public $month_end;

    public $year_start;
    public $year_end;

    public function __construct ($timestring="")
    {
        $this->month_start = new DateTime("first day of this month 00:00:00");
        if (!empty($timestring))
        {
            $this->month_start->modify($timestring);
        }

        $this->month_end = clone $this->month_start;
        $this->month_end->modify("+1 month");

        $this->year_start = clone $this->month_start;
        $this->year_start->modify("first day of January");

        $this->year_end = clone $this->year_start;
        $this->year_end->modify("+1 year");
    }

    /**
     * Get Budgeted totals
     */
    public function getBudgeted($period)
    {
        return [];
    }

    /**
     * Get Unbudgeted totals by period
     */
    public function getUnbudgeted($period)
    {
        $data = $this->getAllByPeriod($period);
        foreach ($data as &$row)
        {
            $row['amount_formatted'] = '$' . number_format($row['amount'], 2);
        }
        return $data;
    }

    /**
     * Get All totals by period
     */
    public function getAllByPeriod($period)
    {
        $table = self::cleanTable();
        $sql =
            'SELECT SUM(IF(class.title = "Credit", -1 * t.amount, t.amount)) as amount'
                . ', cat.title as category_value'
            . ' FROM ' . $table . ' t'
            . ' LEFT JOIN transaction_category cat ON (t.category = cat.id)'
            . ' LEFT JOIN transaction_classification class ON (t.classification = class.id)'
            . ' WHERE t.date_occurred >= "'.$this->{$period . '_start'}->format('Y-m-d H:i:s').'"'
              . ' AND t.date_occurred < "'.$this->{$period . '_end'}->format('Y-m-d H:i:s').'"'
            . ' GROUP BY category_value'
            . ' ORDER BY category_value'
        ;
        return self::get($sql, [], 'category_value');
    }
}
