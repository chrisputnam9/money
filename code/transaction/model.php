<?php
namespace MCPI;

/**
 * Transaction Model
 */
class Transaction_Model extends Core_Model_Dbo
{
    protected static $table = 'transaction';

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
                $account_options,
                empty($data['account_to']) ? false : $data['account_to']
            ),
            'category_options' => self::populateSelectedOptions(
                self::getAll('transaction_category'),
                empty($data['category']) ? false : $data['category']
            ),
        ];
    }

}
