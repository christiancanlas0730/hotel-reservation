<?php
require_once __DIR__.'/../Core/Controller.php';
require_once __DIR__.'/../Config/Database.php';

class ReceptionistController extends Controller {

    private $db;

    public function __construct(){
        $this->forceLogin();      // Force login for everyone
        $this->authRole(2);       // Only receptionists
        $database = new Database();
        $this->db = $database->conn;
    }

    // Force login function
    private function forceLogin(){
        if(!isset($_SESSION['user'])){
            header("Location: /hotel_reservation/public/auth/login");
            exit;
        }
    }

    // Restrict access by role
    public function authRole($role_id){
        if($_SESSION['user']['role_id'] != $role_id){
            session_destroy(); // clear session to force re-login
            header("Location: /hotel_reservation/public/auth/login");
            exit;
        }
    }

    // Default page: dashboard
    public function index(){
        $this->dashboard();
    }

    // Receptionist Dashboard
    public function dashboard(){
        $stmt = $this->db->query("
            SELECT reservations.*, users.username, rooms.room_number
            FROM reservations
            JOIN users ON reservations.user_id = users.id
            JOIN rooms ON reservations.room_id = rooms.id
            ORDER BY reservations.check_in DESC
            LIMIT 10
        ");

        $reservations = [];
        while($row = $stmt->fetch_assoc()) $reservations[] = $row;

        $this->view("receptionist/dashboard", ["reservations" => $reservations]);
    }

    // View available rooms for receptionist
    public function rooms(){
        $stmt = $this->db->query("SELECT * FROM rooms WHERE status = 'Available' ORDER BY room_number");
        $rooms = [];
        while($row = $stmt->fetch_assoc()) $rooms[] = $row;

        $this->view("receptionist/rooms", ["rooms" => $rooms]);
    }

    // View all reservations
    public function reservations(){
        $stmt = $this->db->query("
            SELECT reservations.*, users.username, rooms.room_number
            FROM reservations
            JOIN users ON reservations.user_id = users.id
            JOIN rooms ON reservations.room_id = rooms.id
            ORDER BY reservations.check_in DESC
        ");

        $reservations = [];
        while($row = $stmt->fetch_assoc()) $reservations[] = $row;

        $this->view("receptionist/reservations", ["reservations" => $reservations]);
    }

    // Check-in guest
    public function checkin($id = null){
        if(!$id){
            header("Location: /hotel_reservation/public/receptionist/reservations");
            exit;
        }

        $stmt = $this->db->prepare("UPDATE reservations SET status='checked_in' WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        header("Location: /hotel_reservation/public/receptionist/reservations");
        exit;
    }

    // Check-out guest
    public function checkout($id = null){
        if(!$id){
            header("Location: /hotel_reservation/public/receptionist/reservations");
            exit;
        }

        $stmt = $this->db->prepare("UPDATE reservations SET status='checked_out' WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        header("Location: /hotel_reservation/public/receptionist/reservations");
        exit;
    }

}
