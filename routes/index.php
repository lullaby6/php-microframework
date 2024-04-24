<?php
$todos = ["todo 1", "todo 2", "todo 3"];
?>

<h1>index</h1>

<?php foreach($todos as $todo) {
    render(TEMPLATES_PATH . "/todo.php", ["todo" => $todo]);
} ?>