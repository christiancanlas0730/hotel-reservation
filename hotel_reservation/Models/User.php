<?php

require_once __DIR__ . '/../Config/Database.php';

class User {

    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Register a new user
    public function register($data) {
        $password = password_hash($data['password'], PASSWORD_DEFAULT);

        $stmt = $this->db->conn->prepare(
            "INSERT INTO users (username, email, password, role_id) VALUES (?, ?, ?, ?)"
        );
        $role_id = 3; // default role for normal users
        $stmt->bind_param(
            "sssi",
            $data['username'],
            $data['email'],
            $password,
            $role_id
        );

        return $stmt->execute();
    }

    // Login: get user by username or email
    public function login($usernameOrEmail) {
        $stmt = $this->db->conn->prepare(
            "SELECT * FROM users WHERE username = ? OR email = ?"
        );
        $stmt->bind_param("ss", $usernameOrEmail, $usernameOrEmail);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc(); // returns single user or null
    }

}
