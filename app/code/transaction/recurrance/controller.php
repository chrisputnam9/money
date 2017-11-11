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
    public static function save($transaction_id, $data, $delete_children=false)
    {
        if (!empty($data['type']))
        {
            $type = $data['type'];
            $class = self::getTypeClass($type);
            if (class_exists($class))
            {
                $type_instance = new $class($transaction_id);
                $type_instance->saveRecurringData($data, $delete_children);
                $type_instance->catchup();
            }
        }
    }

    /*
     * Pre delete actions regarding repetition
     * @param transaction_id
     */
    public static function preDelete($ids)
    {
        if (!is_array($ids)) $ids = [$ids];
        foreach ($ids as $id)
        {
            $type_instance = new Transaction_Recurrance_Type_Abstract($id);
            $type_instance->deleteChildren();
        }
    }

    /**
     * Get existing repeat data for form
     */
    public static function getFormData($transaction_id)
    {
        $request = self::getRequest();
        $type_abstract = new Transaction_Recurrance_Type_Abstract($transaction_id);
        $form_data = $type_abstract->getRecurringData();

        if (empty($form_data))
        {
            // Check if this is a child
            $parent = Transaction_Recurrance_Type_Abstract::getParentOf($transaction_id);
            if ($parent)
            {
                $form_data['is_repeat_child'] = true;
                $form_data['parent'] = $parent;
                $form_data['parent_url'] = $request->url(null, [
                    'id' => $parent['main_transaction_id']
                ]);
            }
        }
        else
        {
            $form_data['is_repeat_parent'] = true;
            $form_data['children'] = array_values($type_abstract->getChildren());
            foreach ($form_data['children'] as &$child)
            {
                $child['date_occurred_formatted'] = date('m/d/y', strtotime($child['date_occurred']));
                $child['url'] = $request->url(null, [
                    'id' => $child['id']
                ]);
            }
        }

        return $form_data;
    }

    // Determine class for the given repeat type
    protected static function getTypeClass($type)
    {
        $class = str_replace(' ', '_', ucwords(str_replace('_',' ', $type)));
        $class = 'MCPI\Transaction_Recurrance_Type_' . $class;
        return $class;
    }
}
