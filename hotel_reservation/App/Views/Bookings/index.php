<?php ?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Bookings</title>

<!-- Offline Bootstrap -->
<link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">

<style>
body {
    font-family: 'Segoe UI', sans-serif;
    background: #f8f9fa;
    padding: 20px;
}

h2 {
    margin-bottom: 20px;
    font-weight: 600;
}

.filter-bar {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 20px;
    align-items: center;
}

.filter-bar label {
    margin-bottom: 0;
    font-weight: 500;
}

.filter-bar select,
.filter-bar input {
    padding: 5px 10px;
    border-radius: 5px;
    border: 1px solid #ced4da;
}

table {
    width: 100%;
    border-collapse: collapse;
}

thead th {
    background: #007bff;
    color: #fff;
    padding: 10px;
}

tbody td {
    padding: 10px;
    vertical-align: middle;
}

.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(0,0,0,.05);
}

.btn-delete {
    background-color: #dc3545;
    color: #fff;
    text-decoration: none;
    padding: 5px 10px;
    border-radius: 4px;
    display: inline-block;
}

.btn-delete:hover {
    background-color: #c82333;
    color: #fff;
}

.back-btn {
    display: inline-block;
    padding: 10px 20px;
    background-color: #007bff;
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
}

.back-btn:hover {
    background-color: #0069d9;
    color: #fff;
}

@media(max-width:768px){
    .filter-bar {
        flex-direction: column;
        align-items: stretch;
    }

    .filter-bar select,
    .filter-bar input {
        width: 100%;
    }
}
</style>
</head>
<body>

<div class="container">

<h2>Bookings</h2>

<!-- Filter + Search -->
<div class="filter-bar">
    <div>
        <label for="status">Filter by Status:</label>
        <select id="status" class="form-select">
            <option value="">All</option>
            <option value="checked_in">Checked-in</option>
            <option value="checked_out">Checked-out</option>
        </select>
    </div>

    <div>
        <label for="search">Search Customer:</label>
        <input type="text" id="search" class="form-control" placeholder="Type customer name...">
    </div>
</div>

<!-- Bookings Table -->
<table class="table table-striped table-bordered" id="bookingsTable">
<thead>
<tr>
    <th>ID</th>
    <th>Customer</th>
    <th>Room</th>
    <th>Check In</th>
    <th>Check Out</th>
    <th>Status</th>
    <th>Actions</th>
</tr>
</thead>
<tbody>
<?php foreach($data['bookings'] as $booking): ?>
<tr data-customer="<?= strtolower($booking['customer_name']) ?>" data-status="<?= $booking['status'] ?? 'pending' ?>">
    <td><?= $booking['id'] ?></td>
    <td><?= htmlspecialchars($booking['customer_name']) ?></td>
    <td><?= $booking['room_number'] ?></td>
    <td><?= $booking['check_in'] ?? '-' ?></td>
    <td><?= $booking['check_out'] ?? '-' ?></td>
    <td><?= $booking['status'] ?? 'pending' ?></td>
    <td>
        <a href="/hotel_reservation/public/bookings/delete/<?= $booking['id'] ?>" 
           onclick="return confirm('Delete booking?')" class="btn-delete">
           Delete
        </a>
    </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<!-- Back to Dashboard -->
<div class="text-center mt-4">
    <a href="/hotel_reservation/public/receptionist/dashboard" class="back-btn">
        Back to Dashboard
    </a>
</div>

</div>

<!-- Bootstrap JS -->
<script src="../../bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Live Search + Filter Script -->
<script>
const searchInput = document.getElementById('search');
const statusSelect = document.getElementById('status');
const table = document.getElementById('bookingsTable');
const rows = Array.from(table.getElementsByTagName('tbody')[0].getElementsByTagName('tr'));

function filterTable() {
    const searchText = searchInput.value.toLowerCase();
    const statusFilter = statusSelect.value;

    rows.forEach(row => {
        const customer = row.dataset.customer;
        const status = row.dataset.status || 'pending';
        const matchSearch = customer.includes(searchText);
        const matchStatus = !statusFilter || status === statusFilter;

        row.style.display = (matchSearch && matchStatus) ? '' : 'none';
    });
}

searchInput.addEventListener('input', filterTable);
statusSelect.addEventListener('change', filterTable);
</script>

</body>
</html>
