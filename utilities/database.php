<?php

class Database {
    public $driver;
    public $host;
    public $database;
    public $username;
    public $password;
    private $connection = null;

    function __construct($db_info) {
        $this->driver = $db_info['driver'];
        $this->host = $db_info['host'];
        $this->database = $db_info['database'];
        $this->username = $db_info['username'];
        $this->password = $db_info['password'];

        $this->connect();
    }

    function connect() {
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

    function create_table($table_info) {
        $table_name = $table_info['table_name'];
        $columns = $table_info['columns'];

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

    function select($query_info) {
        $table_name = $query_info['table_name'];
        $conditions;
        $columns = "*";
        $orders;
        $groups;
        $limit;

        if (isset($query_info['conditions'])) {
            $conditions = $query_info['conditions'];
        }

        if (isset($query_info['columns'])) {
            $columns = $query_info['columns'];
        }
        
        if (isset($query_info['order'])) {
            $orders = $query_info['order'];
        }

        if (isset($query_info['group'])) {
            $groups = $query_info['group'];
        }

        if (isset($query_info['limit'])) {
            $limit = $query_info['limit'];
        }

        $sql = "SELECT " . $columns . " FROM $table_name";
        $params = [];

        if (!empty($conditions)) {
            $where = [];

            foreach ($conditions as $index=>$condition) {
                list($column, $operator, $value) = $condition;
                $where[] = "$column $operator :$index";
                $params[":$index"] = $value;
            }

            $sql .= " WHERE " . implode(" AND ", $where);
        }

        if (!empty($groups)) {
            $sql .= " GROUP BY " . implode(", ", $groups);
        }

        if (!empty($orders)) {
            $sql .= " ORDER BY " . implode(", ", $orders);
        }

        if (!empty($limit)) {
            $sql .= " LIMIT $limit";
        }

        echo $sql;

        $stmt = $this->execute($sql, $params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function create($query_info) {
        $table_name = $query_info['table_name'];
        $data = $query_info['data'];

        $columns = implode(", ", array_keys($data));
        $values = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO $table_name ($columns) VALUES ($values)";
        $params = [];
        foreach ($data as $key => $value) {
            $params[":$key"] = $value;
        }

        $this->execute($sql, $params);
        return $this->connection->lastInsertId();
    }

    function update($query_info) {
        $table_name = $query_info['table_name'];
        $data = $query_info['data'];
        $conditions;

        if (isset($query_info['conditions'])) {
            $conditions = $query_info['conditions'];
        }

        $set = [];
        $params = $data;

        foreach ($data as $column => $value) {
            $set[] = "$column = :$column";
        }

        $sql = "UPDATE $table_name SET " . implode(", ", $set);

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

    function delete($query_info) {
        $table_name = $query_info['table_name'];
        $conditions;

        if (isset($query_info['conditions'])) {
            $conditions = $query_info['conditions'];
        }

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