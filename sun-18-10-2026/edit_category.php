<?php
require __DIR__ . "/config/db.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if ($id === false || $id === null || $id < 1) {
    die("Invalid category ID.");
}