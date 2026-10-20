<?php
require_once __DIR__.'/../Core/Controller.php';
require_once __DIR__.'/../Config/Database.php';

class DashboardController extends Controller {

    // Force login
    public function auth(){
        if(!isset($_SESSION['user'])){
            header("Location: /hotel_reservation/public/auth/login");
            exit;
        }
    }

    public function index(){
        $this->auth(); // check login
        
        // Admin only check
        if($_SESSION['user']['role_id'] != 1){
            header("Location: /hotel_reservation/public/dashboard");
            exit;
        }

$db = $this->model('Database');

        // Fetch totals
        $totalRooms = $db->conn->query("SELECT COUNT(*) as total FROM rooms")->fetch_assoc()['total'];
        $totalCustomers = $db->conn->query("SELECT COUNT(*) as total FROM customers")->fetch_assoc()['total'];
        $totalBookings = $db->conn->query("SELECT COUNT(*) as total FROM bookings")->fetch_assoc()['total'];

        // Fetch users with role names
        $users = $db->conn->query("
            SELECT u.*, r.role_name 
            FROM users u 
            LEFT JOIN roles r ON u.role_id = r.id
            ORDER BY u.created_at DESC
        ")->fetch_all(MYSQLI_ASSOC);

        // Fetch recent bookings with details
        $recentBookings = $db->conn->query("
            SELECT b.*, c.name as customer_name, r.room_number, r.type
            FROM bookings b
            LEFT JOIN customers c ON b.customer_id = c.id
            LEFT JOIN rooms r ON b.room_id = r.id
            ORDER BY b.id DESC
            LIMIT 10
        ")->fetch_all(MYSQLI_ASSOC);

        // Pass data to view
        $this->view("dashboard/index", [
            "totalRooms" => $totalRooms,
            "totalCustomers" => $totalCustomers,
            "totalBookings" => $totalBookings,
            "users" => $users,
            "recentBookings" => $recentBookings
        ]);
    }
}
