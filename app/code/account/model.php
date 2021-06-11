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

        $sql = "SELECT a.*, c.title as classification,"
             . " ("
             . "    SELECT t.category"
             . "    FROM transaction t"
             . "    WHERE t.account_to = a.id"
             . "    GROUP BY t.category"
             . "    ORDER BY count(t.category) DESC"
             . "    LIMIT 1"
             . " ) AS popular_category,"
             . " ("
             . "    SELECT t2.account_from"
             . "    FROM transaction t2"
             . "    WHERE t2.account_to = a.id"
             . "    GROUP BY t2.account_from"
             . "    ORDER BY count(t2.account_from) DESC"
             . "    LIMIT 1"
             . " ) AS popular_account_from"
             . " FROM account a"
             . " LEFT JOIN account_classification c ON (a.classification = c.id)"
             . " GROUP BY a.id"
             . " ORDER BY a.title"
        ;

        $accounts = self::get($sql);

        foreach ($accounts as $id => $account)
        {
            // Card Replacement - PNC Old => New
            if ($account['popular_account_from'] == 4)
            {
                $account['popular_account_from'] = 213;
            }
            
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

    // Get map of account numbers to account IDs
    static function getNumberMap()
    {
        $sql = "SELECT a.id,a.account_number FROM account a WHERE a.account_number <> ''";
        return self::get($sql, null, 'account_number');
    }

}
