<?php
class AdminUser {
    private $pdo;
    public function __construct($pdo) { $this->pdo = $pdo; }

    public function findByUsername($username) {
        $stmt = $this->pdo->prepare("SELECT * FROM admin_users WHERE username=?");
        $stmt->execute([$username]);
        return $stmt->fetch();
    }

    public function create($username, $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO admin_users (username,password) VALUES (?,?)");
        $stmt->execute([$username, $hash]);
        return $this->pdo->lastInsertId();
    }

    public function updateLastLogin($id) {
        $this->pdo->prepare("UPDATE admin_users SET last_login=NOW() WHERE id=?")->execute([$id]);
    }
}
