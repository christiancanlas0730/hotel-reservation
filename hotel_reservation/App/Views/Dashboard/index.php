<?php
if (session_status() == PHP_SESSION_NONE) session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard - 10k Hotel</title>
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">

<style>
body { margin:0; font-family:'Segoe UI', sans-serif; overflow-x:hidden; }

/* Sidebar - Light theme */
.sidebar {
    position: fixed;
    top:0; left:0;
    width:220px; height:100%;
    background:#f5f5f5; color:#333;
    overflow-y:auto;
    transition: transform 0.3s ease;
    z-index:1000;
    border-right:1px solid #ddd;
}
.sidebar.folded { transform: translateX(-220px); }

.sidebar-header {
    display:flex; align-items:center; justify-content:space-between;
    padding:1rem; border-bottom:1px solid #ddd;
}
.sidebar-header .logo { color:#333; text-decoration:none; font-size:1.2rem; font-weight:bold; }

.sidebar-menu { list-style:none; padding:0; margin:0; }
.sidebar-item, .sidebar-subitem {
    display:flex; align-items:center; padding:0.75rem 1rem;
    color:#333; text-decoration:none; transition:background 0.2s;
}
.sidebar-item:hover, .sidebar-subitem:hover { background-color:#e0e0e0; }
.sidebar-item.active, .sidebar-subitem.active { background-color:#d0d0d0; font-weight:bold; }
.sidebar-submenu { list-style:none; padding-left:1rem; }
details.sidebar-details > summary {
    list-style:none; cursor:pointer;
    display:flex; align-items:center; justify-content:space-between;
    padding:0.75rem 1rem;
}

.main-content {
    margin-left:220px;
    transition: margin-left 0.3s ease;
    padding:1rem;
}
.main-content.expanded { margin-left:0; }

.topbar { display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; }
.menu-toggle {
    background:none; border:none; font-size:1.8rem; cursor:pointer;
    transition: transform 0.3s ease;
}
.menu-toggle.open { transform: rotate(90deg); }
.user-info { display:flex; align-items:center; gap:0.5rem; }

/* Stats Cards */
.stats-container { display:flex; gap:1rem; flex-wrap:wrap; margin-bottom:1rem; transition: all 0.3s ease; }
.stats-card {
    flex:1; min-width:200px; color:#fff;
    border-radius:0.25rem; padding:1rem; text-align:center;
    transition: all 0.3s ease;
}
.stats-card .icon { font-size:2rem; margin-bottom:0.5rem; }
.stats-card .number { font-size:2rem; margin-bottom:0.5rem; font-weight:bold; }
.stats-card.rooms { background:#007bff; }
.stats-card.customers { background:#28a745; }
.stats-card.bookings { background:#ffc107; color:#000; }

.card { border-radius:0.25rem; padding:1rem; margin-bottom:1rem; box-shadow:0 0.125rem 0.25rem rgba(0,0,0,0.075); }
.card-header { font-weight:bold; color:#fff; padding:0.5rem 1rem; border-radius:0.25rem 0.25rem 0 0; }

table { width:100%; border-collapse:collapse; }
table th, table td { padding:0.5rem; border:1px solid #ddd; }
table th { background:#007bff; color:#fff; }

@media (max-width:768px){
    .sidebar { transform:translateX(-220px); }
    .main-content { margin-left:0; }
}
</style>
</head>
<body>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <a href="/hotel_reservation/public/" class="logo">10k Hotel</a>
    </div>

    <ul class="sidebar-menu">
        <?php if(isset($_SESSION['user'])):
            $role_id = $_SESSION['user']['role_id'];
            $activeUrl = $_GET['url'] ?? '';
            $menu_items = [
                1 => [
                    ['url'=>'dashboard','label'=>'Dashboard'],
                    ['url'=>'rooms','label'=>'Rooms'],
                    ['url'=>'customers','label'=>'Customers'],
                    ['url'=>'bookings','label'=>'Bookings']
                ],
                2 => [
                    ['url'=>'receptionist/dashboard','label'=>'Dashboard'],
                    ['url'=>'receptionist/reservations','label'=>'Reservations'],
                    ['url'=>'bookings/create','label'=>'Book Room for Guest'],
                    ['url'=>'bookings/guests','label'=>'View Guests'],
                    ['label'=>'Rooms','children'=>[
                        ['url'=>'receptionist/rooms','label'=>'Available Rooms'],
                        ['url'=>'rooms/create','label'=>'Add Rooms'],
                    ]]
                ],
                3 => [
                    ['url'=>'guest/dashboard','label'=>'Dashboard'],
                    ['url'=>'guest/rooms','label'=>'Book Room']
                ]
            ];
        ?>

        <?php foreach($menu_items[$role_id ?? 1] as $item): ?>
            <?php if(isset($item['children'])):
                $anyChildActive=false;
                foreach($item['children'] as $child){
                    if(($child['url'] ?? '') === $activeUrl){ $anyChildActive=true; break; }
                }
            ?>
            <li>
                <details class="sidebar-details <?= $anyChildActive?'open':'' ?>">
                    <summary class="sidebar-item <?= $anyChildActive?'active':'' ?>"><?= $item['label'] ?></summary>
                    <ul class="sidebar-submenu">
                        <?php foreach($item['children'] as $child):
                            $isActive=($child['url'] ?? '')===$activeUrl;
                        ?>
                        <li>
                            <a href="/hotel_reservation/public/<?= $child['url'] ?>" class="sidebar-subitem <?= $isActive?'active':'' ?>"><?= $child['label'] ?></a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </details>
            </li>
            <?php else: ?>
            <li>
                <a href="/hotel_reservation/public/<?= $item['url'] ?>" class="sidebar-item <?= $activeUrl==$item['url']?'active':'' ?>"><?= $item['label'] ?></a>
            </li>
            <?php endif; ?>
        <?php endforeach; ?>
            <li style="margin-top:auto; padding-top:1rem; border-top:1px solid #ddd;">
                <a href="/hotel_reservation/public/auth/logout" class="sidebar-item text-danger">Logout</a>
            </li>
        <?php endif; ?>
    </ul>
</aside>

<div class="main-content" id="main-content">
    <div class="topbar">
        <button class="menu-toggle" id="menu-toggle">&#9776;</button>
        <h1>Dashboard</h1>
        <?php if(isset($_SESSION['user'])): ?>
            <div class="user-info"><?= htmlspecialchars($_SESSION['user']['username']) ?></div>
        <?php endif; ?>
    </div>

    <div class="container mt-3">
        <div class="stats-container">
            <div class="stats-card rooms">
                <div class="icon">&#128719;</div>
                <div class="number"><?= $totalRooms ?? '0' ?></div>
                <div>Total Rooms</div>
            </div>
            <div class="stats-card customers">
                <div class="icon">&#128101;</div>
                <div class="number"><?= $totalCustomers ?? '0' ?></div>
                <div>Total Customers</div>
            </div>
            <div class="stats-card bookings">
                <div class="icon">&#128197;</div>
                <div class="number"><?= $totalBookings ?? '0' ?></div>
                <div>Total Bookings</div>
            </div>
        </div>

        <div class="card" style="background:#fff;">
            <div class="card-header">Users</div>
            <table>
                <thead>
                    <tr><th>#</th><th>Username</th><th>Email</th><th>Role</th><th>Created At</th></tr>
                </thead>
                <tbody>
                    <?php foreach($users ?? [] as $i=>$user): ?>
                    <tr>
                        <td><?= $i+1 ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['role_name']) ?></td>
                        <td><?= htmlspecialchars($user['created_at']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="card" style="background:#fff;">
            <div class="card-header">Recent Bookings</div>
            <table>
                <thead>
                    <tr><th>#</th><th>Customer</th><th>Room</th><th>Type</th><th>Check-in</th><th>Check-out</th><th>Status</th></tr>
                </thead>
                <tbody>
                    <?php foreach($recentBookings ?? [] as $i=>$booking): ?>
                    <tr>
                        <td><?= $i+1 ?></td>
                        <td><?= htmlspecialchars($booking['customer_name']) ?></td>
                        <td><?= htmlspecialchars($booking['room_number']) ?></td>
                        <td><?= htmlspecialchars($booking['type']) ?></td>
                        <td><?= htmlspecialchars($booking['check_in']) ?></td>
                        <td><?= htmlspecialchars($booking['check_out']) ?></td>
                        <td><?= ucfirst($booking['status']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
const sidebar = document.getElementById('sidebar');
const mainContent = document.getElementById('main-content');
const menuToggle = document.getElementById('menu-toggle');

// Toggle sidebar only with burger, never on link click
menuToggle.addEventListener('click', () => {
    sidebar.classList.toggle('folded');
    mainContent.classList.toggle('expanded');
    menuToggle.classList.toggle('open');
});

// Keep details open if child is active
document.querySelectorAll('.sidebar-details').forEach(details => {
    if(details.querySelector('.sidebar-subitem.active')) details.setAttribute('open', true);
});
    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</script>

</body>
</html>

