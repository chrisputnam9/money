<?php
namespace MCPI;

/**
 * Transaction Recurrane Controller
 */
class Transaction_Recurrance_Controller extends Core_Controller_Abstract
{
    /*
     * Save recurrance for a transaction
     * @param transaction_id
     * @param data - array
     *  repeat_type
     *  keys for options (fully passed to type instance)
     */
    public static function save($transaction_id, $data)
    {
        if (!empty($data['type']))
        {
            $type = $data['type'];
            $class = self::getTypeClass($type);
            if (class_exists($class))
            {
                $type_instance = new $class($transaction_id, $data);
                $type_instance->update();
                $type_instance->catchup();
            }
        }
    }

    // Determine class for the given repeat type
    protected static function getTypeClass($type)
    {
        $class = str_replace(' ', '_', ucwords(str_replace(' ','_', $type)));
        $class = 'MCPI\Transaction_Recurrance_Type_' . $class;
        return $class;
    }
}
