<?php
$todos_array = [["text" => "todo 1"], ["text" => "todo 2"], ["text" => "todo 3"]];
?>

<h1>index</h1>

<?= render_array_template("todo", $todos_array) ?>