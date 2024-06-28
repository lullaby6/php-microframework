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
        $sql = "DROP TABLE IF EXISTS $table_name";

        try {
            $this->execute($sql);
            return true;
        } catch (PDOException $e) {
            throw new Exception('Error while deleting table: ' . $e->getMessage());
        }
    }

    public function get_table_columns(string $table_name): array {
        $sql = "SHOW COLUMNS FROM $table_name";

        try {
            $result = $this->query($sql);
            return $result->fetchAll(PDO::FETCH_COLUMN);
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

    public function select(string $table_name, array $query_info): array {
        $columns = $query_info['columns'] ?? "*";

        $where = $query_info['where'] ?? [];
        $where_string = "";

        $order_by = $query_info['order_by'] ?? [];
        $order_by_string = "";

        $group_by = $query_info['group_by'] ?? [];
        $group_by_string = "";

        $join = $query_info['join'] ?? [];
        $join_string = "";

        $limit = $query_info['limit'] ?? [];
        $limit_string = "";

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

        if (!empty($group_by)) $group_by_string = " GROUP BY $group_by";

        if (!empty($order_by)) $order_by_string = " ORDER BY $order_by";

        if (!empty($limit)) $limit_string = " LIMIT $limit";

        $sql = "SELECT $columns FROM $table_name" . (!empty($join) ? " $join_string" : "") . (!empty($where) ? " $where_string" : "") . (!empty($order_by) ? " $order_by_string" : "") . (!empty($group_by) ? " $group_by_string" : "") . (!empty($limit) ? " $limit_string" : "") . ";";

        // echo $sql;
        // var_dump($params);

        try {
            $stmt = $this->execute($sql, $params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Throwable $th) {
            throw new Exception("Failed to select data from table $table_name: " . $th->getMessage());
        }
    }

    public function select_by_id(string $table_name, string $id, array $query_info): array {
        try {
            if (isset($query_info['where'])) {
                $query_info['where'][] = ['id', '=', $id];
            } else {
                $query_info['where'] = [['id', '=', $id]];
            }
            return $this->select($table_name, $query_info);
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