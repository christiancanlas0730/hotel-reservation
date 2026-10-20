<?php
require_once __DIR__.'/../Core/Controller.php';
require_once __DIR__.'/../Config/Database.php';

class GuestController extends Controller {

    private $db;

    public function __construct(){
        $this->forceLogin();    // <-- force login first
        $this->authRole(3);     // <-- then check role
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
            // If someone logged in with wrong role, send to login
            session_destroy(); // clear session to force re-login
            header("Location: /hotel_reservation/public/auth/login");
            exit;
        }
    }

    public function index(){
        $this->rooms(); // default page
    }

    

    public function rooms(){
        $stmt = $this->db->query("SELECT * FROM rooms WHERE status='Available'");
        $rooms = [];
        while($row = $stmt->fetch_assoc()) $rooms[] = $row;
        $this->view("guest/rooms", ["rooms" => $rooms]);
    }

    // Add this method inside GuestController
public function guestsForReceptionist() {
    // Only allow receptionist access
    if($_SESSION['user']['role_id'] != 2){
        // Not a receptionist → redirect to login
        session_destroy();
        header("Location: /hotel_reservation/public/auth/login");
        exit;
    }

    $today = date('Y-m-d');

    // Filter from GET parameter
    $filter = $_GET['filter'] ?? '';

    if($filter === 'checked_in'){
        $sql = "
            SELECT reservations.id, users.name as customer_name, users.email, users.phone,
                   rooms.room_number, rooms.type, reservations.check_in, reservations.check_out
            FROM reservations
            JOIN users ON reservations.user_id = users.id
            JOIN rooms ON reservations.room_id = rooms.id
            WHERE reservations.check_in <= '$today' AND reservations.check_out >= '$today'
            ORDER BY reservations.check_in DESC
        ";
    } elseif($filter === 'checked_out'){
        $sql = "
            SELECT reservations.id, users.name as customer_name, users.email, users.phone,
                   rooms.room_number, rooms.type, reservations.check_in, reservations.check_out
            FROM reservations
            JOIN users ON reservations.user_id = users.id
            JOIN rooms ON reservations.room_id = rooms.id
            WHERE reservations.check_out < '$today'
            ORDER BY reservations.check_out DESC
        ";
    } else {
        $sql = "
            SELECT reservations.id, users.name as customer_name, users.email, users.phone,
                   rooms.room_number, rooms.type, reservations.check_in, reservations.check_out
            FROM reservations
            JOIN users ON reservations.user_id = users.id
            JOIN rooms ON reservations.room_id = rooms.id
            ORDER BY reservations.check_in DESC
        ";
    }

    $result = $this->db->query($sql);
    $bookings = [];
    while($row = $result->fetch_assoc()) $bookings[] = $row;

    $this->view("guest/guests_for_receptionist", [
        "bookings" => $bookings,
        "filter" => $filter
    ]);
}

    public function dashboard(){
    $user_id = $_SESSION['user']['id'];
    $stmt = $this->db->prepare("
        SELECT reservations.*, rooms.room_number
        FROM reservations
        JOIN rooms ON reservations.room_id = rooms.id
        WHERE reservations.user_id=?
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $reservations = [];
    while($row = $result->fetch_assoc()) $reservations[] = $row;

    $this->view("guest/dashboard", [
        "reservations" => $reservations,
        "message" => "Welcome, Guest! Here are your reservations."
    ]);
}

    public function book(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $user_id = $_SESSION['user']['id'];
            $room_id = $_POST['room_id'];
            $check_in = $_POST['check_in'];
            $check_out = $_POST['check_out'];

            $stmt = $this->db->prepare("
                INSERT INTO reservations (user_id, room_id, check_in, check_out, status)
                VALUES (?, ?, ?, ?, 'pending')
            ");
            $stmt->bind_param("iiss", $user_id, $room_id, $check_in, $check_out);
            $stmt->execute();

            header("Location: /hotel_reservation/public/guest/dashboard");
            exit;
        }
    }

    public function cancel($id){
        $stmt = $this->db->prepare("UPDATE reservations SET status='cancelled' WHERE id=? AND user_id=?");
        $stmt->bind_param("ii", $id, $_SESSION['user']['id']);
        $stmt->execute();

        header("Location: /hotel_reservation/public/guest/dashboard");
        exit;
    }

    public function update_profile(){
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            header("Location: /hotel_reservation/public/guest/dashboard");
            exit;
        }

        $user_id = $_SESSION['user']['id'];
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password'] ?? '');

        if(empty($username) || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)){
            $_SESSION['error'] = "Invalid username or email.";
            header("Location: /hotel_reservation/public/guest/dashboard");
            exit;
        }

        // Check username unique (exclude self)
        $check_stmt = $this->db->prepare("SELECT id FROM users WHERE username=? AND id != ?");
        $check_stmt->bind_param("si", $username, $user_id);
        $check_stmt->execute();
        if($check_stmt->get_result()->num_rows > 0){
            $_SESSION['error'] = "Username already taken.";
            header("Location: /hotel_reservation/public/guest/dashboard");
            exit;
        }

        // Check email unique (exclude self)
        $check_stmt = $this->db->prepare("SELECT id FROM users WHERE email=? AND id != ?");
        $check_stmt->bind_param("si", $email, $user_id);
        $check_stmt->execute();
        if($check_stmt->get_result()->num_rows > 0){
            $_SESSION['error'] = "Email already in use.";
            header("Location: /hotel_reservation/public/guest/dashboard");
            exit;
        }

        // Build update query
        $sql = "UPDATE users SET username=?, email=?";
        $params = [$username, $email];
        $types = "ss";

        if(!empty($password)){
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql .= ", password=?";
            $params[] = $hashed_password;
            $types .= "s";
        }

        $sql .= " WHERE id=?";
        $params[] = $user_id;
        $types .= "i";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param($types, ...$params);

        if($stmt->execute()){
            // Refresh session with updated data
            $refresh_stmt = $this->db->prepare("SELECT * FROM users WHERE id=?");
            $refresh_stmt->bind_param("i", $user_id);
            $refresh_stmt->execute();
            $updated_user = $refresh_stmt->get_result()->fetch_assoc();
            $_SESSION['user'] = $updated_user;

            $_SESSION['success'] = "Profile updated successfully.";
        } else {
            $_SESSION['error'] = "Update failed.";
        }

        header("Location: /hotel_reservation/public/guest/dashboard");
        exit;
    }

    // Handle profile picture upload
    public function upload_profile_picture(){
        if($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['profile_picture'])){
            $_SESSION['error'] = "Invalid request.";
            header("Location: /hotel_reservation/public/guest/dashboard");
            exit;
        }

        $user_id = $_SESSION['user']['id'];
        $file = $_FILES['profile_picture'];

        if($file['error'] !== UPLOAD_ERR_OK){
            $_SESSION['error'] = "File upload error.";
            header("Location: /hotel_reservation/public/guest/dashboard");
            exit;
        }

        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 2 * 1024 * 1024; // 2MB

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if(!in_array($mime_type, $allowed_types) || $file['size'] > $max_size){
            $_SESSION['error'] = "Invalid file type or size.";
            header("Location: /hotel_reservation/public/guest/dashboard");
            exit;
        }

        $upload_dir = __DIR__ . '/../../public/assets/profiles/';
        if(!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'profile_' . $user_id . '_' . time() . '.' . $extension;
        $filepath = $upload_dir . $filename;

        if(move_uploaded_file($file['tmp_name'], $filepath)){
            // Delete old picture if exists
            $old_pic = $_SESSION['user']['profile_picture'] ?? '';
            if($old_pic && file_exists($upload_dir . $old_pic)){
                unlink($upload_dir . $old_pic);
            }

            // Update DB
            $stmt = $this->db->prepare("UPDATE users SET profile_picture=? WHERE id=?");
            $stmt->bind_param("si", $filename, $user_id);
            $stmt->execute();

            // Refresh session
            $refresh_stmt = $this->db->prepare("SELECT * FROM users WHERE id=?");
            $refresh_stmt->bind_param("i", $user_id);
            $refresh_stmt->execute();
            $updated_user = $refresh_stmt->get_result()->fetch_assoc();
            $_SESSION['user'] = $updated_user;

            $_SESSION['success'] = "Profile picture updated.";
        } else {
            $_SESSION['error'] = "Upload failed.";
        }

        header("Location: /hotel_reservation/public/guest/dashboard");
        exit;
    }


}
