<?php

class Controller {

    public function __construct(){
        if(session_status() == PHP_SESSION_NONE){
            session_start();
        }

        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Pragma: no-cache");

        // Routes that can be accessed without being logged in.
        // With mod_rewrite, REQUEST_URI can include `?url=...`, so we only compare the path.
        $publicRoutes = [
            '/hotel_reservation/public',
            '/hotel_reservation/public/login',
            '/hotel_reservation/public/register', // legacy link (kept for compatibility)
            '/hotel_reservation/public/auth',
            '/hotel_reservation/public/auth/login',
            '/hotel_reservation/public/auth/register',
        ];

        $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $currentPath = rtrim($currentPath ?? '', '/');
        if($currentPath === ''){
            $currentPath = '/hotel_reservation/public';
        }

        $normalizedPublicRoutes = array_map(function($p){
            return rtrim($p, '/');
        }, $publicRoutes);

        if(!isset($_SESSION['user']) && !in_array($currentPath, $normalizedPublicRoutes, true)){
            header("Location: /hotel_reservation/public/auth/login");
            exit;
        }
    }

    public function view($view, $data = []){
        $viewFile = __DIR__ . '/../Views/' . $view . '.php';
        if(file_exists($viewFile)){
            extract($data);
            require $viewFile;
        } else {
            die("View not found: " . $viewFile);
        }
    }

    public function model($model){
        $modelFile = __DIR__ . '/../Models/' . $model . '.php';
        if(file_exists($modelFile)){
            require_once $modelFile;
            return new $model();
        } elseif (file_exists(__DIR__ . '/../Config/' . $model . '.php')) {
            require_once __DIR__ . '/../Config/' . $model . '.php';
            return new $model();
        }
        die("Model/Config not found: " . $model);
    }

    public function auth(){
        if(!isset($_SESSION['user'])){
            header("Location: /hotel_reservation/public/auth/login");
            exit;
        }
    }

    public function authRole($role_id){
        $this->auth();

        if($_SESSION['user']['role_id'] != $role_id){
            switch($_SESSION['user']['role_id']){
                case 1:
                    header("Location: /hotel_reservation/public/dashboard");
                    break;
                case 2:
                    header("Location: /hotel_reservation/public/receptionist/dashboard");
                    break;
                case 3:
                    header("Location: /hotel_reservation/public/guest/dashboard");
                    break;
                default:
                    header("Location: /hotel_reservation/public/auth/login");
            }
            exit;
        }
    }
}
