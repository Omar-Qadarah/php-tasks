<?php
session_start();
require_once __DIR__ . "/function.php";

$usersModel = new DB_con($pdo);
$errors = [];

function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}

if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

$userid = filter_var($_GET["id"] ?? $_POST["id"] ?? null, FILTER_VALIDATE_INT);

if (!$userid || $userid < 1) {
    http_response_code(400);
    exit("Invalid user ID.");
}

$user = $usersModel->fetchonerecord($userid);

if (!$user) {
    http_response_code(404);
    exit("User not found.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update"])) {
    $token = $_POST["csrf_token"] ?? "";

    if (!hash_equals($_SESSION["csrf_token"], $token)) {
        $errors[] = "Invalid request token. Please refresh the page and try again.";
    } else {
        $fname = trim($_POST["firstname"] ?? "");
        $lname = trim($_POST["lastname"] ?? "");
        $emailid = trim($_POST["emailid"] ?? "");
        $contactno = trim($_POST["contactno"] ?? "");
        $address = trim($_POST["address"] ?? "");

        if ($fname === "" || $lname === "" || $emailid === "" || $contactno === "" || $address === "") {
            $errors[] = "Please complete all fields.";
        } elseif (!filter_var($emailid, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Please enter a valid email address.";
        } elseif (!preg_match('/^[0-9]{1,11}$/', $contactno)) {
            $errors[] = "Contact number must contain 1 to 11 digits.";
        } else {
            try {
                $usersModel->update($fname, $lname, $emailid, $contactno, $address, $userid);
                $_SESSION["success"] = "Record updated successfully.";
                header("Location: index.php");
                exit;
            } catch (PDOException $e) {
                error_log($e->getMessage());
                $errors[] = "Could not update the record.";
            }
        }

        // Keep the submitted values in the form if validation fails.
        $user["FirstName"] = $fname;
        $user["LastName"] = $lname;
        $user["EmailId"] = $emailid;
        $user["ContactNumber"] = $contactno;
        $user["Address"] = $address;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit user - PDO CRUD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5" style="max-width: 850px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Edit user</h1>
        <a href="index.php" class="btn btn-outline-secondary">Back to users</a>
    </div>

    <?php foreach ($errors as $error): ?>
        <div class="alert alert-danger"><?= e($error) ?></div>
    <?php endforeach; ?>

    <div class="card">
        <div class="card-body">
            <form method="post" action="update.php?id=<?= (int)$userid ?>">
                <input type="hidden" name="csrf_token" value="<?= e($_SESSION["csrf_token"]) ?>">
                <input type="hidden" name="id" value="<?= (int)$userid ?>">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="firstname" class="form-label">First name</label>
                        <input id="firstname" type="text" name="firstname" class="form-control" maxlength="150" value="<?= e($user["FirstName"]) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="lastname" class="form-label">Last name</label>
                        <input id="lastname" type="text" name="lastname" class="form-control" maxlength="150" value="<?= e($user["LastName"]) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="emailid" class="form-label">Email</label>
                        <input id="emailid" type="email" name="emailid" class="form-control" maxlength="120" value="<?= e($user["EmailId"]) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="contactno" class="form-label">Contact number</label>
                        <input id="contactno" type="text" name="contactno" class="form-control" maxlength="11" pattern="[0-9]{1,11}" value="<?= e($user["ContactNumber"]) ?>" required>
                    </div>
                    <div class="col-12">
                        <label for="address" class="form-label">Address</label>
                        <textarea id="address" name="address" class="form-control" maxlength="255" rows="3" required><?= e($user["Address"]) ?></textarea>
                    </div>
                    <div class="col-12">
                        <button type="submit" name="update" class="btn btn-primary">Save changes</button>
                        <a href="index.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
