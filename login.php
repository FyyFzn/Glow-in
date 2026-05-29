<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Glow-in Login</title>
 
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
  />
  <link rel="stylesheet" href="assets/CSS/login.css">
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

    <form action="api/auth.php" method="POST">
      <div class="input-group">
        <i class="fa-solid fa-user"></i>
        <input type="text" name="username" placeholder="Username" required />
      </div>
      <div class="input-group" style="margin-top: 15px;">
        <i class="fa-solid fa-lock"></i>
        <input type="password" name="password" placeholder="Password" required />
      </div>

      <button class="btn btn-submit" type="submit" name="action" value="login" style="margin-top: 15px;">Login</button>
      <a href="#" class="forgot">Forgot password?</a>
    </form>

    <div class="signup">
      Don't have an account? <a href="register.php">Sign up</a>
    </div>
  </div>
</body>
</html>
