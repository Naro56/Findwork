<?php
// Start the session and include required files at the very top
// with no whitespace or output before this
session_start();
require_once 'includes/db.php';

// Check if functions.php exists in the includes directory
if (file_exists('includes/functions.php')) {
    require_once 'includes/functions.php';
} else {
    // Define the required functions if the file doesn't exist
    function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    function isRecruiter() {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'recruiter';
    }
}

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: ' . (isRecruiter() ? 'recruiter/dashboard.php' : 'dashboard.php'));
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Please fill in all fields';
    } else {
        $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($user = $result->fetch_assoc()) {
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['role'] = $user['role'];
                
                // Redirect based on role
                header('Location: ' . ($user['role'] === 'recruiter' ? 'recruiter/dashboard.php' : 'dashboard.php'));
                exit;
            } else {
                $error = 'Invalid email or password';
            }
        } else {
            $error = 'Invalid email or password';
        }
    }
}

// Include the header after all potential redirects
require_once 'includes/header.php';
?>

<div class="container">
    <div class="form-container">
        <h1>Login</h1>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        
        <p class="mt-3">
            Don't have an account? 
            <a href="signup.php">Sign up</a>
        </p>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
