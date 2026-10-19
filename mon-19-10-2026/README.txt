OOPSCRUD - PDO VERSION

FILES
- database.sql: creates the oopscrud database and tblusers table.
- config/db.php: PDO connection.
- function.php: DB_con class containing CRUD methods.
- index.php: create, list, and delete users.
- update.php: edit an existing user.

SETUP (XAMPP)
1. Start Apache and MySQL.
2. Open phpMyAdmin.
3. Import database.sql, or run its SQL in the SQL tab.
   If tblusers already exists and contains data, back it up first and inspect its structure before changing it.
4. Put the oopscrud_pdo folder inside your htdocs directory.
5. Check config/db.php matches your MySQL host, database, username, and password.
6. Open http://localhost/oopscrud_pdo/index.php

NOTES
- This version uses PDO prepared statements.
- Deletion is permanent, matching the original project behavior.
- Delete uses POST rather than a GET link.
- CSRF tokens are used for form submissions.
- Database errors are written to the PHP error log rather than shown to visitors.
- The contact number is stored as text because it is an identifier, not a quantity.
- Bootstrap is loaded from a CDN, so internet access is needed for styling.
