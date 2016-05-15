<?php
namespace MCPI;
use \PDO,\Exception;

/**
 * Core Model Database Object
 */
class Core_Model_Dbo extends Core_Model_Abstract
{
    protected static $db;
    protected static $statement;

    static function db()
    {
        if (is_null(self::$db))
        {
            require_once DIR_CONFIG . 'db.php';
            self::$db = new PDO(
                'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4',
                DB_USER,
                DB_PASS,
                array(
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                )
            );
        }

        return self::$db;
    }

    // Consructor, reads config for credentials
    function __construct()
    {
    }

    static function lastInsertId()
    {
        return self::db()->lastInsertId();
    }

    // Run query
    static function execute($sql, $data=[])
    {
        try {

            $stmt = self::db()->prepare($sql);
            self::$statement = $stmt;
            return $stmt->execute($data);

        } catch (Exception $e) { self::error($e, 'database'); }
    }

    // Get Data
    static function get($sql, $data=[], $key_column='id')
    {
        try {

            $stmt = self::db()->prepare($sql);
            self::$statement = $stmt;
            $stmt->execute($data);
            $results = [];
            while($row = $stmt->fetch(PDO::FETCH_ASSOC))
            {
                $key = $row[$key_column];
                $results[$key] = $row;
            }
            return $results;

        } catch (Exception $e) { self::error($e, 'database'); }
    }

    // Get everything from a table
    static function getAll($table="")
    {
        $table = self::cleanTable($table);
        return self::get('SELECT * FROM ' . $table);
    }

    // Get by field
    static function getBy($data, $table="")
    {
        $table = self::cleanTable($table);
        $data = new Core_Model_Dbo_Data($data);

        $fields = $data->fields();
        $placeholders = $data->placeholders();
        $conditions = [];
        foreach ($fields as $i => $field)
        {
            $conditions[]= $field . ' = ' . $placeholders[$i];
        }

        $sql = 'SELECT * FROM ' . $table
             . ' WHERE '
             . join(' AND ', $conditions)
        ;
        return self::get($sql, $data->hash());
    }

    // Get by ID
    static function getById($id, $table="")
    {
        return self::getBy(['id'=>$id], $table);
    }

    // Delete by ID(s)
    static function delete($ids, $table="")
    {
        if (!is_array($ids))
        {
            $ids = array($ids);
        }

        $table = self::cleanTable($table);
        $data = new Core_Model_Dbo_Data($ids);

        $sql = 'DELETE FROM ' . $table
             . ' WHERE id IN'
             . ' ('.join(',', $data->placeholders()).')'
        ;

        echo("<pre>".print_r($sql,true)."</pre>");
        echo("<pre>".print_r($data->hash(),true)."</pre>");
        die;

        return self::execute($sql, $data->hash());

    }


    // Save data into table
    static function save($data, $table="", $method='REPLACE')
    {
        $table = self::cleanTable($table);
        $data = new Core_Model_Dbo_Data($data);

        $sql = $method . ' INTO ' . $table
             . ' ('.join(',', $data->fields()).')'
             . ' VALUES'
             . ' ('.join(',', $data->placeholders()).')'
        ;

        return self::execute($sql, $data->hash());
    }

    // Create entry in table
    static function create($data, $table="")
    {
        return self::save($data, $table, 'INSERT');
    }

    // Get clean table name
    static function cleanTable($table=false)
    {
        if (empty($table))
            $table = static::$table;

        return Core_Model_Dbo_Data::sanitizeColumnOrTable($table);
    }
}
