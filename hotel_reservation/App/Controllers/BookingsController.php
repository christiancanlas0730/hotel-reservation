<?php
require_once __DIR__.'/../Core/Controller.php';
require_once __DIR__.'/../Config/Database.php';

class BookingsController extends Controller {

    public function auth(){
        if(!isset($_SESSION['user'])){
            header("Location: /hotel_reservation/public/auth/login");
            exit;
        }
    }

    public function index(){
        $this->auth();

        $db = new Database();
        $bookings = [];
        $result = $db->conn->query("
            SELECT bookings.id, customers.name as customer_name, rooms.room_number, bookings.check_in, bookings.check_out
            FROM bookings
            JOIN customers ON bookings.customer_id = customers.id
            JOIN rooms ON bookings.room_id = rooms.id
        ");
        while($row = $result->fetch_assoc()) $bookings[] = $row;

        $this->view("bookings/index", ["bookings" => $bookings]);
    }

    public function guests($filter = null) {
    $this->auth();

    $db = new Database();

    $today = date('Y-m-d');

    // Determine SQL based on filter
    if($filter === 'checked_in'){
        // Guests currently checked in
        $sql = "
            SELECT bookings.id, customers.name as customer_name, customers.email, customers.phone, 
                   rooms.room_number, rooms.type, bookings.check_in, bookings.check_out
            FROM bookings
            JOIN customers ON bookings.customer_id = customers.id
            JOIN rooms ON bookings.room_id = rooms.id
            WHERE bookings.check_in <= '$today' AND bookings.check_out >= '$today'
            ORDER BY bookings.check_in DESC
        ";
    } elseif($filter === 'checked_out'){
        // Guests who already checked out
        $sql = "
            SELECT bookings.id, customers.name as customer_name, customers.email, customers.phone, 
                   rooms.room_number, rooms.type, bookings.check_in, bookings.check_out
            FROM bookings
            JOIN customers ON bookings.customer_id = customers.id
            JOIN rooms ON bookings.room_id = rooms.id
            WHERE bookings.check_out < '$today'
            ORDER BY bookings.check_out DESC
        ";
    } else {
        // All guests
        $sql = "
            SELECT bookings.id, customers.name as customer_name, customers.email, customers.phone, 
                   rooms.room_number, rooms.type, bookings.check_in, bookings.check_out
            FROM bookings
            JOIN customers ON bookings.customer_id = customers.id
            JOIN rooms ON bookings.room_id = rooms.id
            ORDER BY bookings.check_in DESC
        ";
    }

    $result = $db->conn->query($sql);
    $bookings = [];
    while($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }

    // This view file exists as `App/Views/Bookings/guests_for_receptionist.php`
    $this->view("bookings/guests_for_receptionist", [
        "bookings" => $bookings,
        "filter" => $filter
    ]);
}
    public function create(){
    $this->auth();

    $db = new Database();
    $error = null;

    $customers = $db->conn->query("SELECT * FROM customers");
    $rooms = $db->conn->query("SELECT * FROM rooms WHERE status='Available'");

    if($_SERVER['REQUEST_METHOD']==='POST'){
        // Check if new guest info is provided
        if(!empty($_POST['new_name'])){
            $name  = trim($_POST['new_name']);
            $email = trim($_POST['new_email']);
            $phone = trim($_POST['new_phone']);

            $stmt = $db->conn->prepare("INSERT INTO customers(name,email,phone) VALUES (?,?,?)");
            $stmt->bind_param("sss", $name, $email, $phone);

            if($stmt->execute()){
                $customer_id = $db->conn->insert_id; // new customer ID
            } else {
                $error = "Failed to add new guest.";
            }
        } else {
            $customer_id = $_POST['customer_id'] ?? null;
            if(!$customer_id) $error = "Please select or enter a guest.";
        }

        if(!$error){
            $room_id   = $_POST['room_id'];
            $check_in  = $_POST['check_in'];
            $check_out = $_POST['check_out'];

            // Insert booking
            $stmt = $db->conn->prepare("INSERT INTO bookings(customer_id,room_id,check_in,check_out) VALUES (?,?,?,?)");
            $stmt->bind_param("iiss",$customer_id,$room_id,$check_in,$check_out);

            if($stmt->execute()){
                $db->conn->query("UPDATE rooms SET status='Booked' WHERE id=$room_id");
                header("Location: /hotel_reservation/public/bookings");
                exit;
            } else $error = "Failed to add booking";
        }
    }

    $this->view("bookings/create", [
        "error"=>$error,
        "customers"=>$customers,
        "rooms"=>$rooms
    ]);
}

    public function delete($id){
        $this->auth();

        $db = new Database();

        $room_id = $db->conn->query("SELECT room_id FROM bookings WHERE id=$id")->fetch_assoc()['room_id'];
        $db->conn->query("UPDATE rooms SET status='Available' WHERE id=$room_id");

        $db->conn->query("DELETE FROM bookings WHERE id=$id");
        header("Location: /hotel_reservation/public/bookings");
    }
}
