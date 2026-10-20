<?php

require_once __DIR__ . '/../Config/Database.php';

class Booking {

    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Get all bookings with customer name and room number
    public function all() {
        $sql = "SELECT bookings.id, customers.name AS customer_name, rooms.room_number, bookings.check_in, bookings.check_out
                FROM bookings
                JOIN customers ON bookings.customer_id = customers.id
                JOIN rooms ON bookings.room_id = rooms.id";

        $result = $this->db->conn->query($sql);

        $bookings = [];
        if($result) {
            while($row = $result->fetch_assoc()) {
                $bookings[] = $row;
            }
        }

        return $bookings;
    }

    // Create a new booking (safe with prepared statements)
    public function create($data) {
        $stmt = $this->db->conn->prepare(
            "INSERT INTO bookings (customer_id, room_id, check_in, check_out) VALUES (?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "iiss",
            $data['customer_id'],
            $data['room_id'],
            $data['check_in'],
            $data['check_out']
        );

        return $stmt->execute();
    }

    // Optional: delete booking
    public function delete($id) {
        // First, free the room
        $room_id = $this->db->conn->query("SELECT room_id FROM bookings WHERE id=$id")->fetch_assoc()['room_id'];
        $this->db->conn->query("UPDATE rooms SET status='Available' WHERE id=$room_id");

        // Delete booking
        return $this->db->conn->query("DELETE FROM bookings WHERE id=$id");
    }

}
