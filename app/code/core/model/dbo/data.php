<?php
namespace MCPI;

/**
 * Core Model DBO Data
 *  - Stores data
 *  - Sanitizes data
 *  - Provides useful functions
 */
class Core_Model_Dbo_Data extends Core_Model_Abstract
{
    protected $raw_data;

    protected $field_index_map;

    protected $hash;
    protected $fields;
    protected $values;
    protected $placeholders;

    //TODO make iterable? could be nice

    /**
     * Consructor, sets raw data
     *  @param raw_data is expected to be hash, fields=>values
     */
    function __construct($raw_data)
    {
        $this->raw_data = $raw_data;
    }

    protected function process()
    {
        $this->field_index_map = [];
        $this->hash = [];
        $this->fields = [];
        $this->values = [];
        $this->placeholders = [];

        $i=0;
        foreach ($this->raw_data as $raw_field => $value)
        {
            // Skip empty id
            if ($raw_field == 'id' and empty($value))
                continue;
            
            if (is_numeric($raw_field))
                $raw_field = "f" . $raw_field;

            $field = self::sanitizeColumnOrTable($raw_field);
            $placeholder = ":" . $field;

            $this->field_index_map[$raw_field] = $i;
            $this->hash[$placeholder]= $value;
            $this->fields[$i]= $field;
            $this->values[$i]= $value;
            $this->placeholders[$i]= $placeholder;

            $i++;
        }
    }

    // Get Hash
    function hash()
    {
        if (is_null($this->hash))
            $this->process();
        return $this->hash;
    }

    // Get Fields
    function fields()
    {
        if (is_null($this->fields))
            $this->process();
        return $this->fields;
    }

    // Get Values
    function values()
    {
        if (is_null($this->values))
            $this->process();
        return $this->values;
    }

    // Get Placeholders
    function placeholders()
    {
        if (is_null($this->placeholders))
            $this->process();
        return $this->placeholders;
    }

    static function sanitizeColumnOrTable($name)
    {
        if (preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $name))
            return $name;
        else
            self::error('Invalid db field/table name - ' . $name, 'database');
    }
}
