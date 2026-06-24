<?php
$success = isset($_GET['success']) ? $_GET['success'] : 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Glow-in - Sign Up</title>
    <link rel="stylesheet" href="assets/CSS/register.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        .success-message {
            color: #22c55e;
            font-size: 1.1rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h1>Sign Up</h1>

        <?php if (!$success): ?>
            <button class="btn">
                <i class="fab fa-google" style="font-size: 1.5rem;"></i> Sign up with Google
            </button>

            <button class="btn">
                <i class="fab fa-apple" style="font-size: 1.5rem;"></i> Sign up with Apple
            </button>

            <div class="divider">OR</div>

            <form action="api/auth.php" method="POST" id="registerForm">
                <input type="hidden" name="action" value="register">

                <div class="input-group">
                    <input type="text" name="username" placeholder="username" required>
                </div>

                <div class="input-group">
                    <input type="email" name="email" placeholder="email" required>
                </div>

                <div class="input-row">
                    <div class="input-group">
                        <input type="password" name="password" id="password" placeholder="password" required>
                    </div>
                    <div class="input-group">
                        <input type="password" name="confirm_password" id="confirm_password" placeholder="confirm password" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-submit">Create Account</button>
            </form>

        <?php else: ?>
            <div class="success-message">
                <i class="fas fa-check-circle"></i> Akun berhasil dibuat!
            </div>
            <a href="login.php" class="btn btn-submit">Kembali ke Halaman Login</a>
        <?php endif; ?>
    </div>

    <script>
        document.getElementById('registerForm')?.addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Password tidak sama!');
            }
        });
    </script>
</body>
</html>
