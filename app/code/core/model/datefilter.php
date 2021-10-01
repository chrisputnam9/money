<?php
namespace MCPI;

use DateTime;

/**
 * Core Date Filter
 *  - Add date nav - month, year, prev, next
 *  - Manage params in URL
 */
class Core_Model_Datefilter extends Core_Model_Abstract
{
    protected static $instance = null;

    protected $_offset;
    protected $_period;

    protected  $_period_start;
    protected  $_period_end;

	protected $_time_data;

	/* Misc. Data */
	public $now;

    /* Date ranges */
    public $month_start;
    public $month_end;

    public $year_start;
    public $year_end;

    /* Output for template */
    public $url_prev;
    public $url_next;

    public $month_url;
    public $year_url;

    public $month_active;
    public $year_active;

    public $title;

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
        $offset = $this->getOffset();
        $period = $this->getPeriod();
        $timestring = ($offset > 0 ? "+" . $offset : $offset) . " ".$period."s";

		$this->now = new DateTime();

        $this->month_start = new DateTime("first day of this month 00:00:00");
        $this->month_start->modify($timestring);

        $this->month_end = clone $this->month_start;
        $this->month_end->modify("+1 month");

        $this->year_start = clone $this->month_start;
        $this->year_start->modify("first day of January");

        $this->year_end = clone $this->year_start;
        $this->year_end->modify("+1 year");

        $format = ($period == 'year') ? 'Y' : 'F, Y';
        $this->title = $this->month_start->format($format);
    }

    /**
     * Enable
     */
    public function enable()
    {
        $response = $this->getResponse();

        $request = $this->getRequest();

        $response->main_data['show_date_menu'] = true;
        $response->main_data['date_menu_data'] = $this;

        $offset = $this->getOffset();
        $period = $this->getPeriod();

        $this->url_prev = $this->getRequest()->url(null,['offset' => $offset - 1]);
        $this->url_next = $this->getRequest()->url(null,['offset' => $offset + 1]);

        $switch_period = ($period == 'year') ? 'month' : 'year';
        $this->period_switch_url = $this->getRequest()->url(null,['period' => $switch_period, 'offset' => null]);

        $this->month_active = ($period == 'month');
        $this->year_active = ($period == 'year');
    }

    /**
     * Get Offset
     */
    public function getOffset()
    {
        if (is_null($this->_offset))
        {
            $request = $this->getRequest();
            $this->_offset = (int) $request->get('offset', 'number_int');
        }
        return $this->_offset;
    }

    /**
     * Get Period
     */
    public function getPeriod()
    {
        if (is_null($this->_period))
        {
            $request = $this->getRequest();
            $period = (string) $request->get('period');
            $this->_period = ($period == 'year') ? 'year' : 'month';
        }
        return $this->_period;
    }

    /**
     * Get Period Start
     */
    public function getPeriodStart()
    {
        if (is_null($this->_period_start))
        {
            $period = $this->getPeriod();
            $this->_period_start = $this->{$period . '_start'};
        }
        return $this->_period_start;
    }

    /**
     * Get Period End
     */
    public function getPeriodEnd()
    {
        if (is_null($this->_period_end))
        {
            $period = $this->getPeriod();
            $this->_period_end = $this->{$period . '_end'};
        }
        return $this->_period_end;
    }

	/**
	 * Get Time Data
	 */
	public function getTimeData()
	{
		if (is_null($this->_time_data))
		{
			$start = $this->getPeriodStart()->getTimestamp();
			$end = $this->getPeriodEnd()->getTimestamp();
			$now = $this->now->getTimestamp();

			// Make "now" no later than end
			if ($now > $end) $now = $end;
			// Make "now" no earlier than start
			if ($now < $start) $now = $start;


			// Will round up/down based on current time of day
			$days_spent = round(($now - $start) / 86400);
			// Should be exact, but we'll round to make sure
			$total_days = round(($end - $start) / 86400);
			$remaining_days = $total_days - $days_spent;
			$remaining_percentage = round(($remaining_days / $total_days) * 100, 2);

			$this->_time_data = [
				'days_spent' => number_format($days_spent),
				'total_days' => number_format($total_days),
				'remaining_percentage' => number_format($remaining_percentage, 2),
				'remaining_formatted' => number_format($remaining_days) . " day" . ($remaining_days == 1 ? "" : "s"),
				'low' => ($remaining_percentage < 5),
			];
		}
		return $this->_time_data;
	}

}
