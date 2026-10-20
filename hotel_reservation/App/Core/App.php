<?php 

class App {

    protected $controller = 'DashboardController';
    protected $method = 'index';
    protected $params = [];

    public function __construct(){

        if(session_status() == PHP_SESSION_NONE){
            session_start();
        }

        $url = $this->parseUrl();

        // If root and not logged in → login
        if(empty($url) && !isset($_SESSION['user'])){
            $this->controller = 'AuthController';
            $this->method = 'login';
        }

        // CONTROLLER
        if(isset($url[0])){
            $controllerName = ucfirst($url[0]) . 'Controller';
            $controllerFile = __DIR__ . '/../Controllers/' . $controllerName . '.php';

            if(file_exists($controllerFile)){
                $this->controller = $controllerName;
                require_once $controllerFile;
                unset($url[0]);
            }
        }

        // Load controller if not yet loaded
        if(!class_exists($this->controller)){
            require_once __DIR__ . '/../Controllers/' . $this->controller . '.php';
        }

        $this->controller = new $this->controller;

        // METHOD
        if(isset($url[1]) && method_exists($this->controller, $url[1])){
            $this->method = $url[1];
            unset($url[1]);
        }

        // PARAMETERS
        $this->params = $url ? array_values($url) : [];

        // ROLE REDIRECT
        $currentUrl = $_SERVER['REQUEST_URI'];

        if(isset($_SESSION['user']) &&
           ($currentUrl === '/hotel_reservation/public/' || $currentUrl === '/hotel_reservation/public') &&
           $this->controller instanceof DashboardController &&
           $this->method === 'index'){

            switch($_SESSION['user']['role_id']){
                case 2:
                    header('Location: /hotel_reservation/public/receptionist/dashboard');
                    exit;
                case 3:
                    header('Location: /hotel_reservation/public/guest/dashboard');
                    exit;
            }
        }

        // CALL METHOD SAFELY
        if(!method_exists($this->controller, $this->method)){
            die("Method {$this->method} not found in " . get_class($this->controller));
        }

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    private function parseUrl(){
        if(isset($_GET['url'])){
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}
?>
