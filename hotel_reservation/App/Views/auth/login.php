<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Login</title>

    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">



<style>
:root {
    --gold: #d4af37;
    --gold-soft: #f3d67a;
}

/* ===== Global box sizing fix ===== */
* {
    box-sizing: border-box;
}

/* ===== Background ===== */
body {
    margin: 0;
    font-family: 'Inter', sans-serif;
    background: radial-gradient(circle at top, #1e293b, #020617 80%);
}

/* ===== Layout Right Side ===== */
.auth-page {
    min-height: 100vh;
    display: flex;
    justify-content: flex-end;
    align-items: center;
    padding-right: 6%;
}

/* ===== Card ===== */
.auth-card {
    width: 100%;
    max-width: 540px;
    backdrop-filter: blur(18px);
    background: rgba(255,255,255,0.05);
    border-radius: 18px;
    border: 1px solid rgba(212,175,55,0.3);
    box-shadow:
        0 0 30px rgba(212,175,55,0.3),
        0 0 80px rgba(0,0,0,0.8);
    padding: 40px 35px;
}

/* ===== Logo Circle ===== */
.hero-icon {
    width: 110px;
    height: 110px;
    margin: 0 auto 20px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: logoGlow 3s ease-in-out infinite;
}

.hero-icon img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    border-radius: 50%;
}

@keyframes logoGlow {
    0%   { filter: drop-shadow(0 0 10px rgba(212,175,55,0.4)); }
    50%  { filter: drop-shadow(0 0 30px rgba(212,175,55,0.9)); }
    100% { filter: drop-shadow(0 0 10px rgba(212,175,55,0.4)); }
}

/* ===== Text ===== */
.hero-title {
    text-align: center;
    color: white;
    text-shadow: 0 0 12px rgba(212,175,55,0.6);
}

.hero-subtitle {
    text-align: center;
    color: #cbd5f5;
    margin-bottom: 25px;
}

/* ===== Inputs ===== */
.input-wrapper {
    position: relative;
    margin-bottom: 18px;
    width: 100%;
}

.input-field {
    width: 100%;
    padding: 14px 16px 14px 45px;
    border-radius: 10px;
    border: 1px solid rgba(255,255,255,0.1);
    background: rgba(255,255,255,0.08);
    color: white;
}

.input-field::placeholder {
    color: #9ca3af;
}

.input-field:focus {
    outline: none;
    border-color: var(--gold);
    box-shadow: 0 0 10px rgba(212,175,55,0.6);
}

/* icon centered vertically */
.input-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gold-soft);
    pointer-events: none;
}

/* ===== Checkbox alignment ===== */
.form-check {
    margin-left: 0;
    padding-left: 1.8em;
}

.form-check-label {
    color: #e5e7eb;
}

/* ===== Button ===== */
.btn-primary {
    width: 100%;
    padding: 14px;
    border: none;
    background: linear-gradient(135deg, #b8962e, #f3d67a);
    color: #111;
    font-weight: bold;
    border-radius: 12px;
    box-shadow: 0 0 20px rgba(212,175,55,0.6);
}

.btn-primary:hover {
    box-shadow:
        0 0 30px rgba(212,175,55,0.9),
        0 0 60px rgba(212,175,55,0.4);
}

/* ===== Links ===== */
.auth-links {
    text-align: center;
    margin-top: 18px;
}

.auth-link {
    color: var(--gold-soft);
}
</style>
</head>

<body>

<div class="auth-page">

<div class="auth-card">

    <div class="hero-icon">
        <img src="/hotel_reservation/public/assets/img/logo.png" alt="Hotel Logo">
    </div>

    <h2 class="hero-title">Grand Horizon Hotel</h2>
    <p class="hero-subtitle">Staff & Guest Portal</p>

    <?php if(!empty($error)): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="/hotel_reservation/public/auth/login">

        <div class="input-wrapper">
            <i class="fas fa-user input-icon"></i>
            <input type="text" name="username" class="input-field" placeholder="Username"
                   required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
        </div>

        <div class="input-wrapper">
            <i class="fas fa-lock input-icon"></i>
            <input type="password" name="password" class="input-field" placeholder="Password" required>
        </div>

        <div class="form-check text-light mb-3">
            <input type="checkbox" class="form-check-input" name="remember" id="remember">
            <label class="form-check-label" for="remember">Remember me</label>
        </div>

        <button type="submit" class="btn btn-primary">Sign In</button>

    </form>

    <div class="auth-links">
        <p class="text-light">Don't have an account?</p>
        <a href="/hotel_reservation/public/auth/register" class="auth-link">Create new account</a>
    </div>

    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</div>

</div>

</body>
</html>

