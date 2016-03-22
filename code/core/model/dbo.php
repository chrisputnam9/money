<?php
namespace MCPI;

/**
 * Core Model Database Object
 */
class Core_Model_Dbo extends Core_Model_Abstract
{
    // Expect all objects to have these fields at minimum
    protected static $core_fields = [
        'id',
        'date_created',
        'date_updated',
    ];

    // Other fields
    public static $fields = [];

    // References
    // Key=>Value
    // To Create on demand, include table and main field
    public static $references = [];

    // Data for this instance
    protected $data = [];

    // Constructor
    // $data could be:
    // data - set each field accordingly
    public function __construct($data=[], $value='')
    {
        if (is_array($data) and !empty($data))
            $this->set($data);
    }

    // Load
    // pass one of:
    // - int - load by id
    // - field, value - load where field = value
    // - array of conditions
    public function load($data, $value='')
    {
    }
    
    // Getter
    public function get($field)
    {
    }

    // Setter
    // key, value
    // or array of key=>value
    public function set($data=[], $value='')
    {
        if (is_string($data))
            $data = [$data => $value];

        if (!is_array($data))
            die('Bad variable type for field/data');

        foreach ($data as $field => $value)
        {
            if ($this->has_field($field))
            {
                $this->data[$field] = $value;
            }
        }
    }

    // Check if field is valid
    protected function has_field($field)
    {
        return (
            in_array($field, static::core_fields)
            or in_array($field, static::fields)
            or isset(static::references[$field])
        );
    }

}
