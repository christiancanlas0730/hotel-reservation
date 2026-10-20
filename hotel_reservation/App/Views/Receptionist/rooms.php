<?php if(session_status() == PHP_SESSION_NONE) session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Available Rooms</title>

<link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">

<style>
body {
    font-family:'Segoe UI', sans-serif;
    background:#f8f9fa;
    margin:0;
}

/* ------------------------
   SIDEBAR
------------------------ */
.sidebar {
    height: 100vh;
    width: 220px;
    position: fixed;
    top: 0;
    left: 0;
    background-color: #0d6efd;
    color: #fff;
    padding-top: 60px;
    transition: width 0.3s ease;
    overflow-x: hidden;
    z-index: 999;
}

.sidebar.collapsed {
    width: 60px;
    background-color: #0b5ed7;
}

.sidebar a {
    display: block;
    color: #fff;
    padding: 12px 20px;
    text-decoration: none;
    transition: all 0.3s ease;
}

.sidebar a:hover {
    background-color: #0a58ca;
}

.sidebar.collapsed a span {
    display: none;
}

/* ------------------------
   TOP NAVBAR
------------------------ */
.top-navbar {
    position: fixed;
    top: 0;
    left: 220px;
    width: calc(100% - 220px);
    height: 55px;
    background: #fff;
    display: flex;
    align-items: center;
    padding: 0 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    z-index: 1001;
}

.top-navbar .btn {
    font-size: 1.3rem;
    border: none;
    background: none;
    color: #0d6efd;
    cursor: pointer;
    transition: all 0.3s ease;
}

.sidebar.collapsed ~ .top-navbar {
    left: 60px;
    width: calc(100% - 60px);
}

.content {
    margin-left: 220px;
    margin-top: 60px;
    padding: 20px;
    transition: margin-left 0.3s ease;
}

.sidebar.collapsed ~ .content {
    margin-left: 60px;
}

/* ------------------------
   ROOM GRID
------------------------ */
.room-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 25px;
    margin-top: 20px;
}

.room-card {
    background: #fff;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.room-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.15);
}

.room-image img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-bottom: 1px solid #ddd;
    transition: transform 0.3s ease;
}

.room-image img:hover {
    transform: scale(1.05);
}

.room-body {
    padding: 15px 20px;
}

.room-body h3 {
    margin: 0 0 10px 0;
    font-size: 1.25rem;
    font-weight: 600;
}

.room-body p {
    margin: 5px 0;
    font-size: 0.95rem;
    color: #555;
}

.room-body .btn-primary {
    width: 100%;
    font-size: 0.9rem;
}

.text-center .btn-secondary {
    font-size: 0.95rem;
    padding: 0.5rem 1.2rem;
}

.room-header h2 {
    font-weight: 600;
    font-size: 1.8rem;
}

.room-header p {
    color: #555;
    font-size: 1rem;
    margin-bottom: 10px;
}

/* Responsive */
@media(max-width:768px){
    .sidebar { width: 0; }
    .sidebar.collapsed { width: 0; }
    .top-navbar { left: 0; width: 100%; }
    .content { margin-left: 0; }
}
</style>
</head>
<body>

<!-- Top Navbar with burger -->
<div class="top-navbar" id="topNavbar">
    <button class="btn" id="sidebarToggle">&#9776;</button>
</div>

<!-- Sidebar with full receptionist links -->
<div class="sidebar" id="sidebar">
    <a href="/hotel_reservation/public/receptionist/dashboard"><span>Dashboard</span></a>
    <a href="/hotel_reservation/public/receptionist/new_reservation"><span>New Reservation</span></a>
    <a href="/hotel_reservation/public/receptionist/checkin"><span>Check-in Guest</span></a>
    <a href="/hotel_reservation/public/receptionist/checkout"><span>Check-out Guest</span></a>
    <a href="/hotel_reservation/public/receptionist/rooms"><span>Available Rooms</span></a>
    <a href="/hotel_reservation/public/receptionist/guests"><span>Guests List</span></a>
    <a href="#" data-bs-toggle="modal" data-bs-target="#logoutModal"><span>Logout</span></a>
</div>

<!-- Main content -->
<div class="content">
<div class="card p-3">

<div class="room-header">
    <h2>Available Rooms</h2>
    <p>These rooms are ready for booking:</p>
</div>

<div class="room-grid">
<?php if (!empty($rooms)): ?>
    <?php foreach ($rooms as $room): ?>
    <?php
    $roomFolder = $_SERVER['DOCUMENT_ROOT'] . "/hotel_reservation/public/assets/rooms/" . $room['id'];
    $webPath = "/hotel_reservation/public/assets/default.png";
    if (is_dir($roomFolder)) {
        $files = scandir($roomFolder);
        foreach ($files as $file) {
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg','jpeg','png','webp'])) {
                $webPath = "/hotel_reservation/public/assets/rooms/" . $room['id'] . "/" . $file;
                break;
            }
        }
    }
    ?>
    <div class="room-card">
        <div class="room-image">
            <img src="<?= htmlspecialchars($webPath) ?>" alt="Room Image">
        </div>
        <div class="room-body">
            <h3>Room <?= htmlspecialchars($room['room_number']) ?></h3>
            <p><strong>Type:</strong> <?= htmlspecialchars($room['type']) ?></p>
            <p><strong>Price:</strong> $<?= number_format($room['price'], 2) ?> / night</p>
            <a href="/hotel_reservation/public/bookings/create?room_id=<?= $room['id'] ?>" 
               class="btn btn-primary mt-2">Book This Room</a>
        </div>
    </div>
    <?php endforeach; ?>
<?php else: ?>
<p style="text-align:center; padding:20px;">No available rooms at the moment.</p>
<?php endif; ?>
</div>

<div class="text-center mt-4">
    <a href="/hotel_reservation/public/receptionist/dashboard"
       class="btn btn-secondary">Back to Dashboard</a>
</div>

</div>
</div>

<!-- Logout Confirmation Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to logout?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <a href="/hotel_reservation/public/auth/logout" class="btn btn-danger">Logout</a>
      </div>
    </div>
  </div>
</div>

<script src="../../bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
const sidebar = document.getElementById('sidebar');
const toggleBtn = document.getElementById('sidebarToggle');
const topNavbar = document.getElementById('topNavbar');

toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('collapsed');

    if(sidebar.classList.contains('collapsed')){
        topNavbar.style.left = '60px';
        topNavbar.style.width = 'calc(100% - 60px)';
    } else {
        topNavbar.style.left = '220px';
        topNavbar.style.width = 'calc(100% - 220px)';
    }
});
</script>

</body>
</html>
