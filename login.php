<?php
ob_start();
session_start();
include('./config/connect.php');

if (isset($_SESSION['member_id'])) {
    switch ($_SESSION['role']) {
        case 'admin':
            header('Location: admin/admin_dashboard.php');
            exit();
        case 'student':
            header('Location: student/student_scholarships.php');
            exit();
        case 'provider':
            header('Location: provider/provider_dashboard.php');
            exit();
    }
}

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = $_POST['password'];
    $error = array();

    $select = "SELECT * FROM user_info WHERE email = '$email' AND status = 'enabled'";
    $result = mysqli_query($conn, $select);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        $hashed_pass = md5($pass);

        if ($hashed_pass === $row['password']) {
            $_SESSION['member_id'] = $row['member_id'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['role'] = $row['role'];

            switch ($row['role']) {
                case 'admin':
                    header('Location: admin/admin_dashboard.php');
                    exit();
                case 'student':
                    header('Location: student/student_scholarships.php');
                    exit();
                case 'provider':
                    if ($row['provider_status'] === 'verified') {
                        header('Location: provider/provider_dashboard.php');
                    } else {
                        header('Location: provider/provider_details.php');
                    }
                    exit();
                default:
                    header('Location: index.php');
                    exit();
            }
        } else {
            $error[] = 'Incorrect email or password!';
        }
    } else {
        $error[] = 'No user found with that email or your account is disabled!';
    }
}
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | IskolarEase - University of Caloocan City</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style/login.css">
</head>
<body>
    <div class="split-screen">
        <div class="left-panel" style="background-image: url('bg.jpg');">
            <div class="overlay"></div>
            <div class="brand-content">
                <div class="brand-logo">
                    <img src="logo.png" alt="IskolarEase Logo" class="main-logo">
                    <img src="ucc.png" alt="UCC" class="ucc-badge">
                </div>
                <h1 class="brand-title">IskolarEase</h1>
                <p class="brand-subtitle">University of Caloocan City</p>

                <div class="feature-grid">
                    <div class="feature-item">
                        <i class="fas fa-graduation-cap"></i>
                        <span>For Students</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-briefcase"></i>
                        <span>For Providers</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-shield-alt"></i>
                        <span>Secure Portal</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-clock"></i>
                        <span>24/7 Access</span>
                    </div>
                </div>

                <div class="testimonial">
                    <p>Secure access to your academic portal. Please login with your credentials to continue.</p>
                </div>
            </div>
        </div>

        <div class="right-panel">
            <a href="index.php" class="home-button">
                <i class="fas fa-home"></i>
                <span>Home</span>
            </a>

            <div class="login-container">
                <img src="logo.png" alt="IskolarEase" class="mobile-logo">

                <div class="login-header">
                    <h2>Welcome Back!</h2>
                    <p>Please sign in to your account</p>
                </div>

                <?php if (isset($error)): ?>
                    <?php foreach ($error as $error_msg): ?>
                        <div class="error-alert">
                            <i class="fas fa-exclamation-circle"></i>
                            <span><?php echo $error_msg; ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <form action="" method="post" class="login-form">
                    <div class="input-group">
                        <label for="email">
                            <i class="fas fa-envelope"></i> Email Address
                        </label>
                        <input type="email" name="email" id="email" placeholder="Enter your email address"
                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required autofocus>
                    </div>

                    <div class="input-group">
                        <label for="password">
                            <i class="fas fa-lock"></i> Password
                        </label>
                        <div class="password-field">
                            <input type="password" name="password" id="password" placeholder="Enter your password" required>
                            <button type="button" class="password-toggle" onclick="togglePassword()">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-actions">
                        <label class="remember-checkbox">
                            <input type="checkbox" name="remember" <?php echo isset($_POST['remember']) ? 'checked' : ''; ?>>
                            <span class="checkbox-custom"></span>
                            <span class="remember-text">Remember me</span>
                        </label>
                        <a href="forgot_password.php" class="forgot-password">
                            <i class="fas fa-key"></i> Forgot Password?
                        </a>
                    </div>

                    <button type="submit" name="submit" class="login-button">
                        <i class="fas fa-sign-in-alt"></i> Sign In
                    </button>
                </form>

                <div class="register-prompt">
                    <i class="fas fa-user-plus"></i>
                    <span>Don't have an account?</span>
                    <a href="register.php" class="register-link">Sign Up</a>
                </div>

                <div class="login-footer">
                    <p>&copy; <?php echo date('Y'); ?> IskolarEase. All rights reserved.</p>
                    <div class="footer-links">
                        <a href="#">Privacy Policy</a>
                        <span class="separator-dot">•</span>
                        <a href="#">Terms of Service</a>
                        <span class="separator-dot">•</span>
                        <a href="#">Contact Support</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleButton = document.querySelector('.password-toggle i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleButton.classList.remove('fa-eye');
                toggleButton.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleButton.classList.remove('fa-eye-slash');
                toggleButton.classList.add('fa-eye');
            }
        }

        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>
</html>
