<?php
namespace MCPI;

/**
 * Transaction Recurrance Type Abstract
 *  - Recurrance types inherit this structure and methods
 */
abstract class Transaction_Recurrance_Type_Abstract extends Core_Model_Dbo
{

    // Table Name
    protected static $table = 'transaction_recurring';

    // Transaction ID
    protected $transaction_id;

    protected $_fields = [];


    /**
     * Constructor
     * @param transaction_id
     * @param data - repetition config
     */
    public function __construct($transaction_id, $data)
    {
        $this->_fields = array_merge(static::$fields, [
            'date_start',
            'date_end',
        ]);

        $this->transaction_id = $transaction_id;

        $old_data = self::getBy(['main_transaction_id' => $transaction_id]);

        /*
        if (is_array($old_data))
        {
            $old_data = reset($old_data);
            $data_diff = array_diff($old_data, $data);
            self::log($old_data);
            self::log($data);
            self::log($data_diff);
            die;
        }
         */

        $this->setData($data);
    }

    /**
     * Get All Data
     */
    public function getData()
    {
        $data = [];
        foreach ($this->_fields as $field)
        {
            $data[$field] = $this->$field;
        }
        return $data;
    }

    /**
     * Set Data from Array
     */
    public function setData($data)
    {
        foreach ($data as $key => $value)
        {
            if (in_array($key, $this->_fields))
            {
                $this->$key = $value;
            }
        }
    }

    /**
     * Update repetition config
     */
    public function update()
    {
        $this->save(array(
            'main_transaction_id' => $this->transaction_id,
            'date_end' => $this->date_end,
            'recurrance_type' => static::$type,
            'recurrance_data' => json_encode($this->getData()),
        ));

    }

    /**
     * Catchup repetitions to given dates
     */
    abstract public function catchup($from=null,$to=null);

}

