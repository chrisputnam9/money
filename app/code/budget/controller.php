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

                $date_filter = self::getDateFilter();
				$date_filter->enable();
				$time_data = $date_filter->getTimeData();
                self::getBudgetMenu()->enable();

                $budget = Budget_Model::instance();

                $response->body_template = 'budget_list';

                $budgeted = array_values($budget->getBudgeted());
                $unbudgeted = array_values($budget->getUnbudgeted());

                $response->body_data = [
					'time' => $time_data,
                    'budgeted' => $budgeted,
                    'unbudgeted' => $unbudgeted,
                    'budgeted_length' => count($budgeted),
                    'unbudgeted_length' => count($unbudgeted),
                ];

            }

            $response->finalize();
        }
    }

}
Budget_Controller::route();
