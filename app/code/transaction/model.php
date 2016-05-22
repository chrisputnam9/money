<?php
namespace MCPI;

/**
 * Transaction Model
 */
class Transaction_Model extends Core_Model_Dbo
{
    protected static $table = 'transaction';

    // Listing Data
    static function getListing()
    {
        $data = self::getAllFlat();
        foreach ($data as &$row)
        {
            $row['amount_formatted'] = '$' . number_format($row['amount'], 2);
            $row['date_occurred_formatted'] = date('m/d/y', strtotime($row['date_occurred']));
        }

        return $data;
    }

    // Get all data, flattened via joins
    static function getAllFlat()
    {
        $table = self::cleanTable();
        $sql = 'SELECT t.*'
                . ', af.title as account_from_value'
                . ', at.title as account_to_value'
                . ', cat.title as category_value'
                . ', class.title as classification_value'
                . ', s.title as status_value'
            . ' FROM ' . $table . ' t'
            . ' LEFT JOIN account af ON (t.account_from = af.id)'
            . ' LEFT JOIN account at ON (t.account_to = at.id)'
            . ' LEFT JOIN transaction_category cat ON (t.category = cat.id)'
            . ' LEFT JOIN transaction_classification class ON (t.classification = class.id)'
            . ' LEFT JOIN transaction_status s ON (t.status = s.id)'
            . ' ORDER BY t.date_occurred DESC'
        ;
        return self::get($sql);
    }

    // Get options for linked tables
    static function getOptions($data)
    {
        $request = self::getRequest();

        $account_options = Account_Model::getGroupedAccounts();

        return [
            'classification_options' => self::populateSelectedOptions(
                self::getAll('transaction_classification'),
                empty($data['classification']) ? false : $data['classification']
            ),
            'account_from_options' => self::populateSelectedOptions(
                $account_options,
                empty($data['account_from']) ? false : $data['account_from']
            ),
            'account_to_options' => self::populateSelectedOptions(
                array_reverse($account_options),
                empty($data['account_to']) ? false : $data['account_to']
            ),
            'category_options' => self::populateSelectedOptions(
                self::getAll('transaction_category', 'title'),
                empty($data['category']) ? false : $data['category']
            ),
        ];
    }

}
