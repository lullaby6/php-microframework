<?php

class Database {
    public array $config;
    public string $driver;
    public string $host;
    public string $database;
    public string $username;
    protected string $password;
    private $connection = null;

    function __construct(array $config) {
        $this->driver = $config['driver'];
        $this->host = $config['host'];
        $this->database = $config['database'];
        $this->username = $config['username'];
        $this->password = $config['password'];

        $this->connect();
    }

    public function connect() {
        try {
            $dsn = "$this->driver:host=$this->host;dbname=$this->database";
            $this->connection = new PDO($dsn, $this->username, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new Exception('Failed to connect to the database: ' . $e->getMessage());
        }
    }

    public function query(string $sql) {
        try {
            $result = $this->connection->query($sql);
            return $result;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function execute(string $sql, array $params = []) {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function create_table(string $table_name, array $columns): bool {
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

    public function delete_table(string $table_name): bool {
        $sql = "DROP TABLE IF EXISTS :table_name";

        try {
            $this->execute($sql, [
                ':table_name' => $table_name
            ]);
            return true;
        } catch (PDOException $e) {
            throw new Exception('Error while deleting table: ' . $e->getMessage());
        }
    }

    public function get_table_column_names(string $table_name): array {
        $sql = "SHOW COLUMNS FROM $table_name";

        try {
            $result = $this->query($sql);

            return $result->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            throw new Exception('Error while getting table columns: ' . $e->getMessage());
        }
    }

    public function get_table_columns_detailed(string $table_name): array {
        $sql = "SHOW COLUMNS FROM :table_name";

        try {
            $result = $this->execute($sql, [
                ':table_name' => $table_name
            ]);

            return $result->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception('Error while getting table columns: ' . $e->getMessage());
        }
    }

    public function get_table_column_types(string $table_name): array {
        $sql = "SELECT
                COLUMN_NAME,
                CASE
                    WHEN DATA_TYPE = 'bigint' THEN 'BIGINT'
                    WHEN DATA_TYPE = 'int' THEN 'INT'
                    WHEN DATA_TYPE = 'varchar' THEN 'VARCHAR'
                    ELSE DATA_TYPE
                END AS DATA_TYPE
            FROM
                INFORMATION_SCHEMA.COLUMNS
            WHERE
                TABLE_NAME = :table_name
        ";

        try {
            $result = $this->execute($sql, [
                ':table_name' => $table_name
            ]);

            return $result->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception('Error while getting table columns: ' . $e->getMessage());
        }
    }

    public function get_table_primary_key(string $table_name): string {
        $sql = "SHOW KEYS FROM $table_name WHERE Key_name = 'PRIMARY'";

        try {
            $result = $this->query($sql);

            $row = $result->fetch(PDO::FETCH_ASSOC);

            return $row['Column_name'];
        } catch (PDOException $e) {
            throw new Exception('Error while getting table primary key: ' . $e->getMessage());
        }
    }

    public function select(string $table_name, array $query_info = []): array {
        $columns = isset($query_info['columns']) ? (is_array($query_info['columns']) ? implode(", ", $query_info['columns']) : $query_info['columns']) : "$table_name.*";

        $where = $query_info['where'] ?? [];
        $where_string = "";

        $order_by = $query_info['order_by'] ?? "";
        $order_by_string = "";

        $group_by = $query_info['group_by'] ?? "";
        $group_by_string = "";

        $join = $query_info['join'] ?? [];
        $join_string = "";

        $limit = $query_info['limit'] ?? "";
        $limit_string = "";

        $offset = $query_info['offset'] ?? "";
        $offset_string = "";

        $params = [];

        if (!empty($join)) {
            foreach ($join as $join_table_name => $join_item) {
                if (!isset($join_item['type'])) $join_item['type'] = 'INNER';

                $join_item_type = strtoupper($join_item['type']);
                list($join_item_first, $join_item_operator, $join_item_second) = $join_item['on'];
                $join_string .= " $join_item_type JOIN $join_table_name ON $join_item_first $join_item_operator $join_item_second";

                if (isset($join_item['columns'])) {
                    $join_item_columns = $join_item['columns'];

                    if (is_array($join_item_columns)) {
                        $columns .= ", " . implode(", ", $join_item_columns);
                    } else {
                        $columns .= ", $join_item_columns";
                    }
                }
            }
        }

        if (!empty($where)) {
            $where_string = " WHERE ";

            foreach ($where as $index => $where_item) {
                if (count($where_item) < 4) {
                    $where_item[] = 'AND';
                }

                list($column, $operator, $value, $logic_operator) = $where_item;

                $where_string .= "$column $operator :$index";

                $params[":$index"] = $value;

                if ($index != count($where) - 1) $where_string .= " $logic_operator ";
            }
        }

        if ($group_by !== "") $group_by_string = " GROUP BY $group_by";

        if (!empty($order_by)) {
            $order_by_string = " ORDER BY ";

            $order_by_string .= implode(", ", array_map(function($subarray) {
                return implode(" ", $subarray);
            }, $order_by));
        }

        if ($limit !== "") $limit_string = " LIMIT $limit";

        if ($offset !== "") $limit_string .= " OFFSET $offset";

        $sql = "SELECT $columns FROM $table_name" . (!empty($join) ? " $join_string" : "") . (!empty($where) ? " $where_string" : "") . (!empty($order_by) ? " $order_by_string" : "") . (!empty($group_by) ? " $group_by_string" : "") . (!empty($limit) ? " $limit_string" : "") . (!empty($offset) ? " $offset_string" : "") . ";";

        // echo $sql;
        // var_dump($params);

        try {
            $stmt = $this->execute($sql, $params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Throwable $th) {
            throw new Exception("Failed to select data from table $table_name: " . $th->getMessage());
        }
    }

    public function select_one(string $table_name, array $query_info = []): array {
        try {
            $query_info['limit'] = 1;

            $rows = $this->select($table_name, $query_info);

            if (!empty($rows)) {
                return $rows[0];
            }

            return $rows;
        } catch (\Throwable $th) {
            throw new Exception("Failed to select data from table $table_name: " . $th->getMessage());
        }
    }

    public function select_by_id(string $table_name, string $id, array $query_info = []): array {
        try {
            if (isset($query_info['where'])) {
                $query_info['where'][] = ['id', '=', $id];
            } else {
                $query_info['where'] = [['id', '=', $id]];
            }

            $query_info['limit'] = 1;

            $rows = $this->select($table_name, $query_info);

            if (!empty($rows)) {
                return $rows[0];
            }

            return $rows;
        } catch (\Throwable $th) {
            throw new Exception("Failed to select data by id from table $table_name: " . $th->getMessage());
        }
    }

    public function select_by_primary_key(string $table_name, string $id, array $query_info = []): array {
        try {
            $primary_key = $this->get_table_primary_key($table_name);

            if (isset($query_info['where'])) {
                $query_info['where'][] = [$primary_key, '=', $id];
            } else {
                $query_info['where'] = [[$primary_key, '=', $id]];
            }

            $query_info['limit'] = 1;

            $rows = $this->select($table_name, $query_info);

            if (!empty($rows)) {
                return $rows[0];
            }

            return $rows;
        } catch (\Throwable $th) {
            throw new Exception("Failed to select data by id from table $table_name: " . $th->getMessage());
        }
    }

    function create(string $table_name, array $data): int {
        $columns = implode(", ", array_keys($data));
        $values = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO $table_name ($columns) VALUES ($values)";
        $params = [];
        foreach ($data as $key => $value) {
            $params[":$key"] = $value;
        }

        try {
            $this->execute($sql, $params);
            return $this->connection->lastInsertId();
        } catch (\Throwable $th) {
            throw new Exception("Failed to insert data into table $table_name: " . $th->getMessage());
        }
    }

    public function update(string $table_name, array $data, array $where) {
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

        try {
            return $this->execute($sql, $params);
        } catch (\Throwable $th) {
            throw new Exception("Failed to update data in table $table_name: " . $th->getMessage());
        }
    }

    public function update_by_id(string $table_name, string $id, array $data, array $where = []) {
        try {
            return $this->update($table_name, $data, array_merge($where, [['id', '=', $id]]));
        } catch (\Throwable $th) {
            throw new Exception("Failed to update data by id in table $table_name: " . $th->getMessage());
        }
    }

    public function delete(string $table_name, array $where) {
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

        try {
            return $this->execute($sql, $params);
        } catch (\Throwable $th) {
            throw new Exception("Failed to delete data from table $table_name: " . $th->getMessage());
        }
    }

    public function delete_by_id(string $table_name, string $id, array $where = []) {
        try {
            return $this->delete($table_name, array_merge($where, [['id', '=', $id]]));
        } catch (\Throwable $th) {
            throw new Exception("Failed to delete data from table $table_name: " . $th->getMessage());
        }
    }

    public function transaction(callable $callback) {
        $this->query("START TRANSACTION");

        try {
            $result = $callback();

            $this->query("COMMIT");

            return $result;
        } catch (\Throwable $th) {
            $this->query("ROLLBACK");

            throw $th;
        }
    }

    public function close() {
        $this->connection = null;
    }
}