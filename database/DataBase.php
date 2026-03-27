<?php

namespace database;

use PDO;
use PDOException;

class DataBase
{
    private static $instance = null;
    private $connection;
    private $options = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        PDO::ATTR_PERSISTENT => true
    );
    private $dbHost = DB_HOST;
    private $dbUserName = DB_USERNAME;
    private $dbName = DB_NAME;
    private $dbPassword = DB_PASSWORD;

    private function __construct()
    {
        try {
            $this->connection = new PDO("mysql:host=" . $this->dbHost . ";dbname=" . $this->dbName, $this->dbUserName, $this->dbPassword, $this->options);
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }

    //Singleton
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new DataBase();
        }
        return self::$instance;
    }

    public function lastInsertId()
    {
        return $this->connection->lastInsertId();
    }

    public function select($sql, $values = null)
    {
        try {
            $stmt = $this->connection->prepare($sql);
            if ($values == null) {
                $stmt->execute();
            } else {
                $stmt->execute($values);
            }
            return $stmt;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function getLastAutoIncrementId($tableName)
    {
        try {
            $sql = "SHOW TABLE STATUS LIKE ?";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([$tableName]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                return $result['Auto_increment'];
            } else {
                return null;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function getLastId($tableName)
    {
        try {
            $sql = "SELECT AUTO_INCREMENT FROM information_schema.tables WHERE table_name = ?";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([$tableName]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                return $result['AUTO_INCREMENT'] - 1;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    // public function insert($tableName, $fields, $values)
    // {
    //     try {
    //         $stmt = $this->connection->prepare("INSERT INTO " . $tableName . "(" . implode(', ', $fields) . " , created_at) VALUES ( :" . implode(', :', $fields) . " , now() );");
    //         $stmt->execute(array_combine($fields, $values));
    //         return true;
    //     } catch (PDOException $e) {
    //         echo $e->getMessage();
    //         return false;
    //     }
    // }
    public function insert($tableName, $fields, $values)
    {
        $stmt = $this->connection->prepare(
            "INSERT INTO " . $tableName . "(" . implode(', ', $fields) . " , created_at) 
         VALUES ( :" . implode(', :', $fields) . " , now() );"
        );
        if (!$stmt->execute(array_combine($fields, $values))) {
            throw new \Exception("Insert into {$tableName} failed");
        }
        return true;
    }

    public function update($tableName, $id, $fields, $values)
    {
        $sql = "UPDATE " . $tableName . " SET";
        foreach (array_combine($fields, $values) as $field => $value) {
            if ($value) {
                $sql .= " `" . $field . "` = ? ,";
            } else {
                $sql .= " `" . $field . "` = NULL ,";
            }
        }

        $sql .= " updated_at = now()";
        $sql .= " WHERE id = ?";
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute(array_merge(array_filter(array_values($values)), [$id]));
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    // update scores
    public function updateScore($tableName, $id, $fields, $values)
    {
        $sql = "UPDATE " . $tableName . " SET";
        foreach (array_combine($fields, $values) as $field => $value) {
            if (empty($value) && $value !== "0") {
                // مقدار خالی تبدیل به 0.00 می‌شود
                $value = 0.00;
            }
            $sql .= " `" . $field . "` = ? ,";
        }

        $sql .= " updated_at = now()";
        $sql .= " WHERE id = ?";
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute(array_merge(array_values($values), [$id]));
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }


    public function delete($tableName, $id)
    {
        $sql = "DELETE FROM " . $tableName . " WHERE id = ? ;";
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([$id]);
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function deleteWhere($tableName, $column, $value)
    {
        $sql = "DELETE FROM " . $tableName . " WHERE " . $column . " = ?";

        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([$value]);
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function createTable($sql)
    {
        try {
            $this->connection->exec($sql);
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function beginTransaction()
    {
        return $this->connection->beginTransaction();
    }

    public function commit()
    {
        return $this->connection->commit();
    }

    public function rollBack()
    {
        return $this->connection->rollBack();
    }
}
