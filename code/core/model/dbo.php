<?php
namespace MCPI;
use \PDO,\Exception;

/**
 * Core Model Database Object
 */
class Core_Model_Dbo extends PDO
{
    // Consructor, reads config for credentials
    function __construct()
    {
        require_once DIR_CONFIG . 'db.php';
        parent::__construct(
            'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4',
            DB_USER,
            DB_PASS,
            array(
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            )
        );
    }

    // Get Data
    function get($sql, $data=[], $type=PDO::FETCH_ASSOC)
    {
        try {

            $stmt = $this->prepare($sql);
            $stmt->execute($data);
            return $stmt->fetchAll($type);

        } catch (Exception $e) {
            echo "Database Error: ";
            die($e->getMessage());
        }
    }

    // Get everything from a table
    public function getAll($table="")
    {
        if (empty($table))
            $table = $this->table;
        return $this->get('SELECT * FROM ' . $table);
    }
}
