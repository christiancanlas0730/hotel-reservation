<?php

require_once __DIR__ . '/../Config/Database.php';

class Customer {

    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Get all customers
    public function all() {
        $result = $this->db->conn->query("SELECT * FROM customers");

        $customers = [];
        if($result) {
            while($row = $result->fetch_assoc()) {
                $customers[] = $row;
            }
        }

        return $customers;
    }

    // Create a new customer (safe with prepared statements)
    public function create($data) {
        $stmt = $this->db->conn->prepare(
            "INSERT INTO customers (name, email, phone) VALUES (?, ?, ?)"
        );

        $stmt->bind_param(
            "sss",
            $data['name'],
            $data['email'],
            $data['phone']
        );

        return $stmt->execute();
    }

    // Delete a customer
    public function delete($id) {
        $stmt = $this->db->conn->prepare("DELETE FROM customers WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

}
