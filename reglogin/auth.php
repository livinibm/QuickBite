<?php
session_start();

// Connect to DB
$conn = new mysqli("localhost", "root", "", "quickbite");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to redirect with a specific form active
function redirect_with_form($form_id) {
    $_SESSION['active_form'] = $form_id;
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle Registration Form Submission
if (isset($_POST['register'])) {
    // Get values from form
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $contact = trim($_POST['contact_number'] ?? '');
    $nic = trim($_POST['nic'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validate inputs
    $errors = [];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    if (!preg_match('/^[0-9]{10}$/', $contact)) {
        $errors[] = "Contact number must be exactly 10 digits.";
    }

    if (!preg_match('/^[0-9]{9}[vVxX]$|^[0-9]{12}$/', $nic)) {
        $errors[] = "Invalid NIC format. Must be 9 digits with 'V/v' or 12 digits.";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // Check if email already exists
    $stmt_email = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt_email->bind_param("s", $email);
    $stmt_email->execute();
    $stmt_email->store_result();
    if ($stmt_email->num_rows > 0) {
        $errors[] = "Email already registered.";
    }
    $stmt_email->close();

    // Check if NIC already exists
    $stmt_nic = $conn->prepare("SELECT user_id FROM users WHERE nic = ?");
    $stmt_nic->bind_param("s", $nic);
    $stmt_nic->execute();
    $stmt_nic->store_result();
    if ($stmt_nic->num_rows > 0) {
        $errors[] = "NIC already registered.";
    }
    $stmt_nic->close();

    // If there are errors, store them in a session variable and redirect to the register form
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        redirect_with_form('registerForm');
    } else {
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert into database
        $user_role = 'customer';
        $stmt = $conn->prepare("INSERT INTO users (full_name, email, contact_number, nic, password, user_role) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $full_name, $email, $contact, $nic, $hashedPassword, $user_role);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Registration successful! You can now log in.";
        } else {
            $_SESSION['errors'] = ["Error: " . $stmt->error];
        }
        $stmt->close();
        // Redirect to the login form after registration
        redirect_with_form('loginForm');
    }
}

// Handle Login Form Submission
if (isset($_POST['login'])) {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $errors = [];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    if (empty($password)) {
        $errors[] = "Password cannot be empty.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT user_id, password, user_role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_role'] = $user['user_role'];
                $_SESSION['success_message'] = "Login successful!";

                if ($user['user_role'] === 'customer') {
                    header("Location: ../Homepage/index.php");
                } elseif($user['user_role'] === 'admin') {
                    header("Location: ../admin/admin.php");
                }
                exit();
            } else {
                $errors[] = "Invalid email or password.";
            }
        } else {
            $errors[] = "Invalid email or password.";
        }
        $stmt->close();
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        redirect_with_form('loginForm');
    }
}

// Handle Forgot Password Form Submission (Step 1: Validate Email and NIC)
if (isset($_POST['forgot_password'])) {
    $email = trim($_POST['email'] ?? '');
    $nic = trim($_POST['nic'] ?? '');
    $errors = [];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($nic)) {
        $errors[] = "NIC cannot be empty.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ? AND nic = ?");
        $stmt->bind_param("ss", $email, $nic);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            // Store user_id in session to be used in the next step
            $_SESSION['reset_user_id'] = $user['user_id'];
            $_SESSION['success_message'] = "Please enter and confirm your new password.";
            redirect_with_form('resetPasswordForm'); // Redirect to the new password form
        } else {
            $errors[] = "The email and NIC you provided do not match our records.";
        }
        $stmt->close();
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        redirect_with_form('forgotPasswordForm');
    }
}

// Handle Reset Password Form Submission (Step 2: Update the password)
if (isset($_POST['reset_password'])) {
    $new_password = $_POST['new_password'] ?? '';
    $confirm_new_password = $_POST['confirm_new_password'] ?? '';
    $user_id_to_reset = $_SESSION['reset_user_id'] ?? null;
    $errors = [];

    // Check if user_id is in the session and passwords are valid
    if (!$user_id_to_reset) {
        $errors[] = "Invalid request. Please start the password reset process again.";
    }

    if (strlen($new_password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    if ($new_password !== $confirm_new_password) {
        $errors[] = "New passwords do not match.";
    }

    if (empty($errors)) {
        $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);

        $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
        $update_stmt->bind_param("si", $hashed_new_password, $user_id_to_reset);

        if ($update_stmt->execute()) {
            $_SESSION['success_message'] = "Your password has been successfully reset. You can now log in with your new password.";
        } else {
            $_SESSION['errors'] = ["Error updating password: " . $update_stmt->error];
        }
        $update_stmt->close();
        
        // Clear the temporary session variable
        unset($_SESSION['reset_user_id']);
        
        // Redirect back to the login form
        redirect_with_form('loginForm');
    } else {
        $_SESSION['errors'] = $errors;
        redirect_with_form('resetPasswordForm');
    }
}

$conn->close();

// Determine which form should be active on page load
$active_form_id = $_SESSION['active_form'] ?? 'loginForm';
unset($_SESSION['active_form']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>QuickBite - Login/Register</title>
    <link rel="stylesheet" href="auth.css" />
</head>

<body>
    <?php
    // Display success message from session
    if (isset($_SESSION['success_message'])) {
        echo "<div class='success-message'>" . nl2br(htmlspecialchars($_SESSION['success_message'])) . "</div>";
        unset($_SESSION['success_message']);
    }
    
    // Display errors from session
    if (isset($_SESSION['errors'])) {
        echo "<div class='error-message'>";
        foreach ($_SESSION['errors'] as $error) {
            echo "<p>" . htmlspecialchars($error) . "</p>";
        }
        echo "</div>";
        unset($_SESSION['errors']);
    }
    ?>

    <div class="left-content">
        <h1>QuickBite</h1>
        <p>The flavor you crave, delivered in a flash.</p>
    </div>

    <div class="auth-container">
        <div class="tab-buttons">
            <button id="loginTab">Login</button>
            <button id="registerTab">Register</button>
        </div>

        <div class="form-container">
            <form method="post" action="" id="loginForm" class="form">
                <h2>Login</h2>
                <input type="email" name="email" placeholder="Email" required />
                <input type="password" name="password" placeholder="Password" required />
                <button type="submit" name="login">Login</button>
                <a href="#" id="forgotPasswordLink" class="forgot-link">Forgot Password?</a>
            </form>

            <form method="post" action="" id="registerForm" class="form">
                <h2>Register</h2>
                <input type="text" name="full_name" placeholder="Full Name" required />
                <input type="email" name="email" placeholder="Email" required />
                <input type="text" name="contact_number" placeholder="Contact Number" required />
                <input type="text" name="nic" placeholder="NIC" required />
                <input type="password" name="password" placeholder="Password" required />
                <input type="password" name="confirm_password" placeholder="Confirm Password" required />
                <button type="submit" name="register">Register</button>
            </form>

            <form method="post" action="" id="forgotPasswordForm" class="form">
                <h2>Forgot Password</h2>
                <p class="form-description">Enter your email and NIC to verify your account.</p>
                <input type="email" name="email" placeholder="Email" required />
                <input type="text" name="nic" placeholder="NIC" required />
                <button type="submit" name="forgot_password">Verify</button>
                <a href="#" id="backToLoginLink" class="back-link">Back to Login</a>
            </form>

            <form method="post" action="" id="resetPasswordForm" class="form">
                <h2>Reset Password</h2>
                <p class="form-description">Enter and confirm your new password.</p>
                <input type="password" name="new_password" placeholder="New Password" required />
                <input type="password" name="confirm_new_password" placeholder="Confirm New Password" required />
                <button type="submit" name="reset_password">Change Password</button>
                <a href="#" id="backToLoginFromResetLink" class="back-link">Back to Login</a>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Get all the necessary elements
            const loginTab = document.getElementById("loginTab");
            const registerTab = document.getElementById("registerTab");
            const loginForm = document.getElementById("loginForm");
            const registerForm = document.getElementById("registerForm");
            const forgotPasswordLink = document.getElementById("forgotPasswordLink");
            const forgotPasswordForm = document.getElementById("forgotPasswordForm");
            const backToLoginLink = document.getElementById("backToLoginLink");
            const resetPasswordForm = document.getElementById("resetPasswordForm");
            const backToLoginFromResetLink = document.getElementById("backToLoginFromResetLink");

            // PHP sets the active form in a hidden input or JS variable, let's use a JS variable.
            const activeFormId = "<?php echo $active_form_id; ?>";

            function showForm(formId) {
                // Deactivate all tabs and forms
                loginTab.classList.remove("active");
                registerTab.classList.remove("active");
                loginForm.classList.remove("active");
                registerForm.classList.remove("active");
                forgotPasswordForm.classList.remove("active");
                resetPasswordForm.classList.remove("active");

                // Activate the selected form and tab
                if (formId === 'loginForm') {
                    loginTab.classList.add("active");
                    loginForm.classList.add("active");
                } else if (formId === 'registerForm') {
                    registerTab.classList.add("active");
                    registerForm.classList.add("active");
                } else if (formId === 'forgotPasswordForm') {
                    // No tab should be active for forgot password
                    forgotPasswordForm.classList.add("active");
                } else if (formId === 'resetPasswordForm') {
                    resetPasswordForm.classList.add("active");
                }
            }

            // Set the initial active form on page load
            showForm(activeFormId);

            // Add event listeners for tab switching
            loginTab.addEventListener("click", () => showForm('loginForm'));
            registerTab.addEventListener("click", () => showForm('registerForm'));

            // Add event listeners for forgot password link
            forgotPasswordLink.addEventListener("click", (e) => {
                e.preventDefault();
                showForm('forgotPasswordForm');
            });

            // Add event listener for back to login link
            backToLoginLink.addEventListener("click", (e) => {
                e.preventDefault();
                showForm('loginForm');
            });

            // Add event listener for back to login link from reset form
            backToLoginFromResetLink.addEventListener("click", (e) => {
                e.preventDefault();
                showForm('loginForm');
            });
        });
    </script>
</body>

</html>