<?php
require __DIR__ . "/config/db.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if ($id === false || $id === null || $id < 1) {
    die("Invalid category ID.");
}


    $query = "SELECT id, name, description FROM categories WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$id]);
    $category=$stmt->fetch();
    if (!$category) {
        exit("Category not found.");
    }
    $name = $category['name'];
    $description = $category['description'];
    $errors = [];
    $success = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = TRIM($_POST['name']);
        $description = TRIM($_POST['description']);
                $post_id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if ($post_id === false) {
            die("Invalid POST ID. It must be a positive integer.");
        } elseif ($post_id !== $id) {
            die("Security mismatch. POST ID does not match the URL ID.");
        }else{
            if ($name == '') {
            $errors['name'] = 'Fill category name';
            }else{
               $query="SELECT id FROM categories WHERE name = ? AND id != ?";
               $stmt = $pdo->prepare($query);
               $stmt->execute([$name, $id]);
               $duplicate=$stmt->fetch();
                if($duplicate) {
                    $errors["name"] = "This category already exists";
                }
            }
            if ($description == '') {
                $errors['description'] = 'Fill category description';
            }
            $post_id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
            if ($post_id === false) {
                die("Invalid POST ID. It must be a positive integer.");
            } elseif ($post_id !== $id) {
                die("Security mismatch. POST ID does not match the URL ID.");
            }
            if (empty($errors)) {
                $query= 'UPDATE categories SET name = ?, description = ? WHERE id = ?';
                $stmt = $pdo->prepare($query);
                $stmt->execute([$name, $description, $id]);
                header('Location: categories.php');
                exit;
            }
    
        }
    

        
    }
    
    // $name = trim($_POST["name"] ?? "");
    // $email = trim($_POST["email"] ?? "");
    // $password = $_POST["password"] ?? "";
    // $update_user = $pdo->update($id,$name,$email,$password);


?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>edit_category</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <h1>Edit Category</h1>
        <form action="" method="POST" class="form" novalidate>

            <!-- Send the ID through POST -->
            <input type="hidden" name="id" value="<?= (int)$id ?>">

            <label for="name">Name:</label>
            <input
                type="text"
                id="name"
                name="name"
                value="<?= htmlspecialchars($name) ?>"
                required
            >
            <?php if(isset($errors['name'])):?>
                <span class="error"><?=htmlspecialchars($errors['name'])?></span>
            <?php endif;?>
            <label for="description">Description:</label>
            <textarea id="description" name="description" required><?= htmlspecialchars($description) ?></textarea>
            <?php if(isset($errors['description'])):?>
                <span class="error"><?=htmlspecialchars($errors['description'])?></span>
            <?php endif;?>
            <div class="wrapper">
                <button type="submit">Save Changes</button>

                <a href="categories.php" class="btn">Cancel</a>
            </div>
        </form>
    </body>
</html>

