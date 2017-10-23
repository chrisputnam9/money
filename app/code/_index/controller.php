<?php
namespace MCPI;

/**
 * Index Controller
 */
class Index_Controller extends Core_Controller_Abstract
{
    // Set defaults
    static public function route()
    {
        $request = self::getRequest();
        $response = self::getResponse();

        // Add menu items
        $response->menu =  [
            'budget' => [
                'title' => 'Budgets',
                'url' => '/budget/list',
                'class' => '',
            ],
            'transaction' => [
                'title' => 'Transactions',
                'url' => '/transaction/list',
                'class' => '',
            ],
        ];

        if (empty($request->index()))
        {
            // Designate homepage
            $request->uri_segments = [
                'transaction',
                'list',
            ];
        }
    }
}
Index_Controller::route();
