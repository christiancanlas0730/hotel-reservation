<?php
if(session_status() == PHP_SESSION_NONE) session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Guests List</title>
<link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
<style>
body { font-family: 'Segoe UI', sans-serif; background: #f8f9fa; }
nav a { margin-right: 15px; text-decoration: none; color: #333; }
nav a.active { font-weight: bold; color: #007bff; }
.container { margin-top: 50px; }
</style>
</head>
<body>

<nav>
    <a href="/hotel_reservation/public/receptionist/new_reservation">New Reservation</a>
    <a href="/hotel_reservation/public/receptionist/checkin">Check-in Guest</a>
    <a href="/hotel_reservation/public/receptionist/checkout">Check-out Guest</a>
    <a href="/hotel_reservation/public/receptionist/guests" class="active">Guests List</a>
</nav>

<div class="container">
    <h2>Guests List</h2>
    <p>Here you can see all guests.</p>
</div>

</body>
</html>
