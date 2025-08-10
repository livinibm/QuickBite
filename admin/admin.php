<?php
session_start();
require_once 'class/Admin.php';

// Check if user is logged in and is an admin
// If not, send them to login page
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../reglogin/auth.php');
    exit();
}

function handleImageUpload($file, $itemId) {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }

    $uploadDir = "uploads/" . htmlspecialchars($itemId) . "/";

    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $imageFileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

    // Checking allowed file types 
    if (!in_array($imageFileType, $allowedTypes)) {
        return false;
    }

    $fileName = 'menu_' . time() . '_' . rand(1000, 9999) . '.' . $imageFileType;
    $targetFile = $uploadDir . $fileName;

    // Move uploaded file to folder
    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
        return $fileName;
    }

    return false;
}

function setFlashMessage($message, $type) {
    $_SESSION['flash_message'] = $message;
    $_SESSION['flash_type'] = $type;
}

$admin = new Admin();

$message = $_SESSION['flash_message'] ?? '';
$messageType = $_SESSION['flash_type'] ?? '';

if (isset($_SESSION['flash_message'])) {
    unset($_SESSION['flash_message']);
    unset($_SESSION['flash_type']);
}

// Handle AJAX request to get user data for editing
if (($_GET['action'] ?? '') === 'get_user') {
    header('Content-Type: application/json');
    
    // Check if admin is logged in
    if (!$admin->isLoggedIn()) {
        echo json_encode(['error' => 'Not authenticated']);
        exit;
    }
    
    // Get user ID from URL
    $id = $_GET['id'] ?? null;
    if (!$id) {
        echo json_encode(['error' => 'User ID is required']);
        exit;
    }
    
    // Get user data from database
    $user = $admin->getUserById($id);
    if ($user) {
        // Remove password from data for security
        unset($user['password']);
        echo json_encode($user);
    } else {
        echo json_encode(['error' => 'User not found']);
    }
    exit;
}

// Handle logout request
if (($_GET['action'] ?? '') === 'logout') {
    $admin->logout();
    
    header('Location: ../reglogin/auth.php');
    exit;
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {

            case 'add_user':
                $userData = [
                    'name' => $_POST['name'],
                    'email' => $_POST['email'],
                    'phone' => $_POST['phone'],
                    'nic' => $_POST['nic'],
                    'password' => $_POST['password'],
                    'address' => $_POST['address']
                ];
                if ($admin->addUser($userData)) {
                    setFlashMessage('User added successfully!', 'success');
                } else {
                    setFlashMessage('Failed to add user. Email address may already exist.', 'error');
                }
                header('Location: admin.php');
                exit;
                break;

            case 'update_user':
                $userData = [
                    'name' => $_POST['name'],
                    'email' => $_POST['email'],
                    'phone' => $_POST['phone'],
                    'nic' => $_POST['nic'],
                    'address' => $_POST['address']
                ];
                // Only update password if admin entered a new one
                if (!empty($_POST['password'])) {
                    $userData['password'] = $_POST['password'];
                }
                
                if ($admin->updateUser($_POST['id'], $userData)) {
                    setFlashMessage('User updated successfully!', 'success');
                } else {
                    setFlashMessage('Failed to update user. Email address may already exist.', 'error');
                }
                header('Location: admin.php');
                exit;
                break;
                
            case 'delete_user':
                if ($admin->deleteUser($_POST['id'])) {
                    setFlashMessage('User deleted successfully!', 'success');
                } else {
                    setFlashMessage('Failed to delete user.', 'error');
                }
                header('Location: admin.php');
                exit;
                break;

            case 'add_menu':
                if (isset($_POST['name'], $_POST['category'], $_POST['price'])) {
                    $menuData = [
                        'name' => $_POST['name'],
                        'category' => $_POST['category'],
                        'price' => $_POST['price'],
                        'image' => null,
                        'popularity' => 0
                    ];

                    $itemId = $admin->addMenuItem($menuData);
                    
                    if ($itemId !== false) {
                        
                        // Handle image if uploaded
                        if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
                            $uploadedImage = handleImageUpload($_FILES['image'], $itemId);
                            if ($uploadedImage !== false) {
                                
                                $admin->updateMenuImage($itemId, $uploadedImage);
                            } else {
                                
                                setFlashMessage('Menu item added, but image upload failed. Only JPG, JPEG, PNG & GIF files are allowed.', 'error');
                                header('Location: admin.php');
                                exit;
                            }
                        }
                        

                        setFlashMessage('Menu item added successfully!', 'success');
                    } else {
                        setFlashMessage('Failed to add menu item.', 'error');
                    }
                    header('Location: admin.php');
                    exit;
                }
                break;
        }
    }
}

// Get numbers for dashboard statistics
$totalOrders = $admin->getTotalOrders();
$totalMenuItems = $admin->getTotalMenuItems();
$totalRevenue = $admin->getTotalRevenue();

$usersPage = $_GET['users_page'] ?? 1;
$ordersPage = $_GET['orders_page'] ?? 1;
$usersPerPage = 5;
$ordersPerPage = 10;

$users = $admin->getUsersPaginated($usersPage, $usersPerPage);
$orders = $admin->getOrdersPaginated($ordersPage, $ordersPerPage);
$totalUsers = $admin->getTotalUsers();
$totalOrdersCount = $admin->getTotalOrders();

$hasMoreUsers = ($usersPage * $usersPerPage) < $totalUsers;
$hasMoreOrders = ($ordersPage * $ordersPerPage) < $totalOrdersCount;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuickBite Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Admin stylesheet with timestamp to prevent caching -->
    <link rel="stylesheet" href="css/admin.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="header">
        <h1>QuickBite Admin Dashboard</h1>
        <a href="?action=logout" class="logout-btn">Logout</a>
    </div>

    <div class="container">
        <?php if ($message): ?>
            <div class="alert <?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <!-- Dashboard statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Orders</h3>
                <div class="value"><?php echo $totalOrders; ?></div>
            </div>
            <div class="stat-card">
                <h3>Menu Items</h3>
                <div class="value"><?php echo $totalMenuItems; ?></div>
            </div>
            <div class="stat-card">
                <h3>Total Users</h3>
                <div class="value"><?php echo $totalUsers; ?></div>
            </div>
            <div class="stat-card">
                <h3>Revenue</h3>
                <div class="value">Rs. <?php echo number_format($totalRevenue, 2); ?></div>
            </div>
        </div>

        <!-- User Management and Orders section -->
        <div class="sections">
            <div class="section">
                <h2>User Management</h2>
                <button class="btn btn-success" onclick="openModal('addUserModal')">Add User</button>
                <table class="table" style="margin-top: 1rem;">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>NIC</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($users && count($users) > 0): ?>
                            <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['user_id'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($user['full_name'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($user['email'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($user['nic'] ?? 'N/A'); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn action-btn" onclick="editUser(<?php echo $user['user_id']; ?>)">Edit</button>
                                        <button class="btn btn-danger action-btn" onclick="deleteUser(<?php echo $user['user_id']; ?>)">Delete</button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align: center; color: #666;">No users found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <div class="pagination">
                    <?php
                    $totalUsersPages = ceil($totalUsers / $usersPerPage);
                    $ordersPageParam = isset($_GET['orders_page']) ? '&orders_page=' . $_GET['orders_page'] : '';
                    
                    if ($usersPage > 1): ?>
                        <a href="?users_page=<?php echo $usersPage - 1; ?><?php echo $ordersPageParam; ?>" class="pagination-btn">‹</a>
                    <?php endif;
                    
                    $start = max(1, $usersPage - 2);
                    $end = min($totalUsersPages, $usersPage + 2);
                    
                    if ($start > 1): ?>
                        <a href="?users_page=1<?php echo $ordersPageParam; ?>" class="pagination-btn">1</a>
                        <?php if ($start > 2): ?>
                            <span class="pagination-dots">...</span>
                        <?php endif;
                    endif;
                    
                    for ($i = $start; $i <= $end; $i++): ?>
                        <a href="?users_page=<?php echo $i; ?><?php echo $ordersPageParam; ?>" 
                           class="pagination-btn <?php echo $i == $usersPage ? 'active' : ''; ?>"><?php echo $i; ?></a>
                    <?php endfor;
                    
                    if ($end < $totalUsersPages): ?>
                        <?php if ($end < $totalUsersPages - 1): ?>
                            <span class="pagination-dots">...</span>
                        <?php endif; ?>
                        <a href="?users_page=<?php echo $totalUsersPages; ?><?php echo $ordersPageParam; ?>" class="pagination-btn"><?php echo $totalUsersPages; ?></a>
                    <?php endif;
                    
                    if ($usersPage < $totalUsersPages): ?>
                        <a href="?users_page=<?php echo $usersPage + 1; ?><?php echo $ordersPageParam; ?>" class="pagination-btn">›</a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Orders Section -->
            <div class="section">
                <h2>Orders</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($orders && count($orders) > 0): ?>
                            <?php foreach ($orders as $order): ?>
                            <tr>
                                <td>#<?php echo $order['id']; ?></td>
                                <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                                <td>Rs. <?php echo number_format($order['total'], 2); ?></td>
                                <td><?php echo date('M d, Y', strtotime($order['order_date'])); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" style="text-align: center; color: #666;">No orders found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <div class="pagination">
                    <?php
                    $totalOrdersPages = ceil($totalOrdersCount / $ordersPerPage);
                    $usersPageParam = isset($_GET['users_page']) ? '&users_page=' . $_GET['users_page'] : '';
                    
                    if ($ordersPage > 1): ?>
                        <a href="?orders_page=<?php echo $ordersPage - 1; ?><?php echo $usersPageParam; ?>" class="pagination-btn">‹</a>
                    <?php endif;
                    
                    $start = max(1, $ordersPage - 2);
                    $end = min($totalOrdersPages, $ordersPage + 2);
                    
                    if ($start > 1): ?>
                        <a href="?orders_page=1<?php echo $usersPageParam; ?>" class="pagination-btn">1</a>
                        <?php if ($start > 2): ?>
                            <span class="pagination-dots">...</span>
                        <?php endif;
                    endif;
                    
                    for ($i = $start; $i <= $end; $i++): ?>
                        <a href="?orders_page=<?php echo $i; ?><?php echo $usersPageParam; ?>" 
                           class="pagination-btn <?php echo $i == $ordersPage ? 'active' : ''; ?>"><?php echo $i; ?></a>
                    <?php endfor;
                    
                    if ($end < $totalOrdersPages): ?>
                        <?php if ($end < $totalOrdersPages - 1): ?>
                            <span class="pagination-dots">...</span>
                        <?php endif; ?>
                        <a href="?orders_page=<?php echo $totalOrdersPages; ?><?php echo $usersPageParam; ?>" class="pagination-btn"><?php echo $totalOrdersPages; ?></a>
                    <?php endif;
                    
                    if ($ordersPage < $totalOrdersPages): ?>
                        <a href="?orders_page=<?php echo $ordersPage + 1; ?><?php echo $usersPageParam; ?>" class="pagination-btn">›</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Adding new menu items Section -->
        <div class="section" style="margin-top: 2rem;">
            <h2>Add Menu Item</h2>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="add_menu">
                <div class="form-group">
                    <label for="name">Item Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="category">Category</label>
                    <select id="category" name="category" style="width: 100%; padding: 0.5rem; border: 1px solid #e2e8f0; border-radius: 5px;" required>
                        <option value="Pizza">Pizza</option>
                        <option value="Burgers">Burgers</option>
                        <option value="Salads">Salads</option>
                        <option value="Appetizers">Appetizers</option>
                        <option value="Desserts">Desserts</option>
                        <option value="Beverages">Beverages</option>
                        <option value="Pasta">Pasta</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="price">Price (Rs.)</label>
                    <input type="number" id="price" name="price" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="image">Select Image</label>
                    <input type="file" id="image" name="image" accept="image/*" style="width: 100%; padding: 0.5rem; border: 1px solid #e2e8f0; border-radius: 5px;">
                    <small style="color: #666; font-size: 0.8rem;">Allowed formats: JPG, JPEG, PNG, GIF</small>
                </div>
                <button type="submit" class="btn btn-success">Add Menu Item</button>
            </form>
        </div>
    </div>

    <!-- Popup window for adding new users -->
    <div id="addUserModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('addUserModal')">&times;</span>
            
            <h2>Add User</h2>

            <form method="POST">
                <input type="hidden" name="action" value="add_user">
                <div class="form-group">
                    <label for="userName">Name</label>
                    <input type="text" id="userName" name="name" required>
                </div>
                <div class="form-group">
                    <label for="userEmail">Email</label>
                    <input type="email" id="userEmail" name="email" required>
                </div>
                <div class="form-group">
                    <label for="userPhone">Phone</label>
                    <input type="tel" id="userPhone" name="phone" required>
                </div>
                <div class="form-group">
                    <label for="userNic">NIC</label>
                    <input type="text" id="userNic" name="nic" required placeholder="Enter NIC (9 digits + V/X or 12 digits)">
                </div>
                <div class="form-group">
                    <label for="userPassword">Password</label>
                    <input type="password" id="userPassword" name="password" required>
                </div>
                <div class="form-group">
                    <label for="userAddress">Address</label>
                    <textarea id="userAddress" name="address" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-success">Add User</button>
            </form>
        </div>
    </div>

    <!-- Popup window for editing users -->
    <div id="editUserModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('editUserModal')">&times;</span>
            <h2>Edit User</h2>
            <form method="POST">
                <input type="hidden" name="action" value="update_user">
                <input type="hidden" id="editUserId" name="id">
                <div class="form-group">
                    <label for="editUserName">Name</label>
                    <input type="text" id="editUserName" name="name" required>
                </div>
                <div class="form-group">
                    <label for="editUserEmail">Email</label>
                    <input type="email" id="editUserEmail" name="email" required>
                </div>
                <div class="form-group">
                    <label for="editUserPhone">Phone</label>
                    <input type="tel" id="editUserPhone" name="phone" required>
                </div>
                <div class="form-group">
                    <label for="editUserNic">NIC</label>
                    <input type="text" id="editUserNic" name="nic" required placeholder="Enter NIC (9 digits + V/X or 12 digits)">
                </div>
                <div class="form-group">
                    <label for="editUserPassword">Password (leave blank to keep current)</label>
                    <input type="password" id="editUserPassword" name="password" placeholder="Leave blank to keep current password">
                </div>
                <div class="form-group">
                    <label for="editUserAddress">Address</label>
                    <textarea id="editUserAddress" name="address" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-success">Update User</button>
            </form>
        </div>
    </div>

    <script>
        // Function to show popup windows
        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'block';
        }

        // Function to hide popup windows
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        function editUser(id) {
            fetch(`?action=get_user&id=${id}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        alert(`Error: ${data.error}`);
                        return;
                    }
                    
                    // Fill the edit form with user data
                    if (data && data.user_id) {
                        document.getElementById('editUserId').value = data.user_id;
                        document.getElementById('editUserName').value = data.full_name || '';
                        document.getElementById('editUserEmail').value = data.email || '';
                        document.getElementById('editUserPhone').value = data.contact_number || '';
                        document.getElementById('editUserNic').value = data.nic || '';
                        document.getElementById('editUserPassword').value = ''; // Clear password field
                        document.getElementById('editUserAddress').value = data.address || '';
                        openModal('editUserModal');
                    } else {
                        alert('User data not found');
                    }
                })
                .catch(error => {
                    console.error('Error fetching user:', error);
                    alert('Error loading user data. Please try again.');
                });
        }

        function deleteUser(id) {
            if (confirm('Are you sure you want to delete this user?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete_user">
                    <input type="hidden" name="id" value="${id}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }

        // Auto-hide alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                setTimeout(() => alert.style.display = 'none', 500);
            });
        }, 5000);

        // NIC validation function
        function validateNIC(nic) {
            // Sri Lankan NIC formats:
            // Old format: 9 digits + V/v/X/x
            // New format: 12 digits
            const oldNICPattern = /^[0-9]{9}[vVxX]$/;
            const newNICPattern = /^[0-9]{12}$/;
            
            return oldNICPattern.test(nic) || newNICPattern.test(nic);
        }

        // Add event listeners for NIC validation
        document.addEventListener('DOMContentLoaded', function() {
            const nicFields = ['userNic', 'editUserNic'];
            
            nicFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.addEventListener('blur', function() {
                        const nic = this.value.trim();
                        if (nic && !validateNIC(nic)) {
                            // Show red border if invalid
                            this.style.borderColor = '#ff4444';
                            this.title = 'Invalid NIC format. Use 9 digits + V/X or 12 digits';
                        } else {
                            // Remove red border if valid
                            this.style.borderColor = '';
                            this.title = '';
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>