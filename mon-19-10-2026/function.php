<?php
require_once __DIR__ . "/config/db.php";

class DB_con
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function insert($fname, $lname, $emailid, $contactno, $address): bool
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO tblusers
                (FirstName, LastName, EmailId, ContactNumber, Address)
             VALUES (?, ?, ?, ?, ?)"
        );

        return $stmt->execute([
            $fname,
            $lname,
            $emailid,
            $contactno,
            $address
        ]);
    }

    public function fetchdata(): array
    {
        $stmt = $this->pdo->query(
            "SELECT * FROM tblusers ORDER BY id DESC"
        );

        return $stmt->fetchAll();
    }

    public function fetchonerecord(int $userid): array|false
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM tblusers WHERE id = ?"
        );
        $stmt->execute([$userid]);

        return $stmt->fetch();
    }

    public function update(
        $fname,
        $lname,
        $emailid,
        $contactno,
        $address,
        int $userid
    ): bool {
        $stmt = $this->pdo->prepare(
            "UPDATE tblusers
             SET FirstName = ?,
                 LastName = ?,
                 EmailId = ?,
                 ContactNumber = ?,
                 Address = ?
             WHERE id = ?"
        );

        return $stmt->execute([
            $fname,
            $lname,
            $emailid,
            $contactno,
            $address,
            $userid
        ]);
    }

    public function delete(int $rid): bool
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM tblusers WHERE id = ?"
        );

        return $stmt->execute([$rid]);
    }
}
