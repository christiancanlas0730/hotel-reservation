<?php if(!empty($data['error'])): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?= htmlspecialchars($data['error']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-lg border-0" style="border-radius: 20px; overflow: hidden;">
            <div class="card-header bg-gradient text-white text-center py-4" style="background: linear-gradient(135deg, #d4af37, #f3d67a);">
                <i class="fas fa-door-open fa-2x mb-2"></i>
                <h2 class="mb-0 fw-bold">Add New Room</h2>
                <p class="mb-0 opacity-75">Complete details to add a premium room to inventory</p>
            </div>
            <div class="card-body p-5">
                <form method="POST" id="roomForm">
                    <div class="mb-4">
                        <label class="form-label fw-bold mb-3">
                            <i class="fas fa-hashtag text-warning me-2"></i>
                            Room Number
                        </label>
                        <input type="text" class="form-control form-control-lg shadow-sm" name="room_number" 
                               placeholder="e.g. 101, Suite A-5" maxlength="20" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold mb-3">
                            <i class="fas fa-bed text-primary me-2"></i>
                            Room Type
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-warning">
                                <i class="fas fa-layer-group"></i>
                            </span>
                            <input type="text" class="form-control form-control-lg shadow-sm" name="type" 
                                   placeholder="e.g. Deluxe Suite, Standard Single" maxlength="50" required>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-bold mb-3">
                            <i class="fas fa-dollar-sign text-success me-2"></i>
                            Nightly Price (USD)
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-success text-white">$</span>
                            <input type="number" class="form-control form-control-lg shadow-sm" name="price" 
                                   step="0.01" min="50" max="5000" placeholder="0.00" required>
                            <span class="input-group-text bg-light">.00</span>
                        </div>
                    </div>

                    <div class="d-grid gap-3">
                        <button type="submit" class="btn btn-warning btn-lg fw-bold shadow-lg px-5 py-3 border-0">
                            <i class="fas fa-plus-circle me-2"></i>
                            Add Room to Inventory
                        </button>
                        <a href="/hotel_reservation/public/rooms" class="btn btn-outline-secondary btn-lg fw-semibold px-4">
                            <i class="fas fa-arrow-left me-2"></i>
                            Back to Rooms
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('roomForm').addEventListener('submit', function(e) {
    const price = document.querySelector('input[name="price"]').value;
    if (price < 50 || price > 5000) {
        e.preventDefault();
        alert('Price must be between $50 and $5000');
    }
});
</script>

<style>
:root {
    --gold-primary: #d4af37;
    --gold-secondary: #f3d67a;
    --shadow-gold: 0 10px 30px rgba(212, 175, 55, 0.3);
}

.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
}

.form-control:focus {
    border-color: var(--gold-primary);
    box-shadow: 0 0 0 0.25rem rgba(212, 175, 55, 0.25);
}

.btn-warning {
    background: linear-gradient(135deg, var(--gold-primary), var(--gold-secondary));
    border-color: var(--gold-primary);
}

.btn-warning:hover {
    background: linear-gradient(135deg, #b8962e, var(--gold-primary));
    transform: translateY(-2px);
    box-shadow: var(--shadow-gold);
}

.input-group-text {
    border-color: #e9d77a;
    font-weight: 500;
}

.alert {
    border-radius: 12px;
    border: none;
    box-shadow: var(--shadow-gold);
}
</style>
