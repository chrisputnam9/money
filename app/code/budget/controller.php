<?php
namespace MCPI;

/**
 * Budget Controller
 */
class Budget_Controller extends Core_Controller_Abstract
{

    static public function route()
    {
        $request = self::getRequest();
        $response = self::getResponse();

        if ($request->index(0,'budget'))
        {

            // List
            if (empty($request->index(1)) or $request->index(1,'list'))
            {
                $response->menu['budget']['class'] = 'active';
                $response->main_data['show_menu'] = true;
                $response->main_data['show_transaction_buttons'] = true;

                $timestring = null;
                $month = (int) $request->get('month', 'number_int');
                if ($month)
                {
                    $timestring = ($month > 0 ? "+" . $month : $month) . " months";
                }

                $budget = new Budget_Model($timestring);

                $response->body_template = 'budget_list';

                $month_budgeted = array_values($budget->getBudgeted('month'));
                $month_unbudgeted = array_values($budget->getUnbudgeted('month'));

                $year_budgeted = array_values($budget->getBudgeted('year'));
                $year_unbudgeted = array_values($budget->getUnbudgeted('year'));

                $response->body_data = [
                    'month_title' => $budget->month_start->format('F, Y'),
                    'prev_month_url' => '/budget/list/?month=' . ($month - 1),
                    'next_month_url' => '/budget/list/?month=' . ($month + 1),

                    'month_budgeted' => $month_budgeted,
                    'month_unbudgeted' => $month_unbudgeted,
                    'month_budgeted_length' => count($month_budgeted),
                    'month_unbudgeted_length' => count($month_unbudgeted),

                    'year_title' => $budget->year_start->format('Y'),
                    'year_budgeted' => $year_budgeted,
                    'year_unbudgeted' => $year_unbudgeted,
                    'year_budgeted_length' => count($year_budgeted),
                    'year_unbudgeted_length' => count($year_unbudgeted),
                ];
            }

            $response->finalize();
        }
    }

}
Budget_Controller::route();
