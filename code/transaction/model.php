<?php
namespace MCPI;

/**
 * Transaction Model
 */
class Transaction_Model extends Core_Model_Dbo
{
    protected $table = 'transaction';

    // Get options for linked tables
    public function getOptions()
    {
        return [
            'classification_options' => $this->getAll('transaction_classification'),
            'account_options' => $this->getGroupedAccounts(),
            'category_options' => $this->getAll('transaction_category'),
        ];
    }

    // Get grouped array of accounts
    public function getGroupedAccounts()
    {
        $grouped_accounts = [];
        $accounts = $this->get("
            SELECT a.*, c.title as classification
            FROM account a
            LEFT JOIN account_classification c ON (a.classification = c.id)
        ");
        foreach ($accounts as $account)
        {
            $group = $account['classification'];
            if (!isset($grouped_accounts[$group]))
            {
                $grouped_accounts[$group] = [
                    'group' => $group,
                    'options' => [],
                ];
            }
            $grouped_accounts[$group]['options'][]= $account;
        }
        return array_values($grouped_accounts);
    }

}
