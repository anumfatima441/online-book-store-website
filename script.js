// JavaScript for Book Hub

// Function to toggle password visibility
function togglePassword(fieldId = 'password') {
   const passwordField = document.getElementById(fieldId);
   const iconId = fieldId === 'password' ? 'toggleIcon' : (fieldId === 'cpassword' ? 'toggleIcon2' : 'toggleIcon1');
   const toggleIcon = document.getElementById(iconId);
   
   if (passwordField && toggleIcon) {
      if (passwordField.type === 'password') {
         passwordField.type = 'text';
         toggleIcon.classList.remove('fa-eye');
         toggleIcon.classList.add('fa-eye-slash');
      } else {
         passwordField.type = 'password';
         toggleIcon.classList.remove('fa-eye-slash');
         toggleIcon.classList.add('fa-eye');
      }
   }
}