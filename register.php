<?php
// Database connection file ko include karna zaroori hai
include 'config.php';

$error_message = '';
$selected_role = 'user';
$admin_code = '';

if(isset($_POST['submit'])){

   // Form se data lena aur security ke liye 'escape' karna
   $name = mysqli_real_escape_string($conn, trim($_POST['name']));
   $email = mysqli_real_escape_string($conn, strtolower(trim($_POST['email'])));
   $selected_role = mysqli_real_escape_string($conn, trim($_POST['role']));
   $admin_code = mysqli_real_escape_string($conn, trim($_POST['admin_code']));
   
   // Password ko 'md5' se encrypt karna taake database mein nazar na aye
   $pass = mysqli_real_escape_string($conn, md5(trim($_POST['password'])));
   $cpass = mysqli_real_escape_string($conn, md5(trim($_POST['cpassword'])));

   // Check karna ke kya ye email pehle se register to nahi?
   $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email'") or die('query failed');

   if(mysqli_num_rows($select_users) > 0){
      $error_message = 'User already exists!';
   }else{
      // Password match check karna
      if($pass != $cpass){
         $error_message = 'Confirm password not matched!';
      } elseif($selected_role === 'admin' && $admin_code !== 'ADMIN2026') {
         $error_message = 'Admin code incorrect. Please use the correct admin access code.';
      } else {
         // Data database mein insert (save) karna - with user_type only
         mysqli_query($conn, "INSERT INTO `users`(name, email, password, user_type) VALUES('$name', '$email', '$pass', '$selected_role')") or die('query failed');
         echo '<script>alert("Registered successfully!"); window.location.href="login.php";</script>';
      }
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register - Book Hub</title>
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
         padding: 20px 0;
      }

      .register-container {
         width: 100%;
         max-width: 420px;
      }

      .register-card {
         border-radius: 15px;
         border: none;
         box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
         overflow: hidden;
      }

      .register-header {
         background: linear-gradient(135deg, #001f3f 0%, #1e3a8a 100%);
         color: white;
         padding: 30px;
         text-align: center;
      }

      .register-header h2 {
         font-size: 1.8rem;
         font-weight: bold;
         margin: 0;
      }

      .register-header p {
         font-size: 0.95rem;
         margin-top: 5px;
         opacity: 0.9;
      }

      .register-body {
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

      .btn-register {
         background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
         border: none;
         color: white;
         font-weight: 600;
         padding: 10px;
         border-radius: 8px;
         transition: all 0.3s;
         margin-top: 15px;
      }

      .btn-register:hover {
         transform: translateY(-2px);
         box-shadow: 0 5px 20px rgba(14, 165, 233, 0.4);
         color: white;
      }

      .register-footer {
         text-align: center;
         padding: 20px 30px 30px;
         border-top: 1px solid #e0e0e0;
      }

      .register-footer p {
         margin: 0;
         font-size: 0.95rem;
      }

      .register-footer a {
         color: #0ea5e9;
         text-decoration: none;
         font-weight: 600;
      }

      .register-footer a:hover {
         text-decoration: underline;
      }
   </style>
</head>
<body>

<div class="register-container">
    <div class="card register-card">
        
        <div class="register-header">
            <h2><i class="fas fa-book-open"></i> Book Hub</h2>
            <p>Create Your Account</p>
        </div>

        <div class="register-body">
            <form action="" method="post">
                <?php if($error_message != ''): ?>
                <div class="error-message"><?php echo $error_message; ?></div>
                <?php endif; ?>
                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-user-cog"></i> Account Type</label>
                    <select name="role" class="form-control" required>
                        <option value="user" <?php echo $selected_role === 'user' ? 'selected' : ''; ?>>User Account</option>
                        <option value="admin" <?php echo $selected_role === 'admin' ? 'selected' : ''; ?>>Admin Account</option>
                    </select>
                    <div class="password-hint">
                        <i class="fas fa-info-circle"></i>
                        Select admin only if you have the admin access code.
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-key"></i> Admin Access Code</label>
                    <input type="text" name="admin_code" placeholder="Enter admin code if admin" class="form-control" value="<?php echo htmlspecialchars($admin_code); ?>">
                    <div class="password-hint">
                        <i class="fas fa-info-circle"></i>
                        Leave blank for regular user registration.
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-user"></i> Full Name</label>
                    <input type="text" name="name" placeholder="Enter your full name" class="form-control" required>
                </div>

                <div class="mb-4">
                    <label class="form-label"><i class="fas fa-envelope"></i> Email Address</label>
                    <input type="email" name="email" placeholder="Enter your email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-lock"></i> Password</label>
                    <div class="password-wrapper">
                        <input type="password" name="password" id="password" placeholder="Create a password" class="form-control" required>
                        <button type="button" class="password-toggle" onclick="togglePassword('password')">
                            <i class="fas fa-eye" id="toggleIcon1"></i>
                        </button>
                    </div>
                    <div class="password-hint">
                        <i class="fas fa-info-circle"></i>
                        Minimum 6 characters recommended
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-lock"></i> Confirm Password</label>
                    <div class="password-wrapper">
                        <input type="password" name="cpassword" id="cpassword" placeholder="Re-enter your password" class="form-control" required>
                        <button type="button" class="password-toggle" onclick="togglePassword('cpassword')">
                            <i class="fas fa-eye" id="toggleIcon2"></i>
                        </button>
                    </div>
                    <div class="password-hint">
                        <i class="fas fa-info-circle"></i>
                        Must match your password above
                    </div>
                </div>
                
                <button type="submit" name="submit" class="btn btn-register w-100 py-2 fw-bold">
                    <i class="fas fa-user-plus"></i> Create Account
                </button>
            </form>
        </div>

        <div class="register-footer">
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>

    </div>
</div>

<script>
   function togglePassword(fieldId) {
      const passwordField = document.getElementById(fieldId);
      const iconId = fieldId === 'password' ? 'toggleIcon1' : 'toggleIcon2';
      const toggleIcon = document.getElementById(iconId);
      
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