<?php
namespace MCPI;

use Exception;

/**
 * Budget Model
 */
class Budget_Model extends Core_Model_Dbo
{
    protected static $table = 'budget';

    public $date_filter;

    public $month_start;
    public $month_end;

    public $year_start;
    public $year_end;

    protected $_budgets = null;
    protected $_budgeted = null;
    protected $_unbudgeted = null;
    protected $_spending = [];

    public function __construct ($date_filter)
    {
        $this->date_filter = $date_filter;
        $this->month_start = $date_filter->month_start;
        $this->month_end = $date_filter->month_end;
        $this->year_start = $date_filter->year_start;
        $this->year_end = $date_filter->year_end;
    }

    /**
     * Get Budgeted totals
     */
    public function getBudgeted()
    {
        if (is_null($this->_budgeted))
        {
            $period = $this->date_filter->getPeriod();
            $this->_budgeted = [];
            foreach ($this->getBudgets() as $cat_id => $budget_data)
            {
                $spending = [];
                $spending_year = $this->getSpending('year', $cat_id);
                $spending['year'] = empty($spending_year) ? 0 : $spending_year['amount'];
                $spending_month = $this->getSpending('month', $cat_id);
                $spending['month'] = empty($spending_month) ? 0 : $spending_month['amount'];

                $diff = $this->month_start->diff($this->year_end);
                $months_remaining = (12 * $diff->y) + $diff->m;

                $budgeted = [
                    'period' => $period,
                    'category' => $budget_data['category'],
                    'limit' => 0,
                    'spending' => $spending[$period],
                    'remaining' => 0,
                    'remaining_percentage' => 0,
                    'year_spending' => $spending['year'],
                    'month_spending' => $spending['month'],
                    'months_remaining' => $months_remaining,
                    'budgets' => $budget_data['budget_list'],
                ];

                foreach ($budget_data['budget_list'] as $budget)
                {
                    $limit = $this->addToLimit($budgeted, $budget, $period);
                }

                $budgeted['remaining'] = max(0,$budgeted['limit'] - $budgeted['spending']);
                if ($budgeted['limit'] > 0)
                    $budgeted['remaining_percentage'] = round(($budgeted['remaining'] / $budgeted['limit']) * 100);

                $budgeted['status'] = 'success';
                if ($budgeted['remaining_percentage'] < 50)
                    $budgeted['status'] = 'warning';
                if ($budgeted['remaining_percentage'] < 5)
                    $budgeted['status'] = 'danger';

                $budgeted['limit_formatted'] = '$' . number_format($budgeted['limit'], 2);
                $budgeted['spending_formatted'] = '$' . number_format($budgeted['spending'], 2);
                $budgeted['remaining_formatted'] = '$' . number_format($budgeted['remaining'], 2);
                $budgeted['year_spending_formatted'] = '$' . number_format($budgeted['year_spending'], 2);
                $budgeted['month_spending_formatted'] = '$' . number_format($budgeted['month_spending'], 2);

                $this->_budgeted[]= $budgeted;
            }
        }

        return $this->_budgeted;
    }
        /**
         * Add budget limit for a given period
         */
        protected function addToLimit(&$budgeted, $budget, $current_period)
        {
            $budget_period = $budget['timespan'];
            $limit = $budget['amount'];

            // Same period - simple
            if ($budget_period == $current_period)
                return $budgeted['limit']+= $limit;

            // Monthly to yearly - multiply by 12 (months in year)
            if ($budget_period == 'month' and $current_period == 'year')
                return $budgeted['limit']+= ($limit * 12);

            // Yearly to monthly - a bit trickier...
            // - start with year's spending
            // - subtract months spending
            // - subtract result from yearly limit
            // - divide that by months remining in the year
            // - max - no less than 0
            if ($budget_period == 'year' and $current_period == 'month')
                return $budgeted['limit']+= max(0,
                    ($limit - ($budgeted['year_spending'] - $budgeted['month_spending']))
                        /
                    $budgeted['months_remaining']
                );

            throw new Exception('Unexpected budget conversion situation');
        }

    /**
     * Get Unbudgeted totals by period
     */
    public function getUnbudgeted()
    {
        if (is_null($this->_unbudgeted))
        {
            $period = $this->date_filter->getPeriod();
            $budgets = $this->getBudgets();
            $spending = $this->getSpending($period);
            $this->_unbudgeted = [];
            foreach ($spending as $cat_id => $row)
            {
                if (!empty($budgets[$cat_id])) continue;    
                $row['amount_formatted'] = '$' . number_format($row['amount'], 2);
                $this->_unbudgeted[] = $row;
            }
        }
        return $this->_unbudgeted;
    }

    /**
     * Get All totals by period
     */
    public function getSpending($period, $cat_id=null)
    {
        if (!isset($this->_spending[$period]))
        {
            $sql =
                'SELECT SUM(IF(class.title = "Credit", -1 * t.amount, t.amount)) as amount'
                    . ', cat.id as category_id, cat.title as category_value'
                . ' FROM transaction t'
                . ' LEFT JOIN transaction_category cat ON (t.category = cat.id)'
                . ' LEFT JOIN transaction_classification class ON (t.classification = class.id)'
                . ' WHERE t.date_occurred >= "'.$this->{$period . '_start'}->format('Y-m-d H:i:s').'"'
                . ' AND t.date_occurred < "'.$this->{$period . '_end'}->format('Y-m-d H:i:s').'"'
                . ' GROUP BY category_value'
                . ' ORDER BY category_value'
            ;

            $this->_spending[$period] = self::get($sql, [], 'category_id');
        }

        // no category specified, return all
        if (empty($cat_id))
            return $this->_spending[$period];

        // specific id, but doesn't exist
        if (empty($this->_spending[$period][$cat_id]))
            return false;

        // specific id of spending
        return $this->_spending[$period][$cat_id];
    }

    /**
     * Get Budgets
     */
    public function getBudgets()
    {
        if (is_null($this->_budgets))
        {
            $sql =
                'SELECT b.id, cat.id as category_id, cat.title as category_value, b.timespan, b.amount'
                . ' FROM budget b'
                . ' LEFT JOIN transaction_category cat ON (b.category = cat.id)'
                . ' ORDER BY category_value ASC, timespan ASC'
            ;

            $budgets = self::get($sql);
            $this->_budgets = [];
            foreach ($budgets as $budget)
            {
                $category = $budget['category_id'];
                if (!isset($this->_budgets[$category]))
                {
                    $this->_budgets[$category] = [
                        'category' => $budget['category_value'],
                        'budget_list' => [],
                    ];
                }
                $this->_budgets[$category]['budget_list'][]= $budget;
            }
        }
        return $this->_budgets;
    }
}
