<?php
include 'config.php';

session_start();
$error_message = '';
$selected_role = 'user';

if(isset($_POST['submit'])){

   $email = mysqli_real_escape_string($conn, strtolower(trim($_POST['email'])));
   $pass = mysqli_real_escape_string($conn, md5(trim($_POST['password'])));
   $selected_role = mysqli_real_escape_string($conn, trim($_POST['role']));

   $user_query = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email'") or die('query failed');

   if(mysqli_num_rows($user_query) > 0){
      $row = mysqli_fetch_assoc($user_query);

      if($row['password'] !== $pass){
         $error_message = 'Password incorrect for this email.';
      } elseif($row['user_type'] !== $selected_role){
         $error_message = 'Please select "' . ucfirst($row['user_type']) . '" login for this account.';
      } else {
         if($row['user_type'] === 'admin'){
            $_SESSION['admin_name'] = $row['name'];
            $_SESSION['admin_email'] = $row['email'];
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['user_type'] = $row['user_type'];
            header('location:admin/admin.php');
            exit;
         } elseif($row['user_type'] === 'user'){
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['user_email'] = $row['email'];
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_type'] = $row['user_type'];
            header('location:index.php');
            exit;
         }
      }
   } else {
      $error_message = 'No account found with this email. Please register first.';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login - Book Hub</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
   <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
   <link rel="stylesheet" href="style.css">
   <style>
      body {
         background: linear-gradient(135deg, #001f3f 0%, #0ea5e9 100%);
         min-height: 100vh;
         display: flex;
         align-items: center;
         justify-content: center;
      }

      .login-container {
         width: 100%;
         max-width: 420px;
      }

      .login-card {
         border-radius: 15px;
         border: none;
         box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
         overflow: hidden;
      }

      .login-header {
         background: linear-gradient(135deg, #001f3f 0%, #1e3a8a 100%);
         color: white;
         padding: 30px;
         text-align: center;
      }

      .login-header h2 {
         font-size: 1.8rem;
         font-weight: bold;
         margin: 0;
      }

      .login-header p {
         font-size: 0.95rem;
         margin-top: 5px;
         opacity: 0.9;
      }

      .login-body {
         padding: 30px;
      }

      .form-label {
         font-weight: 600;
         color: #333;
         margin-bottom: 8px;
      }

      .form-control {
         border: 2px solid #e0e0e0;
         border-radius: 8px;
         padding: 10px 12px;
         font-size: 0.95rem;
         transition: all 0.3s;
      }

      .form-control:focus {
         border-color: #0ea5e9;
         box-shadow: 0 0 0 0.2rem rgba(14, 165, 233, 0.15);
      }

      .password-wrapper {
         position: relative;
      }

      .password-wrapper .form-control {
         padding-right: 40px;
      }

      .password-toggle {
         position: absolute;
         right: 12px;
         top: 50%;
         transform: translateY(-50%);
         cursor: pointer;
         color: #666;
         background: none;
         border: none;
         font-size: 1rem;
         padding: 5px;
      }

      .password-toggle:hover {
         color: #0ea5e9;
      }

      .password-hint {
         font-size: 0.8rem;
         color: #0ea5e9;
         margin-top: 5px;
         display: flex;
         align-items: center;
         gap: 5px;
      }

      .password-hint i {
         font-size: 0.75rem;
      }

      .btn-login {
         background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
         border: none;
         color: white;
         font-weight: 600;
         padding: 10px;
         border-radius: 8px;
         transition: all 0.3s;
         margin-top: 15px;
      }

      .btn-login:hover {
         transform: translateY(-2px);
         box-shadow: 0 5px 20px rgba(14, 165, 233, 0.4);
         color: white;
      }

      .login-footer {
         text-align: center;
         padding: 20px 30px 30px;
         border-top: 1px solid #e0e0e0;
      }

      .login-footer p {
         margin: 0;
         font-size: 0.95rem;
      }

      .login-footer a {
         color: #0ea5e9;
         text-decoration: none;
         font-weight: 600;
      }

      .login-footer a:hover {
         text-decoration: underline;
      }

      .error-message {
         background: #fee;
         color: #c33;
         padding: 12px;
         border-radius: 8px;
         margin-bottom: 15px;
         border-left: 4px solid #c33;
      }
   </style>
</head>
<body>

<div class="login-container">
    <div class="card login-card">
        
        <div class="login-header">
            <h2><i class="fas fa-book-open"></i> Book Hub</h2>
            <p>Sign In to Your Account</p>
        </div>

        <div class="login-body">
            <form action="" method="post">
                <?php if($error_message != ''): ?>
                <div class="error-message"><?php echo $error_message; ?></div>
                <?php endif; ?>
                <div class="mb-4">
                    <label class="form-label"><i class="fas fa-user-cog"></i> Login Type</label>
                    <select name="role" class="form-control" required>
                        <option value="user" <?php echo $selected_role === 'user' ? 'selected' : ''; ?>>User Login</option>
                        <option value="admin" <?php echo $selected_role === 'admin' ? 'selected' : ''; ?>>Admin Login</option>
                    </select>
                    <div class="password-hint">
                        <i class="fas fa-info-circle"></i>
                        Choose the correct login type before signing in
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label"><i class="fas fa-envelope"></i> Email Address</label>
                    <input type="email" name="email" placeholder="Enter your email" class="form-control" required>
                    <div class="password-hint">
                        <i class="fas fa-check-circle"></i>
                        Use the email you registered with
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-lock"></i> Password</label>
                    <div class="password-wrapper">
                        <input type="password" name="password" id="password" placeholder="Enter your password" class="form-control" required>
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                    <div class="password-hint">
                        <i class="fas fa-info-circle"></i>
                        This is your Book Hub password, not your Gmail password
                    </div>
                </div>
                
                <button type="submit" name="submit" class="btn btn-login w-100 py-2 fw-bold">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>
        </div>

        <div class="login-footer">
            <p>Don't have an account? <a href="register.php">Create one now</a></p>
        </div>

    </div>
</div>

<script>
   function togglePassword() {
      const passwordField = document.getElementById('password');
      const toggleIcon = document.getElementById('toggleIcon');
      
      if(passwordField.type === 'password') {
         passwordField.type = 'text';
         toggleIcon.classList.remove('fa-eye');
         toggleIcon.classList.add('fa-eye-slash');
      } else {
         passwordField.type = 'password';
         toggleIcon.classList.remove('fa-eye-slash');
         toggleIcon.classList.add('fa-eye');
      }
   }
</script>

</body>
</html>
