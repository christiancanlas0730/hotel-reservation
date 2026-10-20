<?php if(session_status() == PHP_SESSION_NONE) session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Guest Dashboard</title>
<link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">

<style>
body { font-family:'Segoe UI',sans-serif; margin:0; background:#f5f5f5; }

/* SIDEBAR */
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
    z-index: 999;
}
.sidebar.collapsed { width: 60px; background-color: #0b5ed7; }
.sidebar a { display: block; color: #fff; padding: 12px 20px; text-decoration: none; transition: all 0.3s ease; }
.sidebar a:hover, .sidebar a.active { background-color: #0a58ca; }
.sidebar.collapsed a span { display: none; }

/* TOP NAVBAR */
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
.top-navbar .profile-dropdown {
    margin-left: auto;
    position: relative;
    display: inline-block;
}
.top-navbar .profile-dropdown button {
    background: none;
    border: none;
    font-weight: bold;
    cursor: pointer;
    color: #333;
}
.top-navbar .profile-dropdown-content {
    display: none;
    position: absolute;
    right: 0;
    background-color: #fff;
    min-width: 200px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    border-radius: 8px;
    padding: 5px 0;
    z-index: 1002;
}
.top-navbar .profile-dropdown-content a {
    color: #333;
    padding: 10px 15px;
    text-decoration: none;
    display: block;
}
.top-navbar .profile-dropdown-content a:hover, .top-navbar .profile-dropdown-content a.active {
    background-color: #f0f0f0;
}
.top-navbar .profile-dropdown.show .profile-dropdown-content {
    display: block;
}

/* MAIN CONTENT */
.main-content {
    margin-left: 220px;
    margin-top: 60px;
    padding: 20px;
    transition: margin-left 0.3s ease;
}
.sidebar.collapsed ~ .main-content { margin-left: 60px; }
.sidebar.collapsed ~ .top-navbar { left: 60px; width: calc(100% - 60px); }

/* ROOM CARDS */
.rooms-container { display:flex; flex-wrap:wrap; gap:20px; justify-content:flex-start; margin-bottom:30px; }
.room-card { background:#fff; border-radius:0.5rem; padding:1rem; width:250px; box-shadow:0 4px 15px rgba(0,0,0,0.15); transition: transform 0.3s ease, box-shadow 0.3s ease; display:flex; flex-direction:column; gap:0.5rem; }
.room-card:hover { transform:translateY(-5px); box-shadow:0 8px 25px rgba(0,0,0,0.25); }
.room-card .room-number { font-weight:bold; font-size:1.2rem; }
.room-card .room-dates { color:#555; font-size:0.95rem; }
.status-badge { padding:4px 8px; border-radius:4px; color:white; font-weight:bold; font-size:0.9em; }
.cancel-btn { padding:4px 8px; background:#dc3545; color:white; text-decoration:none; border-radius:4px; font-weight:bold; }

/* Responsive */
@media(max-width:768px){
    .top-navbar, .main-content { left:0; width:100%; }
    .sidebar { width:0; }
    .sidebar.collapsed { width:0; }
    .main-content { margin-left:0; }
    .rooms-container { justify-content:center; }
}
</style>
</head>
<body>

<!-- Top Navbar -->
<div class="top-navbar" id="topNavbar">
    <button class="btn" id="sidebarToggle">&#9776;</button>

    <div class="profile-dropdown">
        <button id="profileBtn">Welcome, <?= htmlspecialchars($_SESSION['user']['username'] ?? 'Guest') ?> &#9662;</button>
        <div class="profile-dropdown-content">
            <a href="#" data-bs-toggle="modal" data-bs-target="#profileModal">My Profile</a>
             <a href="#" data-bs-toggle="modal" data-bs-target="#logoutModal"><span>Logout</span></a>
        </div>
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
<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <a href="/hotel_reservation/public/guest/dashboard"><span>Dashboard</span></a>
    <a href="/hotel_reservation/public/guest/rooms"><span>Rooms</span></a>
    <a href="/hotel_reservation/public/auth/login"><span>Logout</span></a>
</div>

<!-- Main Content -->
<div class="main-content">
    
    <h3 style="margin-bottom:15px;">Your Reservations</h3>
    <div class="rooms-container">
        <?php if(empty($reservations)): ?>
            <p style="color:#6c757d;">No reservations found.</p>
        <?php else: ?>
            <?php foreach($reservations as $res): 
                $statusColors = [
                    'pending' => '#ffc107',
                    'confirmed' => '#28a745',
                    'checked_in' => '#007bff',
                    'checked_out' => '#6c757d',
                    'cancelled' => '#dc3545'
                ];
                $status = $res['status'] ?? 'pending';
                $color = $statusColors[$status] ?? '#6c757d';
            ?>
            <div class="room-card">
                <div class="room-number"><?= $res['room_number'] ?></div>
                <div class="room-dates">Check-in: <?= $res['check_in'] ?><br>Check-out: <?= $res['check_out'] ?></div>
                <div class="status-badge" style="background:<?= $color ?>;"><?= ucfirst(str_replace('_',' ',$status)) ?></div>
                <?php if($res['status']=='pending'): ?>
                    <a href="/hotel_reservation/public/guest/cancel/<?= $res['id'] ?>" class="cancel-btn" onclick="return confirm('Cancel this booking?')">Cancel</a>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Profile Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="/hotel_reservation/public/guest/update_profile" method="POST" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title" id="profileModalLabel">My Profile</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-center">
            <!-- Profile Picture Preview with dark background -->
            <?php 
            $profilePic = $_SESSION['user']['profile_picture'] ?? '';
            $profilePicUrl = $profilePic ? '/hotel_reservation/public/assets/profiles/' . htmlspecialchars($profilePic) : 'https://via.placeholder.com/100?text=Profile';
            ?>
            <div id="profilePicWrapper" style="position:relative; display:inline-block; padding:10px; border-radius:50%; background:rgba(0,0,0,0.05); cursor:pointer; transition: background 0.3s;">
                <img id="profilePreview" src="<?= $profilePicUrl ?>" alt="Profile Picture" style="width:120px; height:120px; border-radius:50%; object-fit:cover; border:2px solid #ddd;">
                <input type="file" id="profilePicture" name="profile_picture" accept="image/*" style="position:absolute; top:0; left:0; width:100%; height:100%; opacity:0; cursor:pointer;">
            </div>

            <div class="mt-3 text-start">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($_SESSION['user']['username'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($_SESSION['user']['email'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">New Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Leave blank to keep current">
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Update Profile</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="../../bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
// Sidebar toggle
const sidebar = document.getElementById('sidebar');
const toggleBtn = document.getElementById('sidebarToggle');
const mainContent = document.querySelector('.main-content');
const topNavbar = document.getElementById('topNavbar');

toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('collapsed');
    if(sidebar.classList.contains('collapsed')){
        topNavbar.style.left = '60px';
        topNavbar.style.width = 'calc(100% - 60px)';
        mainContent.style.marginLeft = '60px';
    } else {
        topNavbar.style.left = '220px';
        topNavbar.style.width = 'calc(100% - 220px)';
        mainContent.style.marginLeft = '220px';
    }
});

// Highlight sidebar dynamically
const links = sidebar.querySelectorAll('a');
links.forEach(link => {
    if(window.location.href.includes(link.getAttribute('href'))){
        link.classList.add('active');
    } else {
        link.classList.remove('active');
    }
});

// Profile dropdown toggle
const profileBtn = document.getElementById('profileBtn');
const profileDropdown = document.querySelector('.profile-dropdown');
profileBtn.addEventListener('click', () => profileDropdown.classList.toggle('show'));
window.addEventListener('click', e => { if(!profileDropdown.contains(e.target)) profileDropdown.classList.remove('show'); });

// Profile picture preview
const profilePictureInput = document.getElementById('profilePicture');
const profilePreview = document.getElementById('profilePreview');
const profileWrapper = document.getElementById('profilePicWrapper');

profilePictureInput.addEventListener('change', e => {
    const file = e.target.files[0];
    if(file){
        const reader = new FileReader();
        reader.onload = e => profilePreview.src = e.target.result;
        reader.readAsDataURL(file);
    }
});

// Hover effect for dark background
profileWrapper.addEventListener('mouseenter', () => profileWrapper.style.background = 'rgba(0,0,0,0.1)');
profileWrapper.addEventListener('mouseleave', () => profileWrapper.style.background = 'rgba(0,0,0,0.05)');
</script>
</body>
</html>
