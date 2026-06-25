<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Glow-in Login</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="../assets/CSS/base.css?v=101">
  <link rel="stylesheet" href="../assets/CSS/login.css">
</head>

<body>
  <div class="login-container">
    <h2>Sign in to Glow-in</h2>

    <button class="btn btn-google">
      <i class="fa-brands fa-google"></i> Sign in with Google
    </button>

    <button class="btn btn-apple">
      <i class="fa-brands fa-apple"></i> Sign in with Apple
    </button>

    <div class="divider">Or</div>

    <p id="login-error" class="error-state mb-16 d-none"></p>

    <form id="loginForm">
      <div class="input-group">
        <i class="fa-solid fa-user"></i>
        <input type="text" id="username" placeholder="Username" required />
      </div>
      <div class="input-group mt-16">
        <i class="fa-solid fa-lock"></i>
        <input type="password" id="password" placeholder="Password" required />
      </div>

      <button class="btn btn-primary mt-16" type="submit">Login</button>
      <a href="#" class="forgot">Forgot password?</a>
    </form>

    <div class="signup">
      Don't have an account? <a href="register.php">Sign up</a>
    </div>
  </div>

<script>
document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const errEl = document.getElementById('login-error');
    errEl.classList.add('d-none');

    fetch('../controllers/authController.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            action: 'login',
            username: document.getElementById('username').value,
            password: document.getElementById('password').value
        })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            window.location.href = data.redirect;
        } else {
            errEl.textContent = data.error || 'Gagal login.';
            errEl.classList.remove('d-none');
        }
    })
    .catch(err => {
        errEl.textContent = 'Terjadi kesalahan jaringan.';
        errEl.classList.remove('d-none');
    });
});
</script>
</body>
</html>
