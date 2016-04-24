<?php
namespace MCPI;

/**
 * Core Model Abstract
 */
class Core_Model_Abstract extends Core_Abstract
{

    /* Generic Helper Functions */
    /****************************/

    // given an options hash, set selected based on value
    static function populateSelectedOptions($hash, $selected, $text='selected="selected"')
    {
        $ungrouped = false;
        if (empty($hash[0]) or !is_array($hash[0]))
        {
            $ungrouped = true;
            $hash = [[ 'options' => $hash ]];
        }

        foreach ($hash as &$group)
        {
            foreach ($group['options'] as $key => &$option)
            {
                $option['selected'] = ($key == $selected) ? $text : "";
            }
            $group['options'] = array_values($group['options']);
        }

        if ($ungrouped)
        {
            $hash = $hash[0]['options'];
        }

        return $hash;
    }
    
}
