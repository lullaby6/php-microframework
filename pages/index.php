<?php

$todos = ["todo 1", "todo 2", "todo 3"];

?>

<h1>index</h1>

<?php foreach($todos as $todo) {
    include ROOT_PATH . "/components/todo.php";
} ?>

