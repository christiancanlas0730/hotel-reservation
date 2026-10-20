<?php if(session_status() == PHP_SESSION_NONE) session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Guest Dashboard - Available Rooms</title>
<link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">

<style>
body { font-family:'Segoe UI',sans-serif; margin:0; background:#f5f5f5; }

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
    z-index: 999;
}
.sidebar.collapsed { width: 60px; background-color: #0b5ed7; }
.sidebar a { display: block; color: #fff; padding: 12px 20px; text-decoration: none; transition: all 0.3s ease; }
.sidebar a:hover, .sidebar a.active { background-color: #0a58ca; font-weight: bold; }
.sidebar.collapsed a span { display: none; }

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
    min-width: 150px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    border-radius: 4px;
    z-index: 1002;
}
.top-navbar .profile-dropdown-content a {
    color: #333;
    padding: 10px 15px;
    text-decoration: none;
    display: block;
}
.top-navbar .profile-dropdown-content a:hover,
.top-navbar .profile-dropdown-content a.active {
    background-color: #f0f0f0;
    font-weight: bold;
}
.top-navbar .profile-dropdown.show .profile-dropdown-content {
    display: block;
}
.sidebar.collapsed ~ .top-navbar {
    left: 60px;
    width: calc(100% - 60px);
}

/* ------------------------
   MAIN CONTENT
------------------------ */
.content {
    margin-left: 220px;
    margin-top: 60px;
    padding: 20px;
    transition: margin-left 0.3s ease;
}
.sidebar.collapsed ~ .content { margin-left: 60px; }

/* ------------------------
   ROOMS CARDS
------------------------ */
.rooms-container { display:flex; flex-wrap:wrap; gap:20px; justify-content:center; margin-bottom:30px; }
.room-card { background:#fff; border-radius:0.5rem; padding:1rem; width:250px; box-shadow:0 4px 15px rgba(0,0,0,0.15); transition: transform 0.3s ease, box-shadow 0.3s ease; display:flex; flex-direction:column; gap:0.5rem; }
.room-card:hover { transform:translateY(-5px); box-shadow:0 8px 25px rgba(0,0,0,0.25); }
.room-card img { width:100%; height:150px; object-fit:cover; border-radius:0.5rem; margin-bottom:0.5rem; cursor:pointer; }
.room-card .room-number { font-weight:bold; font-size:1.2rem; }
.room-card .room-type { color:#555; font-size:1rem; }
.room-card .room-price { font-size:1.1rem; font-weight:bold; }
.room-card .room-status { font-weight:bold; }
.room-card .room-status.available { color:green; }
.room-card .room-status.occupied { color:red; }
.room-card button { margin-top:0.5rem; }

/* Search + Filter */
.search-filter { display:flex; gap:12px; align-items:center; flex-wrap:wrap; margin-bottom:15px; }
input[type="text"], input[type="date"], input, select { padding:10px; border-radius:8px; border:1px solid #ccc; width:100%; }
button.btn-primary { background:#007bff; color:#fff; border:none; padding:0.6rem 1rem; border-radius:5px; cursor:pointer; }
button:disabled { background:gray; cursor:not-allowed; color:#fff; }

/* Back Button */
.back-btn { display:inline-block; margin-top:20px; text-align:center; background:#6c757d; color:#fff; padding:0.6rem 1rem; border-radius:5px; text-decoration:none; }

/* E-wallet & Card inputs */
#ewalletOptionsSection select,
#ewalletOptionsSection input,
#cardDetailsSection input { width: 100%; margin-bottom: 8px; padding: 8px; border-radius: 6px; box-sizing: border-box; }

/* Responsive */
@media(max-width: 576px){
    #bookingModal .modal-content { max-width: 95%; padding: 10px; }
    #bookingModal .modal-header { font-size: 1rem; }
    #bookingModal button.btn-primary { padding: 0.5rem 0.8rem; font-size: 0.9rem; }
}
@media(max-width:768px){ 
    .room-card { width:100%; } 
    .sidebar { width:0; }
    .sidebar.collapsed { width:0; }
    .top-navbar { left:0; width:100%; }
    .content { margin-left:0; }
}
</style>
</head>
<body>

<!-- Top Navbar -->
<div class="top-navbar" id="topNavbar">
    <button class="btn" id="sidebarToggle">&#9776;</button>
    <div class="profile-dropdown">
        
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
    <a href="/hotel_reservation/public/guest/rooms"><span> Rooms</span></a>
    <a href="#" data-bs-toggle="modal" data-bs-target="#logoutModal"><span>Logout</span></a>
</div>

<!-- Main content -->
<div class="content">
<div class="container">
    <h2>Available Rooms</h2>
    <p>Search rooms and book instantly.</p>

    <!-- Search + Filter -->
    <div class="search-filter">
        <input type="text" id="searchInput" placeholder="Search room type...">
        <label><input type="checkbox" id="availableOnly"> Show Available Only</label>
    </div>

    <?php if(empty($rooms)): ?>
        <p>No rooms are available at the moment.</p>
    <?php else: ?>
        <div class="rooms-container" id="roomsContainer">
            <?php 
            foreach($rooms as $room):
                $status = strtolower($room['status'] ?? '');
                $isAvailable = $status === 'available';
                $roomFolder = $_SERVER['DOCUMENT_ROOT'] . '/hotel_reservation/public/assets/rooms/' . $room['id'];
                $images = [];
                if(is_dir($roomFolder)){
                    foreach(scandir($roomFolder) as $file){
                        if(in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['jpg','jpeg','png','webp'])){
                            $images[] = '/hotel_reservation/public/assets/rooms/' . $room['id'] . '/' . $file;
                        }
                    }
                }
                if(empty($images)) $images[] = 'https://via.placeholder.com/250x150?text=Room+Image';
            ?>
            <div class="room-card" data-type="<?= htmlspecialchars(strtolower($room['type'] ?? '')) ?>" data-status="<?= $status ?>">
                <img src="<?= htmlspecialchars($images[0]) ?>" alt="Room Image" class="room-img" data-room="<?= $room['id'] ?>">
                <div class="room-number">Room #<?= htmlspecialchars($room['room_number'] ?? '') ?></div>
                <div class="room-type"><?= htmlspecialchars($room['type'] ?? '') ?></div>
                <div class="room-price">₱<?= htmlspecialchars($room['price'] ?? '') ?></div>
                <div class="room-status <?= $isAvailable?'available':'occupied' ?>"><?= $isAvailable?'Available':'Occupied' ?></div>
                <?php if($isAvailable): ?>
                    <button class="btn-primary book-btn" data-room-id="<?= $room['id'] ?>">Book</button>
                <?php else: ?>
                    <button disabled>Not Available</button>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>

        <div style="text-align:center;">
            <a href="/hotel_reservation/public/guest/dashboard" class="back-btn"> Back to Dashboard</a>
        </div>
    <?php endif; ?>
</div>
</div>

<!-- Booking Modal (Bootstrap version) -->
<div class="modal fade" id="bookingModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Book Room</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="modalBookingForm" method="POST" action="/hotel_reservation/public/guest/book">
            <input type="hidden" name="room_id" id="modalRoomId">

            <!-- Autofilled Guest Info -->
            <label>Name</label>
            <input type="text" name="guest_name" id="guestName"
                   value="<?= htmlspecialchars($_SESSION['user']['username'] ?? '') ?>"
                   placeholder="Full Name" class="form-control mb-2" required>

            <label>Email</label>
            <input type="email" name="guest_email" id="guestEmail"
                   value="<?= htmlspecialchars($_SESSION['user']['email'] ?? '') ?>"
                   placeholder="Email" class="form-control mb-2" required>

            <label>Phone</label>
            <input type="tel" name="guest_phone" id="guestPhone"
                   value="<?= htmlspecialchars($_SESSION['user']['phone'] ?? '') ?>"
                   placeholder="Phone" class="form-control mb-2" required>

            <label>Check-in</label>
            <input type="date" name="check_in" id="checkIn" class="form-control mb-2" required>

            <label>Check-out</label>
            <input type="date" name="check_out" id="checkOut" class="form-control mb-2" required>
            
            <label>Payment Method</label>
            <div class="d-flex gap-2 mb-2">
                <label><input type="radio" name="payment_method" value="ewallet" id="modalEwallet" checked> E-wallet</label>
                <label><input type="radio" name="payment_method" value="card" id="modalCard"> Credit/Debit Card</label>
            </div>

           <div id="ewalletOptionsSection">
              <select name="ewallet_option" id="ewalletOption" class="form-select mb-2">
                  <option value="gcash">GCash</option>
                  <option value="paypal">PayPal</option>
                  <option value="paymaya">PayMaya</option>
              </select>
              <input type="text" name="ewallet_details" id="ewalletDetails" placeholder="Enter your E-wallet number/email" class="form-control" required>
           </div>

           <div id="cardDetailsSection" style="display:none;">
               <label>Card Number</label>
               <input type="text" name="card_number" class="form-control mb-2" placeholder="XXXX-XXXX-XXXX-XXXX">
               <label>Expiry</label>
               <input type="month" name="card_expiry" class="form-control mb-2">
               <label>CVV</label>
               <input type="text" name="card_cvv" maxlength="4" class="form-control mb-2">
           </div>

           <button type="submit" class="btn btn-primary mt-2 w-100">Confirm Booking</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Profile Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="/hotel_reservation/public/guest/update_profile" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="profileModalLabel">My Profile</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
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
const links = sidebar.querySelectorAll('a');
const content = document.querySelector('.content');
const topNavbar = document.getElementById('topNavbar');
const profileDropdown = document.querySelector('.profile-dropdown');
const profileBtn = document.getElementById('profileBtn');
const myProfileLink = document.getElementById('myProfileLink');

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

// Sidebar highlight
links.forEach(link => {
    if(window.location.href.includes(link.getAttribute('href'))){
        link.classList.add('active');
    } else {
        link.classList.remove('active');
    }
});

// Profile dropdown
profileBtn.addEventListener('click', () => profileDropdown.classList.toggle('show'));
window.addEventListener('click', e => {
    if(!profileDropdown.contains(e.target)) profileDropdown.classList.remove('show');
});

// Highlight My Profile if modal opened
myProfileLink.addEventListener('click', () => {
    myProfileLink.classList.add('active');
});

// ------------------------
// Booking Modal Function
// ------------------------
const bookBtns = document.querySelectorAll('.book-btn');
const modalRoomId = document.getElementById('modalRoomId');
const ewalletRadio = document.getElementById('modalEwallet');
const cardRadio = document.getElementById('modalCard');
const ewalletSection = document.getElementById('ewalletOptionsSection');
const cardSection = document.getElementById('cardDetailsSection');

bookBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        modalRoomId.value = btn.getAttribute('data-room-id');
        const bookingModal = new bootstrap.Modal(document.getElementById('bookingModal'));
        bookingModal.show();
    });
});

// Payment toggle
ewalletRadio.addEventListener('change', () => {
    if(ewalletRadio.checked){
        ewalletSection.style.display = 'block';
        cardSection.style.display = 'none';
    }
});
cardRadio.addEventListener('change', () => {
    if(cardRadio.checked){
        ewalletSection.style.display = 'none';
        cardSection.style.display = 'block';
    }
});

// Simple search + filter
const searchInput = document.getElementById('searchInput');
const availableOnly = document.getElementById('availableOnly');
const roomsContainer = document.getElementById('roomsContainer');

function filterRooms() {
    const text = searchInput.value.toLowerCase();
    const showAvailable = availableOnly.checked;

    document.querySelectorAll('.room-card').forEach(card => {
        const type = card.dataset.type.toLowerCase();
        const status = card.dataset.status.toLowerCase();
        const matchText = type.includes(text);
        const matchStatus = !showAvailable || status === 'available';
        card.style.display = matchText && matchStatus ? 'flex' : 'none';
    });
}
searchInput.addEventListener('input', filterRooms);
availableOnly.addEventListener('change', filterRooms);
</script>
</body>
</html>
