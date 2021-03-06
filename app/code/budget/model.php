<?php
namespace MCPI;

use Exception;

/**
 * Budget Model
 */
class Budget_Model extends Core_Model_Dbo
{
    protected static $table = 'budget';

    protected static $instance = null;

    public $date_filter;

    public $month_start;
    public $month_end;

    public $year_start;
    public $year_end;

    protected $_budgets = null;
    protected $_budgeted = null;
    protected $_total_budgeted = null;
    protected $_unbudgeted = null;
    protected $_spending = [];

    /**
     * Singleton - get instance
     */
    static public function instance()
    {
        if (is_null(self::$instance))
        {
            self::$instance = new Self();
        }
        return self::$instance;
    }

    public function __construct ()
    {
        $this->date_filter = self::getDateFilter();

        $this->month_start = $this->date_filter->month_start;
        $this->month_end = $this->date_filter->month_end;

        $this->year_start = $this->date_filter->year_start;
        $this->year_end = $this->date_filter->year_end;

        $this->year_to_month_start = $this->date_filter->year_start;
        $this->year_to_month_end = $this->date_filter->month_start;
    }

    /**
     * Get Total Budget across all categories
     */
    public function getTotalBudgeted()
    {
        $this->getBudgeted();
        return $this->_total_budgeted;

    }

    /**
     * Get Budgeted totals
     */
    public function getBudgeted($cat_id=null)
    {
        if (is_null($this->_budgeted))
        {
            $request = $this->getRequest();
            $period = $this->date_filter->getPeriod();

            $diff = $this->month_start->diff($this->year_end);
            $months_remaining = (12 * $diff->y) + $diff->m;

            $this->_budgeted = [];
            $total_budgeted = [
                'limit' => 0,
                'annual_limit' => 0,
                'spending' => 0,
                'remaining' => 0,
                'year_spending' => 0,
                'year_to_month_spending' => 0,
                'month_spending' => 0,
                'months_remaining' => $months_remaining,
            ];

            foreach ($this->getBudgets() as $_cat_id => $budget_data)
            {
                $spending = [];

                $spending_year = $this->getSpending('year', $_cat_id);
                $spending['year'] = empty($spending_year) ? 0 : $spending_year['amount'];

                $spending_year_to_month = $this->getSpending('year_to_month', $_cat_id);
                $spending['year_to_month'] = empty($spending_year_to_month) ? 0 : $spending_year_to_month['amount'];

                $spending_month = $this->getSpending('month', $_cat_id);
                $spending['month'] = empty($spending_month) ? 0 : $spending_month['amount'];

                $budgeted = [
                    'category' => $budget_data['category'],
                    'period' => $period,
                    'transactions_url' => $request->url(['transaction','list'],['category' => $_cat_id]),
                    'limit' => 0,
                    'annual_limit' => 0,
                    'spending' => $spending[$period],
                    'remaining' => 0,
                    'remaining_percentage' => 0,
                    'year_spending' => $spending['year'],
                    'year_to_month_spending' => $spending['year_to_month'],
                    'month_spending' => $spending['month'],
                    'months_remaining' => $months_remaining,
                    'budgets' => $budget_data['budget_list'],
                ];

                $total_budgeted['spending']+= $spending[$period];
                $total_budgeted['year_spending']+= $spending['year'];
                $total_budgeted['year_to_month_spending']+= $spending['year_to_month'];
                $total_budgeted['month_spending']+= $spending['month'];

                foreach ($budget_data['budget_list'] as $budget)
                {
                    $limit = $this->addToLimit($budgeted, $budget);
                    $limit = $this->addToLimit($total_budgeted, $budget);
                }

                // Allow overflow from one month into remainder of year
                // - start with year's spending, up to (but not including) current month
                // - subtract result from yearly limit
                // - divide that by months remaining in the year
                if ($period == 'month')
                {
                    $budgeted['limit'] = (
                        ($budgeted['annual_limit'] - $budgeted['year_to_month_spending'])
                        /
                        $budgeted['months_remaining']
                    );
                }
                else
                {
                    $budgeted['limit'] = $budgeted['annual_limit'];
                }

                $this->_budgeted[$_cat_id]= $this->finalizeBudgeted($budgeted);
            }


            // Allow overflow from one month into remainder of year
            // - start with year's spending, up to (but not including) current month
            // - subtract result from yearly limit
            // - divide that by months remaining in the year
            if ($period == 'month')
            {
                $total_budgeted['limit'] = (
                    ($total_budgeted['annual_limit'] - $total_budgeted['year_to_month_spending'])
                    /
                    $total_budgeted['months_remaining']
                );
            }
            else
            {
                $total_budgeted['limit'] = $total_budgeted['annual_limit'];
            }

            $this->_total_budgeted = $this->finalizeBudgeted($total_budgeted);
        }

        if (is_null($cat_id))
            return $this->_budgeted;

        if (empty($this->_budgeted[$cat_id]))
            return false;

        return $this->_budgeted[$cat_id];
    }

        /**
         * Finalize budgeted values for display
         */
        protected function finalizeBudgeted($budgeted)
        {
            $budgeted['remaining'] = ($budgeted['limit'] - $budgeted['spending']);
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

            $budget_list = [];
            if (!empty($budgeted['budgets']))
            {
                foreach ($budgeted['budgets'] as $budget)
                {
                    $budget_list[]= '$' . number_format($budget['amount']) . '/' . $budget['timespan'];
                }
            }
            $budgeted['budget_list_formatted'] = implode(", ", $budget_list);

            return $budgeted;
        }

        /**
         * Add budget limit for a given period
         */
        protected function addToLimit(&$budgeted, $budget)
        {
            $budget_period = $budget['timespan'];
            $limit = $budget['amount'];
            $annual_limit = ($budget_period == 'month') ? $limit * 12 : $limit;
            return $budgeted['annual_limit']+= $annual_limit;
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
            $request = $this->getRequest();
            $this->_unbudgeted = [];
            foreach ($spending as $cat_id => $row)
            {
                if (!empty($budgets[$cat_id])) continue;    
                $row['amount_formatted'] = '$' . number_format($row['amount'], 2);
                $row['transactions_url'] = $request->url(['transaction','list'],['category' => $cat_id]);

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
                'SELECT SUM(IF(class.title IN ("Credit","Income"), -1 * t.amount, t.amount)) as amount'
                    . ', cat.id as category_id, cat.title as category_value'
                . ' FROM transaction t'
                . ' LEFT JOIN transaction_category cat ON (t.category = cat.id)'
                . ' LEFT JOIN transaction_classification class ON (t.classification = class.id)'
                . ' WHERE t.date_occurred >= ?'
                . ' AND t.date_occurred < ?'
                . ' GROUP BY category_value'
                . ' ORDER BY category_value'
            ;

            $this->_spending[$period] = self::get($sql, [
                $this->{$period . '_start'}->format('Y-m-d H:i:s'),
                $this->{$period . '_end'}->format('Y-m-d H:i:s'),
            ], 'category_id');
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
