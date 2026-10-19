<?php
session_start();
require_once __DIR__ . "/function.php";

$usersModel = new DB_con($pdo);
$errors = [];
$success = $_SESSION["success"] ?? "";
unset($_SESSION["success"]);

function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}

if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST["csrf_token"] ?? "";

    if (!hash_equals($_SESSION["csrf_token"], $token)) {
        $errors[] = "Invalid request token. Please refresh the page and try again.";
    } elseif (isset($_POST["insert"])) {
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
                $usersModel->insert($fname, $lname, $emailid, $contactno, $address);
                $_SESSION["success"] = "Record inserted successfully.";
                header("Location: index.php");
                exit;
            } catch (PDOException $e) {
                error_log($e->getMessage());
                $errors[] = "Could not insert the record. Please check the information and try again.";
            }
        }
    } elseif (isset($_POST["delete"])) {
        $rid = filter_var($_POST["id"] ?? null, FILTER_VALIDATE_INT);

        if (!$rid || $rid < 1) {
            $errors[] = "Invalid user ID.";
        } else {
            try {
                if ($usersModel->delete($rid)) {
                    $_SESSION["success"] = "Record deleted successfully.";
                } else {
                    $_SESSION["success"] = "No record was deleted.";
                }
                header("Location: index.php");
                exit;
            } catch (PDOException $e) {
                error_log($e->getMessage());
                $errors[] = "Could not delete the record.";
            }
        }
    }
}

$users = $usersModel->fetchdata();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Users CRUD - PDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h1 class="mb-4">Users Management</h1>

    <?php if ($success !== ""): ?>
        <div class="alert alert-success"><?= e($success) ?></div>
    <?php endif; ?>

    <?php foreach ($errors as $error): ?>
        <div class="alert alert-danger"><?= e($error) ?></div>
    <?php endforeach; ?>

    <div class="card mb-4">
        <div class="card-header">Add a user</div>
        <div class="card-body">
            <form method="post" action="index.php">
                <input type="hidden" name="csrf_token" value="<?= e($_SESSION["csrf_token"]) ?>">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="firstname" class="form-label">First name</label>
                        <input id="firstname" type="text" name="firstname" class="form-control" maxlength="150" required>
                    </div>
                    <div class="col-md-6">
                        <label for="lastname" class="form-label">Last name</label>
                        <input id="lastname" type="text" name="lastname" class="form-control" maxlength="150" required>
                    </div>
                    <div class="col-md-6">
                        <label for="emailid" class="form-label">Email</label>
                        <input id="emailid" type="email" name="emailid" class="form-control" maxlength="120" required>
                    </div>
                    <div class="col-md-6">
                        <label for="contactno" class="form-label">Contact number</label>
                        <input id="contactno" type="text" name="contactno" class="form-control" maxlength="11" pattern="[0-9]{1,11}" required>
                    </div>
                    <div class="col-12">
                        <label for="address" class="form-label">Address</label>
                        <textarea id="address" name="address" class="form-control" maxlength="255" rows="3" required></textarea>
                    </div>
                    <div class="col-12">
                        <button type="submit" name="insert" class="btn btn-primary">Add user</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Registered users</div>
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0 align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>First name</th>
                        <th>Last name</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>Address</th>
                        <th>Posting date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!$users): ?>
                    <tr><td colspan="8" class="text-center py-4">No users found.</td></tr>
                <?php else: ?>
                    <?php foreach ($users as $index => $user): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= e($user["FirstName"]) ?></td>
                            <td><?= e($user["LastName"]) ?></td>
                            <td><?= e($user["EmailId"]) ?></td>
                            <td><?= e($user["ContactNumber"]) ?></td>
                            <td><?= e($user["Address"]) ?></td>
                            <td><?= e($user["PostingDate"]) ?></td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a class="btn btn-sm btn-outline-primary" href="update.php?id=<?= (int)$user["id"] ?>">Edit</a>
                                    <form method="post" action="index.php" onsubmit="return confirm('Do you really want to delete this record?');">
                                        <input type="hidden" name="csrf_token" value="<?= e($_SESSION["csrf_token"]) ?>">
                                        <input type="hidden" name="id" value="<?= (int)$user["id"] ?>">
                                        <button type="submit" name="delete" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
