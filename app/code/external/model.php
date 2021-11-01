<?php
namespace MCPI;

/**
 * Account Model
 */
class External_Model extends Core_Model_Dbo
{
    const BANK_ACCOUNT = 1;
    const CREDIT_CARD = 2;
    const OTHER = 3;

    protected static $table = 'external_item';
}
