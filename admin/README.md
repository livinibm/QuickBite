# QuickBite Admin Dashboard

A modern, responsive admin dashboard for managing restaurant orders and menu items. Built with PHP, MySQL, HTML, CSS, and JavaScript.

## 🚀 Features

# QuickBite Admin Dashboard

A modern, single-page admin dashboard for managing restaurant operations. Built with PHP, MySQL, HTML, CSS, and JavaScript.

## 🚀 Features

### Admin Authentication
- Secure login system with PHP sessions
- Password hashing for security
- Session protection for all admin pages

### Single-Page Dashboard
- **Statistics Overview**: Total orders, users, menu items, and revenue
- **User Management**: Complete CRUD operations for registered users
- **Order Viewing**: Display customer orders with detailed information
- **Menu Adding**: Simple form to add new menu items
- **No Navigation Bar**: Clean, focused single-page interface

### User Management (CRUD Operations)
- **Create**: Add new users with contact information
- **Read**: View all registered users in organized table
- **Update**: Edit existing user details with modal forms
- **Delete**: Remove users with confirmation prompts
- **View Details**: Complete user information display

### Order Management
- **View Only**: Display customer orders (no delete functionality)
- **Order Details**: Modal popup with customer info and ordered items
- **Order History**: Chronological display of all orders

### Menu Management
- **Add Only**: Simple form to add new menu items (no display/edit)
- **Categories**: Organized by appetizer, main course, dessert, beverage
- **Validation**: Form validation for all required fields

### Design Features
- **Theme**: Red (#d62828), White, Black color scheme with Poppins font
- **Single Page**: All functionality consolidated into one interface
- **Responsive**: Works perfectly on desktop, tablet, and mobile
- **Modern UI**: Clean, minimalist design with card-based layout
- **Interactive**: Modal windows for detailed views and editing
- **User-friendly**: Intuitive design with clear visual feedback

## 📋 Requirements

- **XAMPP** (Apache + MySQL + PHP 7.4+)
- **Web Browser** (Chrome, Firefox, Safari, Edge)
- **Local Development Environment**

## ⚡ Quick Installation

### Step 1: Setup XAMPP
1. Make sure XAMPP is installed and running
2. Start **Apache** and **MySQL** services in XAMPP Control Panel

### Step 2: Database Setup
1. Open **phpMyAdmin** in your browser: `http://localhost/phpmyadmin`
2. Click **"Import"** tab
3. Choose file: `QuickBite/database/setup.sql`
4. Click **"Go"** to import the database

### Step 3: Project Setup
1. Your project is already in: `c:\xampp\htdocs\QuickBite`
2. Open your browser and go to: `http://localhost/QuickBite/admin.php`

### Step 4: Admin Login
```
Username: admin
Password: admin123
```

## 🎯 Project Structure

```
QuickBite/
├── admin.php                 # Main admin dashboard
├── config/
│   └── database.php          # Database configuration
├── models/
│   └── Admin.php             # Admin model (OOP)
├── pages/
│   ├── dashboard.php         # Dashboard overview
│   ├── orders.php            # Orders management
│   ├── menu.php              # Menu items management
│   └── add_menu.php          # Add new menu item
├── assets/
│   ├── css/
│   │   └── admin.css         # Responsive CSS styles
│   └── js/
│       └── admin.js          # Dynamic JavaScript
└── database/
    └── setup.sql             # Database schema and sample data
```

## 🔑 Key Features Explained

### 1. Admin Authentication (Sessions)
```php
// Session-based authentication
session_start();
if (!$admin->isLoggedIn()) {
    // Redirect to login
}
```

### 2. OOP Implementation
```php
// Admin class with all methods
class Admin {
    public function login($username, $password) { ... }
    public function getAllOrders() { ... }
    public function addMenuItem($data) { ... }
    // ... more methods
}
```

### 3. CRUD Operations
- **Create**: Add new menu items with form validation
- **Read**: Display orders and menu items in tables
- **Update**: Edit menu items with modal forms
- **Delete**: Remove items with confirmation dialogs

### 4. Responsive Design
- CSS Grid and Flexbox for layout
- Mobile-first approach
- Breakpoints for different screen sizes
- Touch-friendly buttons and forms

### 5. Dynamic JavaScript
- Form validation with real-time feedback
- Modal dialogs for editing
- Search and filter functionality
- Image preview for menu items
- Loading states and animations

## 🎨 Design Theme

### Colors
- **Primary Red**: #dc3545 (buttons, accents)
- **Dark Background**: #212529 (main background)
- **White**: #ffffff (cards, forms)
- **Success Green**: #28a745 (success messages)

### Typography
- **Font**: Arial, sans-serif
- **Headers**: Bold, larger sizes
- **Body**: Clean, readable text
- **Icons**: Emoji for visual appeal

## 📱 Responsive Features

### Desktop (1200px+)
- Full dashboard layout
- Side-by-side forms
- Large data tables
- Hover effects

### Tablet (768px - 1199px)
- Stacked layout
- Readable tables
- Touch-friendly buttons

### Mobile (< 768px)
- Single column layout
- Horizontal scrolling tables
- Large touch targets
- Simplified navigation

## 🔒 Security Features

- **Password Hashing**: Using PHP `password_hash()`
- **Session Management**: Secure session handling
- **Input Validation**: Both client and server-side
- **SQL Injection Prevention**: Using PDO prepared statements
- **XSS Protection**: HTML escaping

## 🎯 Database Schema

### Tables Created:
1. **admins** - Admin user authentication
2. **menu_items** - Restaurant menu items
3. **orders** - Customer orders

### Sample Data Included:
- 1 Admin user (admin/admin123)
- 8 Sample menu items across all categories
- 4 Sample customer orders

## 🚀 Usage Guide

### Adding Menu Items
1. Go to "Add Menu Item" page
2. Fill in all required fields:
   - Item name (required)
   - Description (required)
   - Price (required)
   - Category (required)
   - Image URL (optional)
3. Click "Add Menu Item"

### Managing Orders
1. Go to "Orders" page
2. View all customer orders
3. Click "View" for order details
4. Click "Delete" to remove orders

### Editing Menu Items
1. Go to "Menu Items" page
2. Click "Edit" on any item
3. Modify details in modal form
4. Click "Update Item"

## 🔧 Customization

### Changing Colors
Edit `assets/css/admin.css`:
```css
:root {
    --primary-color: #dc3545;    /* Change this */
    --dark-bg: #212529;          /* Change this */
    --light-bg: #ffffff;         /* Change this */
}
```

### Adding New Features
1. Add new methods to `models/Admin.php`
2. Create new page in `pages/`
3. Add navigation link in `admin.php`
4. Style with CSS and add JavaScript

## 📊 Performance Tips

- Database indexes are included for better performance
- Images are loaded with lazy loading
- JavaScript is optimized for mobile
- CSS uses efficient selectors

## 🐛 Troubleshooting

### Common Issues:

1. **Database Connection Error**
   - Check if MySQL is running in XAMPP
   - Verify database credentials in `config/database.php`

2. **Login Not Working**
   - Make sure database is imported correctly
   - Check if session is started

3. **Images Not Loading**
   - Check if image URLs are valid
   - Ensure internet connection for external images

4. **Styling Issues**
   - Clear browser cache
   - Check if CSS file is loading correctly

## 📈 Future Enhancements

Potential features to add:
- Order status updates
- Customer management
- Sales analytics
- Email notifications
- File upload for images
- Multi-admin support
- API integration

## 🎓 Educational Value

This project demonstrates:
- **PHP OOP concepts** with classes and methods
- **Session management** for authentication
- **Database design** with proper relationships
- **Responsive web design** principles
- **JavaScript DOM manipulation**
- **Form validation** techniques
- **Security best practices**

## 📞 Support

For questions or issues:
1. Check the troubleshooting section
2. Review the code comments
3. Test with sample data provided

---

**QuickBite Admin Dashboard** - Simple, Clean, and Professional Restaurant Management System

*Built for CST 226-2 Web Application Development Assignment*
