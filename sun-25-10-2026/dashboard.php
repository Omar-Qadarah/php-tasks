<?php

session_start();
require_once"db.php";
require_once"user.php";

if (!isset($_SESSION["user_id"])) {
    echo "No user session found.";
    exit;
}
$db=(new Database())->connect();
$userModel = new user($db);
$result=$userModel -> getAllUsers();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <header class="topbar">
        <h1>Welcome to dash</h1>
        <a href="logout.php" class="btn">Logout</a>
    </header>

    <main class="container">

        <section class="card">
            <h2>Add New User</h2>

            <form id="createUserForm">
                <div class="form-grid">
                    <div>
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" required>
                    </div>

                    <div>
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>

                    <div>
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required minlength="8">
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit">Create User</button>
                </div>
            </form>

            <p id="formMessage" class="message" role="status"></p>
        </section>

        <section class="card" id="updateCard" hidden>
            <h2>Update User</h2>

            <form id="updateUserForm" hidden>
                <input type="hidden" id="updateUserId">

                <div class="form-grid">
                    <div>
                        <label for="updateName">Name</label>
                        <input type="text" id="updateName" required>
                    </div>

                    <div>
                        <label for="updateEmail">Email</label>
                        <input type="email" id="updateEmail" required>
                    </div>

                    <div>
                        <label for="updatePassword">New Password (optional)</label>
                        <input
                            type="password"
                            id="updatePassword"
                            minlength="8"
                            placeholder="Leave empty to keep current"
                        >
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit">Save Changes</button>
                    <button type="button" id="cancelUpdate" class="btn-secondary">Cancel</button>
                </div>
            </form>

            <p id="updateMessage" class="message" role="status"></p>
        </section>

        <section class="card">
            <h2>Users</h2>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Delete</th>
                            <th>Update</th>
                        </tr>
                    </thead>

                    <tbody id="usersBody"></tbody>
                </table>
            </div>
        </section>

    </main>

    <script src="assets/dashboard.js"></script>
</body>
</html>
