<?php
// Start the session for auth
session_start();

// Load core files
require_once __DIR__ . '/../App/Core/App.php';
require_once __DIR__ . '/../App/Core/Controller.php';

// Initialize the App (handles routing)
$app = new App();
