<?php
require __DIR__ ."/config/db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit("Invalid request method.");
}
$id = filter_input( INPUT_POST, "id", FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);

if ($id === false || $id === null) {
    exit("Invalid category ID.");
}
$stmt = $pdo->prepare("SELECT id FROM categories WHERE id = ?");
$stmt->execute([$id]);
$category = $stmt->fetch();

if (!$category) {
    exit("Category not found.");
}

$stmt = $pdo->prepare("SELECT id FROM products WHERE category_id = ? LIMIT 1");
$stmt->execute([$id]);
$product = $stmt->fetch();

if ($product) {
    exit("Cannot delete this category because it has products.");
}

$deleteid = "DELETE FROM `categories` WHERE id =?";
$stmt= $pdo->prepare($deleteid);    
$stmt->execute([$id]);

header("Location: categories.php");
exit;