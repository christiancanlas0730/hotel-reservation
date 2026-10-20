<?php ?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Create Booking</title>

<!-- LINK OFFLINE BOOTSTRAP -->
<link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">

<style>
body {
    font-family: 'Segoe UI', sans-serif;
    background: #f5f5f5;
    margin: 0;
    padding: 20px;
}

.card {
    max-width: 900px;
    margin: 0 auto;
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
}

.card .p-4 {
    padding: 2rem !important;
}

h2 { font-weight: 600; margin-bottom: 1rem; }
h4 { font-weight: 500; margin-bottom: 0.8rem; }
.form-label { font-weight: 500; }

.row > .col-md-4, .row > .col-md-6 { margin-bottom: 12px; }

.btn { border-radius: 8px; font-weight: 500; }
.btn i { margin-right: 6px; }

/* Highlight invalid fields dynamically */
.is-invalid {
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 0.2rem rgba(220,53,69,.25);
}
</style>
</head>
<body>

<?php if(!empty($error)): ?>
    <div class="alert alert-danger" role="alert">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="p-4">

        <h2 class="mb-4"><i class="fas fa-calendar-check"></i> Create Booking</h2>

        <form method="POST" action="/hotel_reservation/public/bookings/create" id="bookingCreateForm">

            <!-- Guest Section -->
            <div class="mb-4">
                <h4>Guest</h4>

                <div class="d-flex gap-3 flex-wrap mb-3">
                    <label class="d-flex align-items-center gap-2">
                        <input type="radio" name="guest_mode" value="new" id="guestModeNew" checked>
                        New guest
                    </label>
                    <label class="d-flex align-items-center gap-2">
                        <input type="radio" name="guest_mode" value="existing" id="guestModeExisting">
                        Existing guest
                    </label>
                </div>

                <!-- Existing guest -->
                <div id="existingGuestSection" style="display:none;" class="mb-3">
                    <label class="form-label"><b>Select Customer</b></label>
                    <select class="form-control" name="customer_id" id="customerId">
                        <option value="">Choose customer</option>
                        <?php while($customer = $customers->fetch_assoc()): ?>
                            <option value="<?= (int)$customer['id'] ?>">
                                <?= htmlspecialchars($customer['name']) ?> (<?= htmlspecialchars($customer['email']) ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- New guest -->
                <div id="newGuestSection" class="mb-3">
                    <label class="form-label"><b>New Guest Details</b></label>
                    <div class="row gap-2 mt-2">
                        <div class="col-md-4" style="min-width: 240px;">
                            <label class="form-label">Name</label>
                            <input class="form-control" type="text" name="new_name" id="newName" placeholder="Name">
                        </div>
                        <div class="col-md-4" style="min-width: 240px;">
                            <label class="form-label">Email</label>
                            <input class="form-control" type="email" name="new_email" id="newEmail" placeholder="Email">
                        </div>
                        <div class="col-md-4" style="min-width: 200px;">
                            <label class="form-label">Phone</label>
                            <input class="form-control" type="tel" name="new_phone" id="newPhone" placeholder="Phone">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Room & Dates Section -->
            <div class="mb-4">
                <h4>Room & Dates</h4>
                <label class="form-label">Room</label>
                <select class="form-control mb-3" name="room_id" id="roomId" required>
                    <option value="">Select Room</option>
                    <?php while($room = $rooms->fetch_assoc()): ?>
                        <option value="<?= (int)$room['id'] ?>">
                            <?= htmlspecialchars($room['room_number']) ?> - <?= htmlspecialchars($room['type']) ?>
                            ($<?= htmlspecialchars($room['price']) ?>)
                        </option>
                    <?php endwhile; ?>
                </select>

                <div class="row gap-3">
                    <div class="col-md-6" style="min-width: 250px;">
                        <label class="form-label">Check-in</label>
                        <input class="form-control" type="date" name="check_in" required>
                    </div>
                    <div class="col-md-6" style="min-width: 250px;">
                        <label class="form-label">Check-out</label>
                        <input class="form-control" type="date" name="check_out" required>
                    </div>
                </div>
            </div>

            <!-- Payment Method Section -->
            <div class="mb-4">
                <h4>Payment Method</h4>

                <div class="d-flex gap-3 flex-wrap mb-3">
                    <label class="d-flex align-items-center gap-2">
                        <input type="radio" name="payment_method" value="ewallet" id="paymentEwallet" checked>
                        E-wallet
                    </label>
                    <label class="d-flex align-items-center gap-2">
                        <input type="radio" name="payment_method" value="card" id="paymentCard">
                        Credit/Debit Card
                    </label>
                </div>

                <!-- E-wallet options (dropdown) -->
                <div id="ewalletOptionsSection" class="mb-3">
                    <label class="form-label">Choose E-wallet</label>
                    <select class="form-control" name="ewallet_option" id="ewalletOption">
                        <option value="gcash">GCash</option>
                        <option value="paypal">PayPal</option>
                        <option value="paymaya">PayMaya</option>
                    </select>
                </div>

                <!-- Card Details -->
                <div id="cardDetailsSection" style="display:none;">
                    <div class="row gap-2 mt-2">
                        <div class="col-md-4" style="min-width: 240px;">
                            <label class="form-label">Card Number</label>
                            <input class="form-control" type="text" name="card_number" placeholder="XXXX-XXXX-XXXX-XXXX">
                        </div>
                        <div class="col-md-4" style="min-width: 120px;">
                            <label class="form-label">Expiry</label>
                            <input class="form-control" type="month" name="card_expiry">
                        </div>
                        <div class="col-md-2" style="min-width: 120px;">
                            <label class="form-label">CVV</label>
                            <input class="form-control" type="text" name="card_cvv" maxlength="4" placeholder="XXX">
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="/hotel_reservation/public/receptionist/dashboard" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create Booking
                </button>
            </div>

        </form>
    </div>
</div>

<script src="../../bootstrap/js/bootstrap.bundle.min.js"></script>

<script>
(function () {
    // Guest Mode
    const guestModeNew = document.getElementById('guestModeNew');
    const guestModeExisting = document.getElementById('guestModeExisting');
    const existingSection = document.getElementById('existingGuestSection');
    const newSection = document.getElementById('newGuestSection');
    const customerId = document.getElementById('customerId');
    const newName = document.getElementById('newName');
    const newEmail = document.getElementById('newEmail');
    const newPhone = document.getElementById('newPhone');

    function setMode(mode){
        const isExisting = mode==='existing';
        existingSection.style.display = isExisting?'block':'none';
        newSection.style.display = isExisting?'none':'block';

        customerId.required = isExisting;
        newName.required = !isExisting;
        newEmail.required = !isExisting;
        newPhone.required = !isExisting;
    }

    guestModeNew.addEventListener('change', ()=>setMode('new'));
    guestModeExisting.addEventListener('change', ()=>setMode('existing'));
    setMode(guestModeExisting.checked?'existing':'new');

    // Payment
    const paymentEwallet = document.getElementById('paymentEwallet');
    const paymentCard = document.getElementById('paymentCard');
    const cardDetailsSection = document.getElementById('cardDetailsSection');
    const ewalletOptionsSection = document.getElementById('ewalletOptionsSection');
    const ewalletOption = document.getElementById('ewalletOption');
    const form = document.getElementById('bookingCreateForm');
    const cardNumber = form.querySelector("input[name='card_number']");
    const cardExpiry = form.querySelector("input[name='card_expiry']");
    const cardCVV = form.querySelector("input[name='card_cvv']");

    function togglePaymentDetails(){
        if(paymentCard.checked){
            cardDetailsSection.style.display='block';
            ewalletOptionsSection.style.display='none';
            cardNumber.required=true;
            cardExpiry.required=true;
            cardCVV.required=true;
            ewalletOption.required=false;
        } else {
            cardDetailsSection.style.display='none';
            ewalletOptionsSection.style.display='block';
            cardNumber.required=false;
            cardExpiry.required=false;
            cardCVV.required=false;
            ewalletOption.required=true;
        }
    }

    paymentEwallet.addEventListener('change', togglePaymentDetails);
    paymentCard.addEventListener('change', togglePaymentDetails);
    togglePaymentDetails();

    // Form submit validation with dynamic red highlight
    form.addEventListener('submit', function(e){
        let valid = true;

        const checkIn = new Date(form.querySelector("input[name='check_in']").value);
        const checkOut = new Date(form.querySelector("input[name='check_out']").value);
        if(checkOut <= checkIn){
            alert("Check-out must be after Check-in!");
            valid=false;
        }

        // Payment validation
        if(paymentEwallet.checked && !ewalletOption.value){
            valid=false;
            ewalletOption.classList.add('is-invalid');
        } else ewalletOption.classList.remove('is-invalid');

        if(paymentCard.checked){
            [cardNumber,cardExpiry,cardCVV].forEach(el=>{
                if(!el.value){ el.classList.add('is-invalid'); valid=false; }
                else el.classList.remove('is-invalid');
            });
        }

        // Guest info highlight
        if(!guestModeExisting.checked){
            [newName,newEmail,newPhone].forEach(el=>{
                if(!el.value){ el.classList.add('is-invalid'); valid=false; }
                else el.classList.remove('is-invalid');
            });
        } else {
            if(!customerId.value){ customerId.classList.add('is-invalid'); valid=false; }
            else customerId.classList.remove('is-invalid');
        }

        if(!valid) e.preventDefault();
    });

})();
</script>

</body>
</html>
