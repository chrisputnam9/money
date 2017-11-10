<?php
namespace MCPI;

use \DateTime;

/**
 * Transaction Recurrance Model
 */
class Transaction_Recurrance_Model extends Core_Model_Dbo
{
    public static $table = 'transaction_recurring';
    public static $trt_table = 'transaction_recurring_transaction';

    // Recurring Data Fields 
    protected $_recurring_fields = [];

    // IDs
    protected $_transaction_id;

    protected $_recurring_id;

    protected $_transaction_data;
    protected $_recurring_data;
    protected $_children;

    /**
     * Constructor
     * @param transaction_id
     */
    public function __construct($transaction_id)
    {
        $this->_transaction_id = $transaction_id;
    }

    /**
     * Add a child for the given date
     *  - if it doesn't already exist
     */
    public function addChild($dt)
    {
        $stamp = $dt->format('Y-m-d H:i:s');

        // Does it already exist?
        $children = $this->getChildren();
        if (isset($children[$stamp])) return;

        $data = $this->getTransactionData();
        if (empty($data)) self::error('Parent transaction not found');

        // Clean it up for child
        unset($data['id']);
        $data['date_occurred'] = $stamp;

        // Save child
        Transaction_Model::save($data);
        $child_id = Transaction_Model::lastInsertId();

        // Save link
        self::save([
            'transaction_recurring_id' => $this->getRecurringId(),
            'transaction_id' => $child_id,
        ], static::$trt_table);
    }

    /**
     * Delete all recurrance children
     *  - based on transaction_recurring:id
     */
    public function deleteChildren()
    {
        $trt_table = self::cleanTable(static::$trt_table);
        $t_table = self::cleanTable(Transaction_Model::$table);

        $data = new Core_Model_Dbo_Data(['id' => $this->getRecurringId()]);

        $sql = 'DELETE tc'
             . ' FROM ' . $trt_table . ' trt'
             . ' LEFT JOIN ' . $t_table . ' tc'
             . '  ON (trt.transaction_id = tc.id)'
             . ' WHERE trt.transaction_recurring_id IN'
             . '  ('.join(',', $data->placeholders()).')'
        ;

        return self::execute($sql, $data->hash());
    }

    /**
     * Get Recurring Data
     */
    public function getRecurringData()
    {
        if(is_null($this->_recurring_data))
        {
            $this->_recurring_data = [];

            $data = self::getBy([
                'main_transaction_id' => $this->_transaction_id
            ]);
            
            if (is_array($data) and !empty($data))
            {
                $data = reset($data);
                $data['recurrance_data'] = json_decode($data['recurrance_data'], true);
                if ($data['recurrance_data'])
                {
                    foreach ($data['recurrance_data'] as $key => $value)
                    {
                        $data[$key] = $value;
                    }
                    $type = $data['recurrance_type'];
                    $data['type_'.$type] = true;
                }
                $this->_recurring_data = $data;
            }
        }
        return $this->_recurring_data;
    }

    /**
     * Save New Recurring Data
     */
    public function saveRecurringData($data, $delete_children=false)
    {
        $db_data = false;

        // Don't bother querying db if we already know we're deleting
        if (!$delete_children)
        {
            $db_data = $this->getRecurringData();
        }

        $config = [];
        foreach ($data as $key => $value)
        {
            if (in_array($key, $this->_recurring_fields))
            {
                $config[$key] = $value;
            }
        }

        // Arrange new data for saving
        $new_data = [
            'main_transaction_id' => $this->_transaction_id,
            'date_start' => $config['date_start'],
            'date_end' => $config['date_end'],
            'recurrance_type' => static::$type,
            'recurrance_data' => $config,
        ];

        // Was there data in DB?
        //  - if so...
        if (!empty($db_data))
        {
            $old_data = [];

            // Align the data
            foreach ($new_data as $key => $value)
            {
                if (isset($db_data[$key]))
                    $old_data[$key] = $db_data[$key];
            }

            // If no change, no need to update anything at all!
            if ($old_data == $new_data)
                return true;

            // If recurrance data changed
            // we'll delete all recurrance children
            // and they'll be recreated - just to
            // keep things simple
            if (
                $old_data['recurrance_type'] != $new_data['recurrance_type']
                or
                $old_data['recurrance_data'] != $new_data['recurrance_data']
            ) {
                $delete_children = true;
            }
        }

        if ($delete_children) $this->deleteChildren();

        $this->_recurring_data = $new_data;

        $new_data['recurrance_data'] = json_encode($new_data['recurrance_data']);
        self::save($new_data);

        $this->_recurring_data['id'] = self::lastInsertId();
    }

    /**
     * Get Recurring ID
     */
    public function getRecurringId()
    {
        if(is_null($this->_recurring_id))
        {
            $data = $this->getRecurringData();
            $this->_recurring_id = $data['id'];
        }
        return $this->_recurring_id;
    }

    /**
     * Get Main Transaction Data
     */
    public function getTransactionData()
    {
        if(is_null($this->_transaction_data))
        {
            $id = $this->_transaction_id;
            $data = Transaction_Model::getById($id);
            if (!empty($data)) $data = reset($data);
            $this->_transaction_data = $data;
        }
        return $this->_transaction_data;
    }

    /**
     * Get all recurrance children
     *  - based on transaction_recurring:id
     *  - keyed by date
     */
    public function getChildren()
    {
        if (is_null($this->_children))
        {
            $trt_table = self::cleanTable(static::$trt_table);
            $t_table = self::cleanTable(Transaction_Model::$table);
            $data = new Core_Model_Dbo_Data(['id' => $this->getRecurringId()]);

            $sql = 'SELECT tc.*'
                . ' FROM ' . $trt_table . ' trt'
                . ' LEFT JOIN ' . $t_table . ' tc'
                . '  ON (trt.transaction_id = tc.id)'
                . ' WHERE trt.transaction_recurring_id IN'
                . '  ('.join(',', $data->placeholders()).')'
                . ' ORDER BY tc.date_occurred ASC'
            ;
            $this->_children = self::get($sql, $data->hash(), 'date_occurred');
        }

        return $this->_children;

    }

    /**
     * Get Parent (if exists) of potential child transaction
     */
    public static function getParentOf($child_id)
    {
        $tr_table = self::cleanTable(static::$table);
        $trt_table = self::cleanTable(static::$trt_table);
        $data = new Core_Model_Dbo_Data(['id' => $child_id]);

        $sql = 'SELECT tr.*'
            . ' FROM ' . $trt_table . ' trt'
            . ' LEFT JOIN ' . $tr_table . ' tr'
            . '  ON (trt.transaction_recurring_id = tr.id)'
            . ' WHERE trt.transaction_id IN'
            . '  ('.join(',', $data->placeholders()).')'
        ;
        $parent = self::get($sql, $data->hash());

        if (empty($parent)) return false;

        return reset($parent);
    }
}
