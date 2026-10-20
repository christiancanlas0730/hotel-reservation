<?php
require_once __DIR__.'/../Core/Controller.php';

class HomeController extends Controller {

    public function index() {
        if(isset($_SESSION['user'])){
            switch($_SESSION['user']['role_id']){
                case 1: header("Location: /hotel_reservation/public/dashboard"); break;
                case 2: header("Location: /hotel_reservation/public/receptionist/dashboard"); break;
                case 3: header("Location: /hotel_reservation/public/guest/rooms"); break;
                default: header("Location: /hotel_reservation/public/auth/login");
            }
        } else {
            header("Location: /hotel_reservation/public/auth/login");
        }
        exit;
    }
}
?>

