<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Management System</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">

    <style>
/* Minimal Hotel Management - Left Sidebar Navigation */

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

:root {
    --primary-color: #2563eb;
    --primary-dark: #1d4ed8;
    --sidebar-bg: #1e293b;
    --sidebar-active: #334155;
    --light-bg: #f8fafc;
    --white: #ffffff;
    --text-dark: #1e293b;
    --text-light: #64748b;
    --border-color: #e2e8f0;
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
    --radius-md: 0.5rem;
    --transition: all 0.3s ease;
}

body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    line-height: 1.6;
    color: var(--text-dark);
    background: var(--light-bg);
    overflow-x: hidden;
}

/* Sidebar */
.sidebar {
    position: fixed;
    left: 0;
    top: 0;
    width: 260px;
    height: 100vh;
    background: var(--sidebar-bg);
    color: white;
    box-shadow: var(--shadow-lg);
    z-index: 50;
    transition: var(--transition);
}

.sidebar-header {
    padding: 1.5rem;
    border-bottom: 1px solid var(--sidebar-active);
    display: flex;
    align-items: center;
    gap: 1rem;
}

.logo {
    font-size: 1.25rem;
    font-weight: 700;
    color: white;
    text-decoration: none;
}

.logo i { font-size: 1.5rem; }

.sidebar-menu {
    list-style: none;
    padding: 1rem 0;
}

.sidebar-item {
    padding: 0.75rem 1.5rem;
    color: rgba(255,255,255,0.8);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    transition: var(--transition);
    border-left: 3px solid transparent;
}

.sidebar-item:hover, .sidebar-item.active {
    background: var(--sidebar-active);
    color: white;
    border-left-color: var(--primary-color);
}

.sidebar-item i { font-size: 1.1rem; }

/* Hierarchical sidebar (for role-specific grouping) */
.sidebar-details {
    margin: 0;
}

.sidebar-details summary {
    list-style: none;
}

.sidebar-details summary::-webkit-details-marker {
    display: none;
}

.sidebar-group {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.sidebar-submenu {
    list-style: none;
    padding: 0;
    margin: 0;
    background: rgba(255,255,255,0.02);
}

.sidebar-subitem {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.6rem 1.5rem 0.6rem 3rem;
    color: rgba(255,255,255,0.85);
    text-decoration: none;
    transition: var(--transition);
}

.sidebar-subitem:hover, .sidebar-subitem.active {
    background: var(--sidebar-active);
    color: white;
    border-left: 3px solid var(--primary-color);
}

/* Main Content */
.main-content {
    margin-left: 260px;
    min-height: 100vh;
    padding: 1.5rem;
}

.container {
    max-width: 100%;
    margin: 0;
    padding: 0;
}

/* Top Bar (Mobile) */
.topbar {
    background: var(--white);
    padding: 1rem 1.5rem;
    box-shadow: var(--shadow-lg);
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    border-radius: var(--radius-md);
}

.menu-toggle {
    display: none;
    background: none;
    border: none;
    font-size: 1.25rem;
    color: var(--text-dark);
    cursor: pointer;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.5rem 1rem;
    background: var(--light-bg);
    border-radius: var(--radius-md);
}

/* Cards & Buttons */
.card {
    background: var(--white);
    border-radius: var(--radius-md);
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    border: 1px solid var(--border-color);
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: var(--radius-md);
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
    font-size: 0.9rem;
}

.btn-primary {
    background: var(--primary-color);
    color: white;
}

.btn-primary:hover {
    background: var(--primary-dark);
    transform: translateY(-1px);
}

.btn-success { background: #10b981; color: white; }
.btn-danger { background: #ef4444; color: white; }
.btn-small { padding: 0.5rem 1rem; font-size: 0.8rem; }

/* Tables */
table {
    width: 100%;
    border-collapse: collapse;
    background: var(--white);
    border-radius: var(--radius-md);
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

th {
    background: var(--primary-color);
    color: white;
    padding: 1rem;
    text-align: left;
    font-weight: 600;
}

td {
    padding: 1rem;
    border-bottom: 1px solid var(--border-color);
}

tr:hover { background: #f8fafc; }

/* Responsive Sidebar */
@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%);
    }
    
    .sidebar.open {
        transform: translateX(0);
    }
    
    .main-content {
        margin-left: 0;
    }
    
    .menu-toggle {
        display: block;
    }
    
    .topbar {
        margin-bottom: 1rem;
    }
}

/* Forms & Utilities */
.form-group { margin-bottom: 1.25rem; }
.form-group label { 
    display: block; 
    margin-bottom: 0.5rem; 
    font-weight: 600; 
}

.form-group input, .form-group select {
    width: 100%;
    padding: 0.875rem;
    border: 2px solid var(--border-color);
    border-radius: var(--radius-md);
    font-size: 1rem;
}

.form-group input:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
}

.mb-4 { margin-bottom: 1.5rem; }
</style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <a href="/hotel_reservation/public/" class="logo">
                <i class="fas fa-hotel"></i>
                <span>Hotel Pro</span>
            </a>
        </div>
        <ul class="sidebar-menu">
            <?php if(isset($_SESSION['user'])): ?>
                <?php 
                $role_id = $_SESSION['user']['role_id'];
                $activeUrl = $_GET['url'] ?? '';
                $menu_items = [
                    1 => [
                        ['url' => 'dashboard', 'icon' => 'tachometer-alt', 'label' => 'Dashboard'],
                        ['url' => 'rooms', 'icon' => 'door-open', 'label' => 'Rooms'],
                        ['url' => 'customers', 'icon' => 'users', 'label' => 'Customers'],
                        ['url' => 'bookings', 'icon' => 'calendar-check', 'label' => 'Bookings']
                    ],
                    2 => [
                        ['url' => 'receptionist/dashboard', 'icon' => 'clipboard-list', 'label' => 'Dashboard'],
                        ['url' => 'receptionist/reservations', 'icon' => 'list-check', 'label' => 'Reservations'],
                        ['url' => 'bookings/create', 'icon' => 'calendar-plus', 'label' => 'Book Room for Guest'],
                        ['url' => 'bookings/guests', 'icon' => 'users', 'label' => 'View Guests'],
                        [
                            'label' => 'Rooms',
                            'icon' => 'door-open',
                            'children' => [
                                ['url' => 'receptionist/rooms', 'icon' => 'bed', 'label' => 'Available Rooms'],
                                ['url' => 'rooms/create', 'icon' => 'plus', 'label' => 'Add Rooms'],
                            ]
                        ]
                    ],
                    3 => [
                        ['url' => 'guest/dashboard', 'icon' => 'house', 'label' => 'Dashboard'],
                        ['url' => 'guest/rooms', 'icon' => 'bed', 'label' => 'Book Room']
                    ]
                ];
                ?>
                <?php foreach($menu_items[$role_id ?? 1] as $item): ?>
                    <?php if(isset($item['children']) && is_array($item['children'])): ?>
                        <?php
                            $anyChildActive = false;
                            foreach($item['children'] as $child){
                                if(($child['url'] ?? '') === $activeUrl){
                                    $anyChildActive = true;
                                    break;
                                }
                            }
                        ?>
                        <li>
                            <details class="sidebar-details" <?= $anyChildActive ? 'open' : '' ?>>
                                <summary class="sidebar-item <?= $anyChildActive ? 'active' : '' ?>">
                                    <i class="fas fa-<?= $item['icon'] ?>"></i>
                                    <span><?= $item['label'] ?></span>
                                </summary>
                                <ul class="sidebar-submenu">
                                    <?php foreach($item['children'] as $child): ?>
                                        <?php $isActive = ($child['url'] ?? '') === $activeUrl; ?>
                                        <li>
                                            <a href="/hotel_reservation/public/<?= $child['url'] ?>" class="sidebar-subitem <?= $isActive ? 'active' : '' ?>">
                                                <i class="fas fa-<?= $child['icon'] ?>"></i>
                                                <span><?= $child['label'] ?></span>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </details>
                        </li>
                    <?php else: ?>
                        <li>
                            <a href="/hotel_reservation/public/<?= $item['url'] ?>" class="sidebar-item <?= $activeUrl == $item['url'] ? 'active' : '' ?>">
                                <i class="fas fa-<?= $item['icon'] ?>"></i>
                                <span><?= $item['label'] ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
                <li style="margin-top: auto; padding-top: 1rem; border-top: 1px solid var(--sidebar-active);">
                    <a href="/hotel_reservation/public/auth/logout" class="sidebar-item">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <button class="menu-toggle" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        
        <div class="topbar">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <h1>Dashboard</h1>
            </div>
            <?php if(isset($_SESSION['user'])): ?>
                <div class="user-info">
                    <i class="fas fa-user-circle" style="font-size: 1.5rem;"></i>
                    <?= htmlspecialchars($_SESSION['user']['username']) ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="container">

