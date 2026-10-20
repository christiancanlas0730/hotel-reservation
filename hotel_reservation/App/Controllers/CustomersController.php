<?php
require_once __DIR__.'/../Core/Controller.php';
require_once __DIR__.'/../Config/Database.php';

class CustomersController extends Controller {

    public function auth(){
        if(!isset($_SESSION['user'])){
            header("Location: /hotel_reservation/public/auth/login");
            exit;
        }
    }

    public function index(){
        $this->auth();

        $db = new Database();
        $customers = [];
        $result = $db->conn->query("SELECT * FROM customers");
        while($row = $result->fetch_assoc()) $customers[] = $row;

        $this->view("customers/index", ["customers" => $customers]);
    }

    public function create(){
        $this->auth();

        $db = new Database();
        $error = null;

        if($_SERVER['REQUEST_METHOD']==='POST'){
            $name = $_POST['name'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];

            $stmt = $db->conn->prepare("INSERT INTO customers(name,email,phone) VALUES (?,?,?)");
            $stmt->bind_param("sss",$name,$email,$phone);

            if($stmt->execute()) header("Location: /hotel_reservation/public/customers");
            else $error="Failed to add customer";
        }

        $this->view("customers/create", ["error"=>$error]);
    }

    public function delete($id){
        $this->auth();

        $db = new Database();
        $db->conn->query("DELETE FROM customers WHERE id=$id");
        header("Location: /hotel_reservation/public/customers");
    }
}
