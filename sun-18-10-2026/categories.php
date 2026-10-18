<?php
    require __DIR__ ."/config/db.php";
    $name="";
    $description= "";
    $errors=[];
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $name=trim($_POST["name"] ?? "");
        $description=trim($_POST["description"] ??"");
        if ($name =="") {
            $errors['name'] = "Enter category name please!";
        }else{                
            $stmt= $pdo->prepare("SELECT id FROM categories WHERE name = ?");
            $stmt->execute([$name]);
            if($stmt->fetch()) {
                $errors["name"] = "This category already exists";
            }
        }
        if ($description == "") {
            $errors["description"] = "Describe this category";
        }
        if (empty($errors)) {
            $stmt = $pdo->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
            $stmt->execute([$name, $description]);
            header("Location: categories.php");
            exit;
        }
    }
    $stmt = $pdo->prepare("SELECT `id`, `name`, `description` FROM `categories` ORDER BY id DESC");
    $stmt->execute();
    $categories = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Add Category</h1>
    <form action="" method="post">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" value="<?=htmlspecialchars($name)?>">
        <?php if(isset($errors['name'])): ?>
            <span class="error"><?=htmlspecialchars($errors['name'])?></span>
        <?php endif; ?>
        <label for="description">Description</label>
        <textarea name="description" id="description" ><?=htmlspecialchars($description)?></textarea>
        <?php if(isset($errors['description'])): ?>
            <span class="error"><?=htmlspecialchars($errors['description'])?></span>
        <?php endif; ?>
        <button type="submit">Submit</button>
    </form>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Category</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($categories)): ?>
                <?php foreach($categories as $category): ?>
                    <tr>
                        <td><?= htmlspecialchars($category['id']) ?></td>
                        <td><?= htmlspecialchars($category['name']) ?></td>
                        <td><?= htmlspecialchars($category['description']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="3">No categories found yet.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>