<?php

require_once __DIR__ . '/../Config/Database.php';

class Room {

    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Get all rooms
    public function all() {
        $result = $this->db->conn->query("SELECT * FROM rooms");

        $rooms = [];
        if($result) {
            while($row = $result->fetch_assoc()) {
                $rooms[] = $row;
            }
        }

        return $rooms;
    }

    // Find a single room by ID
    public function find($id) {
        $stmt = $this->db->conn->prepare("SELECT * FROM rooms WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc(); // returns single room as array
    }

    // Create a new room
    public function create($data) {
        $stmt = $this->db->conn->prepare(
            "INSERT INTO rooms (room_number, type, price, status) VALUES (?, ?, ?, ?)"
        );
        $status = $data['status'] ?? 'Available'; // default status
        $stmt->bind_param(
            "ssds",
            $data['room_number'],
            $data['type'],
            $data['price'],
            $status
        );
        return $stmt->execute();
    }

    // Update a room
    public function update($id, $data) {
        $stmt = $this->db->conn->prepare(
            "UPDATE rooms SET room_number = ?, type = ?, price = ?, status = ? WHERE id = ?"
        );
        $stmt->bind_param(
            "ssdsi",
            $data['room_number'],
            $data['type'],
            $data['price'],
            $data['status'],
            $id
        );
        return $stmt->execute();
    }

    // Delete a room
    public function delete($id) {
        $stmt = $this->db->conn->prepare("DELETE FROM rooms WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

}
