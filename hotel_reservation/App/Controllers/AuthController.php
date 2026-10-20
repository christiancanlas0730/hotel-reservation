<?php
require_once __DIR__.'/../Core/Controller.php';
require_once __DIR__.'/../Config/Database.php';

class AuthController extends Controller {

    // Login method
   public function login() {
    // If you're already logged in, we still want the user to land on the login page
    // when they visit `/auth` or `/auth/login` (per UX requirement).
    // They can use "Logout" if they want to switch accounts.
    $error = null;
    if(isset($_SESSION['user'])){
        $error = "You are already logged in.";
    }

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $db = new Database();

        $username = trim($_POST['username']);
        $password = $_POST['password'];

        if(empty($username) || empty($password)){
            $error = "Please fill in all fields";
        } else {
            $stmt = $db->conn->prepare("SELECT * FROM users WHERE username=?");
            if(!$stmt) die("Prepare failed: " . $db->conn->error);

            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if($result->num_rows > 0){
                $user = $result->fetch_assoc();

                if(password_verify($password, $user['password'])){
                    $_SESSION['user'] = $user; // Save session

                    // Redirect based on role
                    switch($user['role_id']){
                        case 1: header("Location: /hotel_reservation/public/dashboard"); break;
                        case 2: header("Location: /hotel_reservation/public/receptionist/dashboard"); break;
                        case 3: header("Location: /hotel_reservation/public/guest/dashboard"); break;
                        default: header("Location: /hotel_reservation/public/auth/login"); break;
                    }
                    exit;
                } else {
                    $error = "Incorrect password";
                }
            } else {
                $error = "User not found";
            }
        }
    }

    $this->view("auth/login", ["error"=>$error]);
}

    // Register method
    public function register() {
        if(isset($_SESSION['user'])){
            $this->redirectByRole($_SESSION['user']['role_id']);
        }

        $error = null;
        $success = null;

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $db = new Database();

            $username = trim($_POST['username']);
            $password = $_POST['password'];

            if(empty($username) || empty($password)){
                $error = "Please fill in all fields";
            } else {
                // Check if username exists
                $stmt = $db->conn->prepare("SELECT id FROM users WHERE username=?");
                if(!$stmt) die("Prepare failed: " . $db->conn->error);

                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();

                if($result->num_rows > 0){
                    $error = "Username already exists";
                } else {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $stmt2 = $db->conn->prepare("INSERT INTO users (username, password, role_id) VALUES (?, ?, 3)"); // Guest role
                    if(!$stmt2) die("Prepare failed: " . $db->conn->error);

                    $stmt2->bind_param("ss", $username, $hashedPassword);
                    if($stmt2->execute()){
                        $success = "Registration successful. Please login now.";
                    } else {
                        $error = "Registration failed: " . $stmt2->error;
                    }
                }
            }
        }

        $this->view("auth/register", ["error"=>$error, "success"=>$success]);
    }

    // Logout method
    public function logout() {
        if(session_status() == PHP_SESSION_NONE) session_start();

        $_SESSION = [];
        session_destroy();
        session_write_close();
        setcookie(session_name(), '', time() - 3600, '/');

        header("Location: /hotel_reservation/public/auth/login");
        exit;
    }

    // Helper function for role-based redirect
    private function redirectByRole($role_id){
        switch($role_id){
            case 1: // Admin
                header("Location: /hotel_reservation/public/dashboard");
                break;
            case 2: // Receptionist
                header("Location: /hotel_reservation/public/receptionist/dashboard");
                break;
            case 3: // Guest
    header("Location: /hotel_reservation/public/guest"); // default rooms page
    break;


            default:
                header("Location: /hotel_reservation/public/auth/login");
        }
        exit;
    }
}
