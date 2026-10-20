<?php if(session_status() == PHP_SESSION_NONE) session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>All Reservations</title>

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

/* Cards */
.card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    padding: 20px;
    margin-bottom: 25px;
}

/* Table */
.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(0,0,0,.05);
}

.badge-status {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.85em;
    font-weight: bold;
    display: inline-block;
}

/* Back Button */
.back-btn {
    display:inline-block;
    padding:10px 20px;
    background-color:#6c757d;
    color:#fff;
    text-decoration:none;
    border-radius:5px;
    margin-top:20px;
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

<!-- Sidebar with full links -->
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
<div class="card">
    <h2>All Reservations</h2>
    <p>Receptionist view</p>

    <?php if(empty($reservations)): ?>
        <p>No reservations yet.</p>
    <?php else: ?>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Guest</th>
                    <th>Room</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($reservations as $r): ?>
                <?php
                    $status = $r['status'] ?? '';
                    $statusLabel = $status ? ucwords(str_replace('_', ' ', $status)) : 'N/A';
                    $badgeStyle = '';
                    switch($status){
                        case 'pending': $badgeStyle = 'background:#ffc107; color:#000;'; break;
                        case 'checked_in': $badgeStyle = 'background:#28a745; color:#fff;'; break;
                        case 'checked_out': $badgeStyle = 'background:#6c757d; color:#fff;'; break;
                        default: $badgeStyle = 'background:#dc3545; color:#fff;';
                    }
                ?>
                <tr>
                    <td><?= htmlspecialchars($r['id'] ?? '') ?></td>
                    <td><?= htmlspecialchars($r['username'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($r['room_number'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($r['check_in'] ?? '') ?></td>
                    <td><?= htmlspecialchars($r['check_out'] ?? '') ?></td>
                    <td>
                        <span class="badge-status" style="<?= $badgeStyle ?>">
                            <?= htmlspecialchars($statusLabel) ?>
                        </span>
                    </td>
                    <td>
                        <?php if($status == 'pending'): ?>
                            <a href="/hotel_reservation/public/receptionist/checkin/<?= htmlspecialchars($r['id']) ?>" class="btn btn-primary btn-sm me-1">Check-in</a>
                        <?php elseif($status == 'checked_in'): ?>
                            <a href="/hotel_reservation/public/receptionist/checkout/<?= htmlspecialchars($r['id']) ?>" class="btn btn-danger btn-sm">Check-out</a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

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
const content = document.querySelector('.content');

toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('collapsed');

    if(sidebar.classList.contains('collapsed')){
        topNavbar.style.left = '60px';
        topNavbar.style.width = 'calc(100% - 60px)';
        content.style.marginLeft = '60px';
    } else {
        topNavbar.style.left = '220px';
        topNavbar.style.width = 'calc(100% - 220px)';
        content.style.marginLeft = '220px';
    }
});
</script>

</body>
</html>
