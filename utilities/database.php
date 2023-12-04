<?php

class Database {
    public $driver;
    public $host;
    public $database;
    public $username;
    public $password;
    private $connection = null;

    function __construct($db) {
        $this->driver = $db['driver'];
        $this->host = $db['host'];
        $this->database = $db['database'];
        $this->username = $db['username'];
        $this->password = $db['password'];

        $this->connect();
    }

    function connect($db) {
        try {
            $dsn = "$this->driver:host=$this->host;dbname=$this->database";
            $this->connection = new PDO($dsn, $this->username, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new Exception('Failed to connect to the database: ' . $e->getMessage());
        }
    }

    function query($sql) {
        try {
            $result = $this->connection->query($sql);
            return $result;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
        return false;
    }

    function execute($sql, $params = []) {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    function create_table($table_name, $columns) {
        if (empty($table_name) || empty($columns)) {
            throw new Exception('Table name and columns are required.');
        }

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (";
        $column_definitions = [];

        foreach ($columns as $column_name => $definition) {
            $column_definitions[] = "$column_name $definition";
        }

        $sql .= implode(", ", $column_definitions) . ")";

        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            throw new Exception('Error while creating table: ' . $e->getMessage());
        }
    }

    function delete_table($table_name) {
        if (empty($table_name)) {
            throw new Exception('Table name is required.');
        }

        $sql = "DROP TABLE IF EXISTS $table_name";

        try {
            $this->execute($sql);
            return true;
        } catch (PDOException $e) {
            throw new Exception('Error while deleting table: ' . $e->getMessage());
        }
    }

    function select($table, $conditions = [], $columns = '*', $limit = null, $extra = null) {
        $sql = "SELECT " . $columns . " FROM $table";
        $params = [];

        if (!empty($conditions)) {
            $sql .= " WHERE ";
            $where = [];

            foreach ($conditions as $index=>$condition) {
                list($column, $operator, $value) = $condition;
                $where[] = "$column $operator :$index";
                $params[":$index"] = $value;
            }

            $sql .= implode(" AND ", $where);
        }

        $sql .=  ($extra ? " $extra" : '') . ($limit ? " LIMIT $limit" : '') . ';';

        $stmt = $this->execute($sql, $params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function create($table, $data) {
        $columns = implode(", ", array_keys($data));
        $values = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO $table ($columns) VALUES ($values)";
        $params = [];
        foreach ($data as $key => $value) {
            $params[":$key"] = $value;
        }

        $this->execute($sql, $params);
        return $this->connection->lastInsertId();
    }

    function update($table, $data, $conditions) {
        $set = [];
        $params = $data;

        foreach ($data as $column => $value) {
            $set[] = "$column = :$column";
        }

        $sql = "UPDATE $table SET " . implode(", ", $set);

        if (!empty($conditions)) {
            $sql .= " WHERE ";
            $where = [];

            foreach ($conditions as $index=>$condition) {
                list($column, $operator, $value) = $condition;
                $where[] = "$column $operator :$index";
                $params[":$index"] = $value;
            }

            $sql .= implode(" AND ", $where);
        }

        return $this->execute($sql, $params);
    }

    function delete($table, $conditions) {
        $sql = "DELETE FROM $table WHERE ";
        $where = [];
        $params = [];

        if (!empty($conditions)) {
            $sql .= " WHERE ";
            $where = [];

            foreach ($conditions as $index=>$condition) {
                list($column, $operator, $value) = $condition;
                $where[] = "$column $operator :$index";
                $params[":$index"] = $value;
            }

            $sql .= implode(" AND ", $where);
        }

        $sql .= implode(" AND ", $where);

        return $this->execute($sql, $params);
    }

    function close() {
        $this->connection = null;
    }
}

?>