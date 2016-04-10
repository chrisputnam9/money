<?php
namespace MCPI;

/**
 * Transaction Model
 */
class Transaction_Model extends Core_Model_Dbo
{
    protected static $table = 'transaction';

    // Get options for linked tables
    static function getOptions()
    {
        $request = self::getRequest();

        $account_options = Account_Model::getGroupedAccounts();

        return [
            'classification_options' => self::populateSelectedOptions(
                self::getAll('transaction_classification'),
                $request->post('classification')
            ),
            'account_from_options' => self::populateSelectedOptions(
                $account_options,
                $request->post('account_from')
            ),
            'account_to_options' => self::populateSelectedOptions(
                $account_options,
                $request->post('account_to')
            ),
            'category_options' => self::populateSelectedOptions(
                self::getAll('transaction_category'),
                $request->post('category')
            ),
        ];
    }

}
