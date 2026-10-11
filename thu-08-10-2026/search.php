<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php 
    $keyname = trim($_GET["q"] ?? '');
?>
<form method="get">
    <label for="q">Search</label>
    <input type="text" id="q" name="q" value="<?= htmlspecialchars($keyname)?>">
    <button type="submit">Go</button>
</form>
<?php if ($keyname !== ""): ?>
    <p>Search result for: <?= htmlspecialchars($keyname)?></p>
    <?php endif; ?>
</body>
</html>
