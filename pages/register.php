<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Glow-in - Sign Up</title>
    <link rel="stylesheet" href="../assets/CSS/base.css?v=101">
    <link rel="stylesheet" href="../assets/CSS/register.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        .success-message {
            color: #22c55e;
            font-size: 1.1rem;
            margin-bottom: 1rem;
        }
        .error-message {
            color: #ef4444;
            font-size: 0.95rem;
            margin-bottom: 1rem;
            display: none;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h1>Sign Up</h1>

        <div id="register-form-section">
            <button class="btn btn-secondary">
                <i class="fab fa-google fa-xl"></i> Sign up with Google
            </button>

            <button class="btn btn-secondary">
                <i class="fab fa-apple fa-xl"></i> Sign up with Apple
            </button>

            <div class="divider">OR</div>

            <div id="reg-error" class="error-message"></div>

            <form id="registerForm">
                <div class="input-group">
                    <input type="text" id="username" placeholder="username" required>
                </div>

                <div class="input-group">
                    <input type="email" id="email" placeholder="email" required>
                </div>

                <div class="input-row">
                    <div class="input-group">
                        <input type="password" id="password" placeholder="password" required>
                    </div>
                    <div class="input-group">
                        <input type="password" id="confirm_password" placeholder="confirm password" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-16">Create Account</button>
            </form>
            <div class="mt-16 text-center">
                Already have an account? <a href="login.php" class="text-link">Sign in</a>
            </div>
        </div>

        <div id="register-success-section" class="text-center d-none">
            <div class="success-message">
                <i class="fas fa-check-circle fa-3x mb-16"></i> 
                Akun berhasil dibuat!
            </div>
            <a href="login.php" class="btn btn-primary mt-16">Login Sekarang</a>
        </div>
    </div>

    <script>
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const errEl = document.getElementById('reg-error');
            errEl.style.display = 'none';

            const u = document.getElementById('username').value.trim();
            const em = document.getElementById('email').value.trim();
            const pw = document.getElementById('password').value;
            const cpw = document.getElementById('confirm_password').value;

            if (pw !== cpw) {
                errEl.textContent = 'Password tidak sama!';
                errEl.style.display = 'block';
                return;
            }

            fetch('../controllers/authController.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'register',
                    username: u,
                    email: em,
                    password: pw,
                    confirm_password: cpw
                })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    document.getElementById('register-form-section').classList.add('d-none');
                    document.getElementById('register-success-section').classList.remove('d-none');
                } else {
                    errEl.textContent = data.error || 'Gagal mendaftar.';
                    errEl.style.display = 'block';
                }
            })
            .catch(() => {
                errEl.textContent = 'Terjadi kesalahan koneksi jaringan.';
                errEl.style.display = 'block';
            });
        });
    </script>
</body>
</html>
