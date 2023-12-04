<?php

class Database {
    private $connection = null;

    function __construct($db_type, $db_host, $db_name, $db_user, $db_pass) {
        $this->connect($db_type, $db_host, $db_name, $db_user, $db_pass);
    }

    function connect($db_type, $db_host, $db_name, $db_user, $db_pass) {
        try {
            $dsn = "$db_type:host=$db_host;dbname=$db_name";
            $this->connection = new PDO($dsn, $db_user, $db_pass);
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

        $sql .= implode(", ", $column_definitions);
        $sql .= ")";

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

        echo $sql;
        echo print_r($params);

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

// example:
// $db = new Database('mysql', 'localhost', 'my_database', 'username', 'password');

// create_table:
// $users_table = [
//     'name' => 'users',
//     'columns' => [
//         'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
//         'name' => 'VARCHAR(50)',
//         'email' => 'VARCHAR(100)'
//     ]
// ];
// $result = $db->create_table($users_table['name'], $users_table['columns']);
// if ($result) {
//     echo $users_table['name'] . " created successfully.";
// }


// delete_table:
// $result = $db->delete_table('users');

// query:
// $sql = "SELECT * FROM users";
// $users = $db->query($sql);
// if ($users) {
//     foreach ($users as $user) {
//         echo "name: {$user['name']}, email: {$user['email']}<br>";
//     }
// }

// execute:
// $sql = 'SELECT * FROM users WHERE id > :id';
// $users = $db->query($sql, [':id' => 1]);

// foreach ($users as $user) {
//     // Procesar los resultados
// }

// select
// $conditions = [['id', '=', 1]];
// $users = $db->select('users', $conditions);

// foreach ($users as $user) {
//     echo "name: {$user['name']}, email: {$user['email']}<br>";
// }

// create:
// $user = ['name' => 'Lullaby', 'email' => 'lucianobrumer5@gmail.com'];
// $new_user_id = $db->create('users', $user);

// if ($new_user_id) {
//     echo "new user inserted with ID: $new_user_id";
// }

// update:
// $data = ['email' => 'lucianobrumer5@gmail.com2'];
// $conditions = [['id', '=', 1]];
// $user_updated = $db->update('users', $data, $conditions);

// if ($user_updated) {
//     echo "user updated successfully.";
// }

// delete:
// $user_deleted = $db->delete('users', ['id' => 2]);

// if ($user_deleted) {
//     echo "user deleted successfully.";
// }

?>