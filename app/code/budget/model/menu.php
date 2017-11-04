<?php
namespace MCPI;

/**
 * Budget Menu Model
 */
class Budget_Model_Menu extends Core_Model_Abstract
{
    protected static $instance = null;

    /* Output for template */
    public $title;
    
    public $budget = false;

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

    /**
     * Constructor
     */
    protected function __construct()
    {
    }

    /**
     * Enable
     */
    public function enable()
    {
        $response = $this->getResponse();
        $request = $this->getRequest();

        $date_filter = self::getDateFilter();
        $budget = Budget_Model::instance();

        $response->main_data['show_budget_menu'] = true;
        $response->main_data['budget_menu_data'] = $this;

        $category = false;
        $category_id = $request->get('category');

        $title = $date_filter->title;

        if ($category_id)
        {
            $this->budget = $budget->getBudgeted($category_id);
            $title.= ' - ' . $this->budget['category'];
        }
        else
        {
            $this->budget = $budget->getTotalBudgeted();
        }

        $this->title = $title;

    }

}
