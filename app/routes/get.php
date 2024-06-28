<?php
layout("layout", ["title" => "Home"]);

$todos_array = [["text" => "todo 1"], ["text" => "todo 2"], ["text" => "todo 3"]];
?>

<h1>Home</h1>

<?= render_array_template("todo", $todos_array) ?>