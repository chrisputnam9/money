<?php
namespace MCPI;

/**
 * Transaction Model
 */
class Transaction_Model extends Core_Model_Dbo
{
    static $fields = [
        'amount',
        'date_occurred',
        'image',
    ];

    static $references = [
        'status'=>null,
        'classification'=>['transaction_classification','title'],
        'category'=>['transaction_category','title'],
        'account_from'=>['account','title'],
        'account_to'=>['account','title'],
    ];
}
