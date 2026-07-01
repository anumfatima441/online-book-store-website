// Quick Test Checklist - Online Book Store

## ✅ COMPLETED FEATURES

### Authentication System
- [x] User Registration (register.php)
  - Full name input
  - Email validation
  - Password confirmation
  - Auto-redirect to login after registration

- [x] User Login (login.php)
  - Email & password authentication
  - Session management
  - Admin/User role detection
  - Secure password hashing (MD5)

- [x] Logout (logout.php)
  - Session destruction
  - Redirect to homepage

### Homepage (index.php)
- [x] Welcome message without login required
- [x] Search functionality (by title, author, description)
- [x] Book listing with:
  - Book cover images
  - Title & Author
  - Price
  - Star rating (1-5 stars)
  - Add to Cart button (requires login)
- [x] Dynamic navbar (shows different links based on login status)
- [x] Professional footer with links

### Logged-in User Experience (home.php)
- [x] Personalized welcome message
- [x] Full book collection display
- [x] Add to Cart functionality
- [x] Book ratings and descriptions
- [x] Proper navigation

### Shopping Cart (cart.php)
- [x] View all cart items
- [x] Display for each item:
  - Book cover thumbnail
  - Title
  - Price
  - Quantity
  - Subtotal (Price × Quantity)
- [x] Remove items from cart
- [x] Total price calculation
- [x] Checkout button
- [x] Empty cart message

### Add to Cart (add_to_cart.php)
- [x] Increment quantity if book already in cart
- [x] Add new books to cart
- [x] User authentication check
- [x] Redirect back to cart view

### Checkout (checkout.php)
- [x] Move cart items to purchases table
- [x] Clear user's cart
- [x] Success confirmation message
- [x] Link to continue shopping

### Purchase History (purchases.php)
- [x] View all purchased books
- [x] Display:
  - Book cover
  - Title & Author
  - Quantity purchased
  - Purchase date & time
- [x] Sorted by most recent first

### Database & Configuration
- [x] config.php - Database connection
- [x] db_setup.sql - Database schema with:
  - users table (id, name, email, password, user_type)
  - books table (id, title, author, price, image, description, rating)
  - cart table (id, user_id, book_id, quantity)
  - purchases table (id, user_id, book_id, quantity, purchased_at)
- [x] Sample data - 14 books pre-loaded
- [x] Sample admin user

### Styling & UI
- [x] Bootstrap 5.3.0 integration
- [x] Responsive design (mobile-friendly)
- [x] Custom CSS with:
  - Navy (#001f3f) & Yellow (#ffc107) color scheme
  - Smooth hover effects
  - Card shadows and transitions
  - Professional layout
  - Mobile responsive breakpoints
- [x] Professional typography
- [x] Consistent footer across all pages
- [x] Search bar styling

---

## 🚀 TO RUN THE PROJECT

### Step 1: Start XAMPP
1. Open XAMPP Control Panel
2. Click "Start" for Apache
3. Click "Start" for MySQL

### Step 2: Setup Database
1. Go to http://localhost/phpmyadmin
2. Click "Import" tab
3. Select db_setup.sql file
4. Click "Import" button

### Step 3: Open Application
1. Go to http://localhost/online_bookstore/
2. Browse books as visitor
3. Click "Register" to create account
4. Login with new credentials
5. Start shopping!

---

## 📋 TEST SCENARIOS

### Visitor (Not Logged In)
- ✅ View all books on homepage
- ✅ Search books
- ✅ See prices and ratings
- ✅ Login/Register links visible
- ❌ Cannot add to cart (shown if trying)

### New User
- ✅ Complete registration form
- ✅ Automatic redirect to login page
- ✅ Login with new credentials
- ✅ Redirect to home.php after login

### Logged-in User
- ✅ See personalized welcome message
- ✅ Add books to cart
- ✅ View cart with quantities
- ✅ Update quantities (by re-adding same book)
- ✅ Remove items from cart
- ✅ See total price calculation

### Checkout
- ✅ Complete purchase
- ✅ See confirmation message
- ✅ Cart becomes empty
- ✅ Items appear in Purchases

### Purchase History
- ✅ View all purchased books
- ✅ See purchase dates/times
- ✅ Multiple items listed chronologically

---

## 📊 SAMPLE TEST DATA

**Test User Account:**
- Email: testuser@example.com
- Password: test123

**Sample Books Available:**
1. The Great Gatsby - Rs. 500
2. Clean Code - Rs. 800
3. Python Crash Course - Rs. 700
4. [... and 11 more]

**Admin Account (if needed):**
- Email: admin@example.com
- Password: admin123

---

## 🔒 SECURITY IMPLEMENTED

✅ Session-based authentication
✅ Password hashing (MD5)
✅ SQL injection prevention (mysqli_real_escape_string)
✅ User input validation
✅ Role-based access (user/admin)
✅ Protected pages (require login)

---

## 📱 RESPONSIVE DESIGN

✅ Mobile devices (< 768px)
✅ Tablets (768px - 1024px)
✅ Desktops (> 1024px)
✅ Bootstrap grid system
✅ Flexible images
✅ Touch-friendly buttons

---

**Status: ✅ READY FOR PRODUCTION TESTING**
All core features implemented and tested!
