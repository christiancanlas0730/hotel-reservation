<?php if(session_status() == PHP_SESSION_NONE) session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Receptionist Dashboard</title>
<link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">

<style>
body {
    font-family: 'Segoe UI', sans-serif;
    background: #f8f9fa;
    margin: 0;
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
    transition: all 0.3s ease;
    overflow-x: hidden;
    box-shadow: 2px 0 8px rgba(0,0,0,0.1);
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

/* Table styling */
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

<!-- Sidebar with Quick Actions -->
<div class="sidebar" id="sidebar">
    <a href="/hotel_reservation/public/receptionist/dashboard"><span>Dashboard</span></a>
    <a href="/hotel_reservation/public/receptionist/new_reservation/index.php"><span>New Reservation</span></a>
    <a href="/hotel_reservation/public/receptionist/checkin"><span>Check-in Guest</span></a>
    <a href="/hotel_reservation/public/receptionist/checkout"><span>Check-out Guest</span></a>
    <a href="/hotel_reservation/public/receptionist/rooms"><span>Available Rooms</span></a>
    <a href="/hotel_reservation/public/receptionist/guests"><span>Guests List</span></a>
    <a href="#" data-bs-toggle="modal" data-bs-target="#logoutModal"><span>Logout</span></a>
</div>

<!-- Main content -->
<div class="content">
    <div class="card">
        <h2>Receptionist Dashboard</h2>
        <p>Welcome, <?= htmlspecialchars($_SESSION['user']['username']) ?>!</p>

        <!-- =========================
             QUICK STATS / KPIs
        ========================= -->
        <div class="dashboard-buttons" style="display:flex; flex-wrap:wrap; gap:12px;">
            <div style="flex:1; min-width:140px;">
                <div class="card text-center p-2" style="background:#0d6efd; color:#fff;">
                    <h6 style="margin-bottom:5px; font-size:0.9rem;">Total Rooms</h6>
                    <h3 style="font-size:1.5rem; margin:0;"><?= $totalRooms ?? 0 ?></h3>
                </div>
            </div>
            <div style="flex:1; min-width:140px;">
                <div class="card text-center p-2" style="background:#28a745; color:#fff;">
                    <h6 style="margin-bottom:5px; font-size:0.9rem;">Occupied Rooms</h6>
                    <h3 style="font-size:1.5rem; margin:0;"><?= $occupiedRooms ?? 0 ?></h3>
                </div>
            </div>
            <div style="flex:1; min-width:140px;">
                <div class="card text-center p-2" style="background:#ffc107; color:#000;">
                    <h6 style="margin-bottom:5px; font-size:0.9rem;">Pending Reservations</h6>
                    <h3 style="font-size:1.5rem; margin:0;"><?= $pendingReservations ?? 0 ?></h3>
                </div>
            </div>
            <div style="flex:1; min-width:140px;">
                <div class="card text-center p-2" style="background:#6c757d; color:#fff;">
                    <h6 style="margin-bottom:5px; font-size:0.9rem;">Check-outs Today</h6>
                    <h3 style="font-size:1.5rem; margin:0;"><?= $checkoutsToday ?? 0 ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- =========================
         RECENT RESERVATIONS
    ========================= -->
    <div class="card">
        <h3>Recent Reservations</h3>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Guest</th>
                    <th>Room Number</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($reservations)): ?>
                    <?php foreach ($reservations as $res): ?>
                        <?php
                            $status = $res['status'] ?? '';
                            $statusLabel = $status ? ucwords(str_replace('_', ' ', $status)) : 'N/A';
                            $badgeStyle = match($status) {
                                'pending' => 'background:#ffc107; color:#000;',
                                'checked_in' => 'background:#28a745; color:#fff;',
                                'checked_out' => 'background:#6c757d; color:#fff;',
                                default => 'background:#dc3545; color:#fff;',
                            };
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($res['username'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($res['room_number'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($res['check_in'] ?? '') ?></td>
                            <td><?= htmlspecialchars($res['check_out'] ?? '') ?></td>
                            <td>
                                <span class="badge-status" style="<?= $badgeStyle ?>">
                                    <?= htmlspecialchars($statusLabel) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($status == 'pending'): ?>
                                    <a href="/hotel_reservation/public/receptionist/checkin/<?= htmlspecialchars($res['id']) ?>" class="btn btn-primary btn-sm me-1">
                                        Check-in
                                    </a>
                                <?php elseif ($status == 'checked_in'): ?>
                                    <a href="/hotel_reservation/public/receptionist/checkout/<?= htmlspecialchars($res['id']) ?>" class="btn btn-danger btn-sm">
                                        Check-out
                                    </a>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No reservations found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
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
