<?php


function select($table, $conditions = [], $columns = ['*']) {
    $sql = "SELECT " . implode(", ", $columns) . " FROM $table";
    $params = [];

    if (!empty($conditions)) {
        $sql .= " WHERE ";
        $where = [];
        foreach ($conditions as $column => $value) {
            $where[] = "$column = :$column";
            $params[":$column"] = $value;
        }
        $sql .= implode(" AND ", $where);
    }

    $stmt = $this->connection->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}