<?php

require_once __DIR__ . '/../dbconnection/database.php';

class Admin {
    private $conn;
    private $table_menu = 'menu_items';
    private $table_orders = 'orders';
    private $table_users = 'users';
    private $table_cart = 'cart_items';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /* Authenticate admin login */
    public function login($username, $password) {
        $query = "SELECT user_id, full_name, email, password FROM " . $this->table_users . " WHERE email = ? AND user_role = 'admin' LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $username);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row && password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['user_role'] = 'admin';
            $_SESSION['admin_username'] = $row['full_name'];
            $_SESSION['admin_email'] = $row['email'];
            return true;
        }
        return false;
    }

    public function isLoggedIn() {
        return isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'admin';
    }

    /* Admin logout */
    public function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_role']);
        unset($_SESSION['admin_username']);
        unset($_SESSION['admin_email']);
        session_destroy();
    }

    /* Get all orders */
    public function getAllOrders() {
        $query = "SELECT * FROM " . $this->table_orders . " ORDER BY order_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* Get all menu items */
    public function getAllMenuItems() {
        $query = "SELECT * FROM " . $this->table_menu . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* Get menu item by ID */
    public function getMenuItemById($id) {
        $query = "SELECT * FROM " . $this->table_menu . " WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* Add new menu item */
    public function addMenuItem($data) {
        $query = "INSERT INTO " . $this->table_menu . " (name, category, price, image, popularity) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        
        $success = $stmt->execute([
            $data['name'],
            $data['category'],
            $data['price'],
            $data['image'],
            $data['popularity'] ?? 0
        ]);

        if ($success) {
            return $this->conn->lastInsertId();
        }
        
        return false;
    }

    /* Update menu item */
    public function updateMenuItem($id, $data) {
        $query = "UPDATE " . $this->table_menu . " SET name = ?, category = ?, price = ?, image = ?, popularity = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        
        return $stmt->execute([
            $data['name'],
            $data['category'],
            $data['price'],
            $data['image'],
            $data['popularity'] ?? 0,
            $id
        ]);
    }

    /* Update a menu item's image */
    public function updateMenuImage($itemId, $imageFileName) {
        $stmt = $this->conn->prepare("UPDATE menu_items SET image = ? WHERE id = ?");
        return $stmt->execute([$imageFileName, $itemId]);
    }

    /* Delete menu item */
    public function deleteMenuItem($id) {
        $query = "DELETE FROM " . $this->table_menu . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        return $stmt->execute();
    }

    /* Delete order */
    public function deleteOrder($id) {
        $query = "DELETE FROM " . $this->table_orders . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        return $stmt->execute();
    }

    /* Get all users (excluding admin) */
    public function getAllUsers() {
        $query = "SELECT * FROM " . $this->table_users . " WHERE user_role != 'admin' ORDER BY user_id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   
     /* Pagination */
    public function getUsersPaginated($page = 1, $limit = 5) {
        $offset = ($page - 1) * $limit;
        $query = "SELECT * FROM " . $this->table_users . " WHERE user_role != 'admin' ORDER BY user_id DESC LIMIT ? OFFSET ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $limit, PDO::PARAM_INT);
        $stmt->bindParam(2, $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   
    public function getOrdersPaginated($page = 1, $limit = 10) {
        $offset = ($page - 1) * $limit;
        $query = "SELECT id, customer_name, total, order_date FROM " . $this->table_orders . " ORDER BY order_date DESC LIMIT ? OFFSET ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $limit, PDO::PARAM_INT);
        $stmt->bindParam(2, $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* Total number of users */
    public function getTotalUsers() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_users . " WHERE user_role != 'admin'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    /* Get user by ID */
    public function getUserById($id) {
        $query = "SELECT * FROM " . $this->table_users . " WHERE user_id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* Add new user */
    public function addUser($data) {
        try {
            // Check if email already exists
            $checkQuery = "SELECT COUNT(*) as count FROM " . $this->table_users . " WHERE email = ?";
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->execute([$data['email']]);
            $result = $checkStmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result['count'] > 0) {
                
                return false;
            }
          
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        
            $query = "INSERT INTO " . $this->table_users . " (full_name, email, contact_number, nic, address, password, user_role) VALUES (?, ?, ?, ?, ?, ?, 'customer')";
            $stmt = $this->conn->prepare($query);
            
            return $stmt->execute([
                $data['name'],
                $data['email'],
                $data['phone'],
                $data['nic'],
                $data['address'],
                $hashedPassword
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /* Update user */
    public function updateUser($id, $data) {
        try {
            // Check if email already exists for another user
            $checkQuery = "SELECT COUNT(*) as count FROM " . $this->table_users . " WHERE email = ? AND user_id != ?";
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->execute([$data['email'], $id]);
            $result = $checkStmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result['count'] > 0) {
                return false;
            }
            
            $fields = [];
            $values = [];
            
            $fields[] = "full_name = ?";
            $values[] = $data['name'];
            
            $fields[] = "email = ?";
            $values[] = $data['email'];
            
            $fields[] = "contact_number = ?";
            $values[] = $data['phone'];
            
            $fields[] = "nic = ?";
            $values[] = $data['nic'];
            
            $fields[] = "address = ?";
            $values[] = $data['address'];
            
            // Update password if provided
            if (isset($data['password']) && !empty($data['password'])) {
                $fields[] = "password = ?";
                $values[] = password_hash($data['password'], PASSWORD_DEFAULT);
            }
            
            $values[] = $id; 
            
            // Update user
            $query = "UPDATE " . $this->table_users . " SET " . implode(', ', $fields) . " WHERE user_id = ? AND user_role != 'admin'";
            $stmt = $this->conn->prepare($query);
            
            return $stmt->execute($values);
        } catch (PDOException $e) {
            return false;
        }
    }

    /* Delete user */
    public function deleteUser($id) {
        $query = "DELETE FROM " . $this->table_users . " WHERE user_id = ? AND user_role != 'admin'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        return $stmt->execute();
    }

    /* Get total number of orders */
    public function getTotalOrders() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_orders;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    /* Get total number of menu items */
    public function getTotalMenuItems() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_menu;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    /* Get total revenue from orders */
    public function getTotalRevenue() {
        $query = "SELECT SUM(total) as revenue FROM " . $this->table_orders;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return floatval($result['revenue'] ?? 0);
    }

    /* Get recent orders */
    public function getRecentOrders() {
        $query = "SELECT id, customer_name, total, order_date FROM " . $this->table_orders . " ORDER BY order_date DESC LIMIT 10";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>