<?php
namespace MCPI;

/**
 * Transaction Recurrance Type Abstract
 *  - Recurrance types inherit this structure and methods
 */
abstract class Transaction_Recurrance_Type extends Core_Model_Dbo
{

    // Data saved for recurrance options
    protected $data;

    // Fields template for recurrance options
    protected $fields_template;

    /**
     * Constructor
     *  - called with data saved from this type's fields as json
     */
    public function __construct($data)
    {
        $this->data = json_decode($data);
    }

    /**
     * Update
     *  - Update the transactions, given a recurring master transaction
     */
    abstract public function update($master);

}

