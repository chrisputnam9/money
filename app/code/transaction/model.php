<?php
namespace MCPI;

/**
 * Transaction Model
 */
class Transaction_Model extends Core_Model_Dbo
{
    public static $table = 'transaction';

    public $date_filter;
    public $category_id;

    protected $_category = null;
    protected $_listing = null;
    protected $_all_flat = null;

    public function __construct ()
    {
        $request = self::getRequest();
        $this->date_filter = self::getDateFilter();
        $this->category_id = $request->get('category', 'number_int');
    }

    // Find possible duplicates
    public static function findDuplicates($data)
    {
        if (!is_array($data) or empty($data))
        {
            throw new Exception ('Bad data based to findDuplicates');
        }

        $account_to = empty($data['account_to']) ? false : $data['account_to'];
        $account_from = empty($data['account_from']) ? false : $data['account_from'];
        $amount = empty($data['amount']) ? false : $data['amount'];
        $date_occurred = empty($data['date_occurred']) ? false : $data['date_occurred'];

        if (
            empty($account_to)
            or empty($account_from)
            or empty($amount)
            or empty($date_occurred)
        ) {
            return false;
        }

        $table = self::cleanTable(self::$table);

        $sql = <<<SQL
            SELECT * FROM {$table}
            WHERE (
                ( account_to = ? AND account_from = ? )
                OR
                ( account_from = ? AND account_to = ? )
            )
            AND amount = ?
            AND ABS(DATEDIFF(date_occurred, ?)) < 20
SQL;

        $data = [
            $account_to, $account_from,
            $account_to, $account_from,
            $amount,
            $date_occurred
        ];

        return self::get($sql, $data);
    }    

    /**
     * Get category name
     */
    public function getCategory()
    {
        if (is_null($this->_category))
        {
            if (empty($this->category_id))
            {
                $this->_category = "";
            }
            else
            {
                $all_flat = $this->getAllFlat();
                if (empty($all_flat))
                {
                    $sql = 'SELECT * FROM transaction_category WHERE id = ?';
                    $results = self::get($sql, [$this->category_id]);
                    if (!empty($results))
                    {
                        $result = reset($results);
                        $this->_category = $result['title'];
                    }
                }
                else
                {
                    // Save a query - get it from flat data
                    $first = reset($all_flat);
                    $this->_category = $first['category_value'];
                }
            }
        }

        return $this->_category;
    }

    // Listing Data
    public function getListing()
    {
        if (is_null($this->_listing))
        {
            $request = self::getRequest();
            $this->_listing = $this->getAllFlat();
            foreach ($this->_listing as &$row)
            {
                $row['entry_type'] = 'Manual Entry';
                $row['entry_icon'] = 'pencil';
                if (!empty($row['file']))
                {
                    $row['entry_type'] = 'Text / Document';
                    $row['entry_icon'] = 'align-left';
                }
                if (!empty($row['image']))
                {
                    $row['entry_type'] = 'Image';
                    $row['entry_icon'] = 'camera';
                }

                $row['category_url'] = $request->url(null, ['category' => $row['category']]);
                $row['amount_formatted'] = '$' . number_format($row['amount'], 2);
                $row['date_occurred_formatted'] = date('m/d/y', strtotime($row['date_occurred']));
            }

        }

        return $this->_listing;
    }

    // Get all data, flattened via joins
    public function getAllFlat()
    {
        if (is_null($this->_all_flat))
        {
            $table = self::cleanTable();
            $sql = 'SELECT t.*'
                    . ', af.title as account_from_value'
                    . ', at.title as account_to_value'
                    . ', cat.title as category_value'
                    . ', class.title as classification_value'
                    . ', s.title as status_value'
                    . ', (tr.id IS NOT NULL) as is_repeat_parent'
                    . ', (trt.id IS NOT NULL) as is_repeat_child'
                    . ', trp.main_transaction_id as repeat_parent_id'
                . ' FROM ' . $table . ' t'
                . ' LEFT JOIN account af ON (t.account_from = af.id)'
                . ' LEFT JOIN account at ON (t.account_to = at.id)'
                . ' LEFT JOIN transaction_category cat ON (t.category = cat.id)'
                . ' LEFT JOIN transaction_classification class ON (t.classification = class.id)'
                . ' LEFT JOIN transaction_status s ON (t.status = s.id)'
                . ' LEFT JOIN transaction_recurring tr ON (t.id = tr.main_transaction_id)'
                . ' LEFT JOIN transaction_recurring_transaction trt ON (t.id = trt.transaction_id)'
                . ' LEFT JOIN transaction_recurring trp ON (trt.transaction_recurring_id = trp.id)'
                . ' WHERE t.date_occurred >= ?'
                . ' AND t.date_occurred < ?'
            ;
            
            if ($this->category_id)
            {
                $sql.= ' AND t.category = "'.$this->category_id.'"';
            }

            $sql.= 
                ' ORDER BY t.date_occurred DESC'
            ;
            $this->_all_flat = self::get($sql, [
                $this->date_filter->getPeriodStart()->format('Y-m-d H:i:s'),
                $this->date_filter->getPeriodEnd()->format('Y-m-d H:i:s'),
            ]);
        }

        return $this->_all_flat;
    }

    // Get options for linked tables
    public function getOptions($data)
    {
        $request = self::getRequest();

        $account_options = Account_Model::getGroupedAccounts();

        return [
            'account_from_options' => self::populateSelectedOptions(
                $account_options,
                empty($data['account_from']) ? false : $data['account_from']
            ),
            'account_to_options' => self::populateSelectedOptions(
                array_reverse($account_options),
                empty($data['account_to']) ? false : $data['account_to']
            ),
            'amount_options' => self::populateSelectedOptions(
                $data['amount_options'],
                $data['amount']
            ),
            'category_options' => self::populateSelectedOptions(
                self::getAll('transaction_category', 'title'),
                empty($data['category']) ? false : $data['category']
            ),
            'classification_options' => self::populateSelectedOptions(
                self::getAll('transaction_classification'),
                empty($data['classification']) ? false : $data['classification']
            ),
        ];
    }

}
