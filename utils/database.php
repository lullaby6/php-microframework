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

    function create_table($table_name, $columns) {
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

    function get_table_columns($table_name) {
        if (empty($table_name)) {
            throw new Exception('Table name is required.');
        }

        $sql = "SHOW COLUMNS FROM $table_name";

        try {
            $result = $this->query($sql);
            return $result->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            throw new Exception('Error while getting table columns: ' . $e->getMessage());
        }
    }

    function select($table_name, $query_info) {
        $where = [];
        $where_string = "";
        $columns = "*";
        $order_by = [];
        $order_by_string = "";
        $group_by = [];
        $group_by_string = "";
        $join = [];
        $join_string = "";
        $limit = [];
        $limit_string = "";

        if (isset($query_info['columns'])) $columns = $query_info['columns'];

        if (isset($query_info['join'])) $join = $query_info['join'];

        if (isset($query_info['where'])) $where = $query_info['where'];

        if (isset($query_info['order_by'])) $order_by = $query_info['order_by'];

        if (isset($query_info['group_by'])) $group_by = $query_info['group_by'];

        if (isset($query_info['limit'])) $limit = $query_info['limit'];

        $params = [];

        if (!empty($join)) {
            if (str_contains($columns, ",")){
                $columns_trimmed = str_replace(' ', '', $columns);
                $columns_array = explode(",", $columns_trimmed);
                $columns_list = [];
                foreach ($columns_array as $column) {
                    $columns_list[] = "$table_name.$column";
                }
                $columns = implode(", ", $columns_list);
            }else {
                $columns = "$table_name.*";
            }

            $join_strings = [];
            $join_columns_string = [];

            foreach ($join as $join_item) {
                if (!isset($join_item['type'])) $join_item['type'] = 'INNER';

                if (!isset($join_item['columns'])) $join_item['columns'] = '*';

                $join_item_table_name = $join_item['table_name'];
                $join_item_columns = $join_item['columns'];
                $join_item_type = strtoupper($join_item['type']);
                $join_item_on = $join_item['on'];
                $join_strings[] = " $join_item_type JOIN $join_item_table_name ON $join_item_on";

                if (str_contains($join_item_columns, ",")){
                    $join_item_columns_trimmed = str_replace(' ', '', $join_item_columns);
                    $join_item_columns_array = explode(",", $join_item_columns_trimmed);
                    foreach ($join_item_columns_array as $join_item_column) {
                        $join_columns_string[] = "$join_item_table_name.$join_item_column";
                    }
                }else {
                    $join_columns_string[] = "$join_item_table_name.*";
                }
            }

            $join_string = implode(" ", $join_strings);
            $columns .= ", " . implode(", ", $join_columns_string);
        }

        if (!empty($where)) {
            $where_strings = [];

            foreach ($where as $index=>$where_item) {
                list($column, $operator, $value) = $where_item;
                $where_strings[] = "$column $operator :$index";
                $params[":$index"] = $value;
            }

            $where_string = " WHERE " . implode(" AND ", $where_strings);
        }

        if (!empty($group_by)) $group_by_string = " GROUP BY $group_by";

        if (!empty($order_by)) $order_by_string = " ORDER BY $order_by";

        if (!empty($limit)) $limit_string = " LIMIT $limit";

        $sql = "SELECT $columns FROM $table_name" . (!empty($join) ? " $join_string" : "") . (!empty($where) ? " $where_string" : "") . (!empty($order_by) ? " $order_by_string" : "") . (!empty($group_by) ? " $group_by_string" : "") . (!empty($limit) ? " $limit_string" : "") . ";";

        // echo $sql;

        $stmt = $this->execute($sql, $params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function create($table_name, $data) {
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

    function update($table_name, $data, $where) {
        $set = [];
        $params = $data;

        foreach ($data as $column => $value) {
            $set[] = "$column = :$column";
        }

        $sql = "UPDATE $table_name SET " . implode(", ", $set);

        if (!empty($where)) {
            $sql .= " WHERE ";
            $where_strings = [];

            foreach ($where as $index=>$where_item) {
                list($column, $operator, $value) = $where_item;
                $where_strings[] = "$column $operator :$index";
                $params[":$index"] = $value;
            }

            $sql .= implode(" AND ", $where_strings);
        }

        return $this->execute($sql, $params);
    }

    function delete($table_name, $where) {
        $sql = "DELETE FROM $table_name";
        $params = [];

        if (!empty($where)) {
            $sql .= " WHERE ";
            $where_strings = [];

            foreach ($where as $index=>$where_item) {
                list($column, $operator, $value) = $where_item;
                $where_strings[] = "$column $operator :$index";
                $params[":$index"] = $value;
            }

            $sql .= implode(" AND ", $where_strings);
        }

        return $this->execute($sql, $params);
    }

    function close() {
        $this->connection = null;
    }
}

?>