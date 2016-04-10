<?php
namespace MCPI;

/**
 * Account Model
 */
class Account_Model extends Core_Model_Dbo
{
    const BANK_ACCOUNT = 1;
    const CREDIT_CARD = 2;
    const OTHER = 3;

    protected static $table = 'account';

    // Get grouped array of accounts
    static function getGroupedAccounts()
    {
        $grouped_accounts = [];

        $sql = "SELECT a.*, c.title as classification"
             . " FROM account a"
             . " LEFT JOIN account_classification c ON (a.classification = c.id)"
        ;

        $accounts = self::get($sql);

        foreach ($accounts as $id => $account)
        {
            $group = $account['classification'];
            if (!isset($grouped_accounts[$group]))
            {
                $grouped_accounts[$group] = [
                    'group' => $group,
                    'options' => [],
                ];
            }
            $grouped_accounts[$group]['options'][$id]= $account;
        }

        ksort($grouped_accounts);
        return array_values($grouped_accounts);
    }

}
