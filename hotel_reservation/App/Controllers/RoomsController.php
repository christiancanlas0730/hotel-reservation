<?php
require_once __DIR__.'/../Core/Controller.php';
require_once __DIR__.'/../Config/Database.php';

class RoomsController extends Controller {

    // Force login
    public function auth(){
        if(!isset($_SESSION['user'])){
            header("Location: /hotel_reservation/public/auth/login");
            exit;
        }
        // Permissions:
        // - Admin (role_id=1): create/edit/delete
        // - Receptionist (role_id=2): create only
        //
        // NOTE: We can't rely on `$this->method` here (it doesn't exist on the controller),
        // so we infer which controller action called `auth()` (index/create/edit/delete).
        $caller = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['function'] ?? '';

        if(in_array($caller, ['edit', 'delete'], true) && $_SESSION['user']['role_id'] != 1){
            header("Location: /hotel_reservation/public/receptionist/dashboard");
            exit;
        }
        if($caller === 'create' && !in_array($_SESSION['user']['role_id'], [1,2], true)){
            header("Location: /hotel_reservation/public/receptionist/dashboard");
            exit;
        }
    }

    // List all rooms
    public function index(){
        $this->auth();

        $db = $this->model('Database');
        $rooms = [];
        $search = $_GET['search'] ?? '';

        // If searching for filename (.php), redirect to login
        if (!empty($search) && preg_match('/\\.php$/i', $search)) {
            header("Location: /hotel_reservation/public/auth/login");
            exit;
        }

        $sql = "SELECT * FROM rooms";
        if (!empty($search)) {
            $sql .= " WHERE type LIKE ?";
            $stmt = $db->conn->prepare($sql);
            $searchParam = "%$search%";
            $stmt->bind_param("s", $searchParam);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $result = $db->conn->query($sql);
        }
        while($row = $result->fetch_assoc()) $rooms[] = $row;

        $this->view("rooms/index", ["rooms" => $rooms, "search" => $search]);
    }

    // Add room
    public function create(){
        $this->auth();

        $db = $this->model('Database');
        $error = null;

        if($_SERVER['REQUEST_METHOD']==='POST'){
            $number = $_POST['room_number'];
            $type = $_POST['type'];
            $price = $_POST['price'];
            $status = 'Available';

            $stmt = $db->conn->prepare("INSERT INTO rooms(room_number,type,price,status) VALUES (?,?,?,?)");
            $stmt->bind_param("ssds",$number,$type,$price,$status);

            if($stmt->execute()) header("Location: /hotel_reservation/public/rooms");
            else $error="Failed to add room";
        }

        $this->view("rooms/create", ["error"=>$error]);
    }

    // Edit room
    public function edit($id){
        $this->auth();

        $db = $this->model('Database');
        $error = null;

        // Use prepared statement to safely fetch room
        $stmt = $db->conn->prepare("SELECT * FROM rooms WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $room = $stmt->get_result()->fetch_assoc();

        if($_SERVER['REQUEST_METHOD']==='POST'){
            $number = $_POST['room_number'];
            $type = $_POST['type'];
            $price = $_POST['price'];
            $status = $_POST['status'];

            // Prepared statement to safely update room
            $stmt = $db->conn->prepare("UPDATE rooms SET room_number=?, type=?, price=?, status=? WHERE id=?");
            $stmt->bind_param("ssdsi",$number,$type,$price,$status,$id);

            if($stmt->execute()) header("Location: /hotel_reservation/public/rooms");
            else $error="Failed to update room";
        }

        $this->view("rooms/edit", ["room"=>$room, "error"=>$error]);
    }

    // Delete room
    public function delete($id){
        $this->auth();

        $db = $this->model('Database');

        // Use prepared statement to safely delete
        $stmt = $db->conn->prepare("DELETE FROM rooms WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        header("Location: /hotel_reservation/public/rooms");
    }
}
