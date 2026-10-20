<?php

require_once __DIR__ . '/../Config/Database.php';

class Role {

    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Get all roles
    public function all() {
        $result = $this->db->conn->query("SELECT * FROM roles");

        $roles = [];
        if($result) {
            while($row = $result->fetch_assoc()) {
                $roles[] = $row;
            }
        }

        return $roles;
    }

}
