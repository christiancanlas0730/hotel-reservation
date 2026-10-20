<?php if(session_status() == PHP_SESSION_NONE) session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>New Reservation</title>

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
    padding: 0 15px;
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
}

/* Adjust content when sidebar collapses */
.sidebar.collapsed ~ .top-navbar { left: 60px; width: calc(100% - 60px); }

.content {
    margin-left: 220px;
    margin-top: 60px;
    padding: 20px;
    transition: margin-left 0.3s ease;
}

.sidebar.collapsed ~ .content { margin-left: 60px; }

/* ------------------------
   QUICK STATS
------------------------ */
.quick-stats {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 20px;
}

.quick-stats .stat-card {
    flex: 1 1 calc(25% - 15px);
    min-width: 120px;
    background: #fff;
    padding: 15px;
    border-radius: 8px;
    text-align: center;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
}

.quick-stats .stat-card h5 { font-size: 0.9rem; margin-bottom:5px; }
.quick-stats .stat-card h3 { font-size: 1.25rem; margin:0; }

/* ------------------------
   FORM
------------------------ */
.card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    padding: 20px;
    margin-bottom: 25px;
}

.form-control, .form-select {
    border-radius: 6px;
    padding: 10px;
}

.btn-primary {
    border-radius: 6px;
    padding: 10px 15px;
}

@media(max-width:768px){
    .sidebar { width: 0; }
    .sidebar.collapsed { width: 0; }
    .top-navbar { left: 0; width: 100%; }
    .content { margin-left: 0; padding: 15px; }

    .quick-stats {
        flex-direction: column;
    }
    .quick-stats .stat-card { flex: 1 1 100%; }
}
</style>
</head>
<body>

<!-- Top Navbar -->
<div class="top-navbar" id="topNavbar">
    <button class="btn" id="sidebarToggle">&#9776;</button>
</div>

<!-- Sidebar -->
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
    <!-- Quick Stats -->
    <div class="quick-stats">
        <div class="stat-card" style="background:#0d6efd; color:#fff;">
            <h5>Total Rooms</h5>
            <h3><?= $totalRooms ?? 0 ?></h3>
        </div>
        <div class="stat-card" style="background:#28a745; color:#fff;">
            <h5>Occupied Rooms</h5>
            <h3><?= $occupiedRooms ?? 0 ?></h3>
        </div>
        <div class="stat-card" style="background:#ffc107; color:#000;">
            <h5>Pending Reservations</h5>
            <h3><?= $pendingReservations ?? 0 ?></h3>
        </div>
        <div class="stat-card" style="background:#6c757d; color:#fff;">
            <h5>Check-outs Today</h5>
            <h3><?= $checkoutsToday ?? 0 ?></h3>
        </div>
    </div>

    <!-- New Reservation Form -->
    <div class="card">
        <h2>New Reservation</h2>
        <form action="/hotel_reservation/public/receptionist/new_reservation/create" method="post">
            <div class="mb-3">
                <label for="guest" class="form-label">Guest Name</label>
                <input type="text" class="form-control" name="guest" id="guest" required>
            </div>
            <div class="mb-3">
                <label for="room" class="form-label">Room</label>
                <select name="room_id" id="room" class="form-select" required>
                    <option value="">Select a room</option>
                    <?php foreach($rooms as $room): ?>
                        <option value="<?= htmlspecialchars($room['id']) ?>">Room <?= htmlspecialchars($room['room_number']) ?> (<?= htmlspecialchars($room['type']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="checkin" class="form-label">Check-in Date</label>
                <input type="date" class="form-control" name="checkin" id="checkin" required>
            </div>
            <div class="mb-3">
                <label for="checkout" class="form-label">Check-out Date</label>
                <input type="date" class="form-control" name="checkout" id="checkout" required>
            </div>
            <button type="submit" class="btn btn-primary">Create Reservation</button>
        </form>
    </div>
</div>

<!-- Logout Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Logout</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to log out?
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
