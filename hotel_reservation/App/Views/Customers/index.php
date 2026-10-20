<h2 class="mb-4 text-center"><i class="fas fa-users"></i> Customers</h2>



    <table class="customers-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($data['customers'] as $customer): ?>
            <tr>
                <td><?= htmlspecialchars($customer['id']) ?></td>
                <td><?= htmlspecialchars($customer['name']) ?></td>
                <td><?= htmlspecialchars($customer['email']) ?></td>
                <td><?= htmlspecialchars($customer['phone']) ?></td>
                <td>
                    <a href="/hotel_reservation/public/customers/delete/<?= $customer['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete customer?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Back to Dashboard centered -->
    <div class="text-center mt-4">
        <a href="/hotel_reservation/public/dashboard" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>
</div>

<style>
/* === Page Background === */
body {
    background: #f4f6f8;
    font-family: 'Inter', sans-serif;
}

/* === Floating Card === */
.floating-card {
    max-width: 950px;
    margin: 0 auto 50px auto;
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    padding: 30px;
}

/* === Table Styling === */
.customers-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

.customers-table th, .customers-table td {
    padding: 12px 15px;
    text-align: left;
    font-size: 0.95rem;
    color: #333;
}

.customers-table thead {
    background: #f1f5f9;
    color: #111;
    font-weight: 600;
}

.customers-table tbody tr {
    border-bottom: 1px solid #e5e7eb;
    transition: background 0.3s;
}

.customers-table tbody tr:nth-child(even) {
    background: #f9fafb;
}

.customers-table tbody tr:hover {
    background: #e2e8f0;
}

/* === Buttons Styling === */
.btn-primary, .btn-danger, .btn-secondary {
    border-radius: 8px;
    padding: 6px 14px;
    font-size: 0.9rem;
    text-decoration: none;
    display: inline-block;
}

.btn-primary {
    background: #0d6efd;
    color: #fff;
}

.btn-primary:hover {
    background: #0b5ed7;
}

.btn-danger {
    background: #dc3545;
    color: #fff;
}

.btn-danger:hover {
    background: #bb2d3b;
}

.btn-secondary {
    background: #6c757d;
    color: #fff;
}

.btn-secondary:hover {
    background: #5a6268;
}
</style>
