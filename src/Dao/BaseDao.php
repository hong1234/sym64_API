<?php
namespace App\Dao;

use Doctrine\DBAL\Connection;

class BaseDao {
    private $connection;

    function __construct(Connection $connection) {
        $this->connection = $connection;
    }

    public function doQuery($sql, $parr=[]) {

        $stmt = $this->connection->prepare($sql);  // Doctrine\DBAL\Statement
        
        foreach ($parr as $key => $value) {
            $stmt->bindValue("{$key}", $value);
        }
        $result = $stmt->executeQuery();
        return $result;
    }

    public function doSQL($sql, $parr=[]) {
        $stmt = $this->connection->prepare($sql);
        $rowCount = $stmt->executeStatement($parr); // returns the affected rows count
        return $rowCount;
    }
}