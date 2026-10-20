<?php if(session_status() == PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register - Hotel Reservation</title>
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(135deg,#1e3c72,#2a5298);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

.auth-card {
    background: #fff;
    width: 100%;
    max-width: 420px;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    overflow: hidden;
}

.hero-section {
    background: #10b981;
    color: white;
    padding: 30px 20px;
    text-align: center;
}

.hero-section h1 {
    margin: 10px 0 5px;
}

.form-body {
    padding: 30px 25px;
}

.input-group {
    position: relative;
    margin-bottom: 20px;
}

.input-group i {
    position: absolute;
    top: 50%;
    left: 12px;
    transform: translateY(-50%);
    color: #999;
}

.input-group input {
    width: 100%;
    padding: 12px 12px 12px 38px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 14px;
}

.password-toggle {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    color: #777;
}

button {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 8px;
    background: #10b981;
    color: white;
    font-size: 15px;
    cursor: pointer;
}

button:hover {
    background: #0e9f6e;
}

.alert {
    padding: 10px;
    margin: 15px;
    border-radius: 6px;
    font-size: 14px;
}

.alert-danger { background: #fee2e2; color: #b91c1c; }
.alert-success { background: #dcfce7; color: #166534; }

.auth-footer {
    text-align: center;
    padding: 15px;
    font-size: 14px;
}

a { color: #10b981; text-decoration: none; }

</style>
</head>
<body>

<div class="auth-card">
    <div class="hero-section">
        <i class="fas fa-user-plus fa-2x"></i>
        <h1>Create Account</h1>
        <p>Join us and start booking your stay</p>
    </div>

    <?php if(!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if(!empty($success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <div class="form-body">
        <form method="POST" action="/hotel_reservation/public/auth/register" id="registerForm">

            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="username" placeholder="Username" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
            </div>

            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>

            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" id="password" placeholder="Password" required>
                <span class="password-toggle" onclick="togglePassword('password')"><i class="fas fa-eye"></i></span>
            </div>

            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>
                <span class="password-toggle" onclick="togglePassword('confirm_password')"><i class="fas fa-eye"></i></span>
            </div>

            <button type="submit">Create Account</button>
        </form>
    </div>

    <div class="auth-footer">
        Already have an account? 
        <a href="/hotel_reservation/public/auth/login">Sign in here</a>
    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</div>
</div>

<script>
function togglePassword(id) {

    const field = document.getElementById(id);
    field.type = field.type === 'password' ? 'text' : 'password';
}

// Validate password match
document.getElementById('registerForm').addEventListener('submit', function(e){
    const p = document.getElementById('password').value;
    const c = document.getElementById('confirm_password').value;
    if(p !== c){
        alert('Passwords do not match');
        e.preventDefault();
    }
});
</script>

</body>
</html>
