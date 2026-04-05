<?php
session_start();
require './config/connect.php';

$message = '';
$message_type = '';
$showForm = false;

if (isset($_GET['member_id'], $_GET['reset_code'])) {
    $member_id = filter_input(INPUT_GET, 'member_id', FILTER_VALIDATE_INT);
    $reset_code = filter_input(INPUT_GET, 'reset_code', FILTER_SANITIZE_STRING);

    if ($member_id && $reset_code) {
        $stmt = $conn->prepare("SELECT member_id, expires_at FROM password_resets WHERE member_id = ? AND reset_code = ?");
        $stmt->bind_param("is", $member_id, $reset_code);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $reset_data = $result->fetch_assoc();
            $expires_at = new DateTime($reset_data['expires_at']);
            $current_time = new DateTime();

            if ($expires_at > $current_time) {
                $showForm = true;
            } else {
                $message = 'error:Your reset link has expired. Please request a new password reset.';
                $message_type = 'error';
            }
        } else {
            $message = 'error:Invalid reset request. Please ensure you\'re using the correct link or request a new password reset.';
            $message_type = 'error';
        }
    } else {
        $message = 'error:Invalid request parameters. Please try resetting your password again.';
        $message_type = 'error';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_password'], $_POST['confirm_password'])) {
    $member_id = filter_input(INPUT_GET, 'member_id', FILTER_VALIDATE_INT);
    $reset_code = filter_input(INPUT_GET, 'reset_code', FILTER_SANITIZE_STRING);
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($member_id && $reset_code && $new_password && $confirm_password) {
        if ($new_password === $confirm_password) {
            if (strlen($new_password) < 8) {
                $message = 'error:Password must be at least 8 characters long.';
                $message_type = 'error';
                $showForm = true;
            } elseif (!preg_match('/[A-Z]/', $new_password)) {
                $message = 'error:Password must contain at least one uppercase letter!';
                $message_type = 'error';
                $showForm = true;
            } elseif (!preg_match('/[a-z]/', $new_password)) {
                $message = 'error:Password must contain at least one lowercase letter!';
                $message_type = 'error';
                $showForm = true;
            } elseif (!preg_match('/[0-9]/', $new_password)) {
                $message = 'error:Password must contain at least one number!';
                $message_type = 'error';
                $showForm = true;
            } else {
                $stmt = $conn->prepare("SELECT member_id, expires_at FROM password_resets WHERE member_id = ? AND reset_code = ?");
                $stmt->bind_param("is", $member_id, $reset_code);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $reset_data = $result->fetch_assoc();
                    $expires_at = new DateTime($reset_data['expires_at']);
                    $current_time = new DateTime();

                    if ($expires_at > $current_time) {
                        $hashed_pass = md5($new_password);

                        $stmt = $conn->prepare("UPDATE user_info SET password = ? WHERE member_id = ?");
                        $stmt->bind_param("si", $hashed_pass, $reset_data['member_id']);

                        if ($stmt->execute()) {
                            $stmt = $conn->prepare("DELETE FROM password_resets WHERE member_id = ?");
                            $stmt->bind_param("i", $member_id);
                            $stmt->execute();

                            $_SESSION['success_message'] = 'Your password has been reset successfully. You can now log in with your new password.';
                            header("Location: login.php");
                            exit();
                        } else {
                            $message = 'error:Failed to update password. Please try again.';
                            $message_type = 'error';
                            $showForm = true;
                        }
                    } else {
                        $message = 'error:Your reset link has expired. Please request a new password reset.';
                        $message_type = 'error';
                    }
                } else {
                    $message = 'error:Invalid reset request. Please ensure you\'re using the correct link or request a new password reset.';
                    $message_type = 'error';
                }
            }
        } else {
            $message = 'error:Passwords do not match. Please try again.';
            $message_type = 'error';
            $showForm = true;
        }
    } else {
        $message = 'error:Please fill in all fields.';
        $message_type = 'error';
        $showForm = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | IskolarEase</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style/reset_password.css">
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
                    <div class="feature-item"><i class="fas fa-shield-alt"></i><span>Secure Reset</span></div>
                    <div class="feature-item"><i class="fas fa-lock"></i><span>Strong Password</span></div>
                    <div class="feature-item"><i class="fas fa-check-circle"></i><span>Instant Update</span></div>
                    <div class="feature-item"><i class="fas fa-key"></i><span>Easy Recovery</span></div>
                </div>
                <div class="testimonial">
                    <p>Create a strong new password to secure your IskolarEase account.</p>
                </div>
            </div>
        </div>

        <div class="right-panel">
            <a href="index.php" class="home-button"><i class="fas fa-home"></i><span>Home</span></a>

            <div class="reset-password-container">
                <img src="logo.png" alt="IskolarEase" class="mobile-logo">
                <div class="reset-password-header">
                    <h2><i class="fas fa-lock"></i> Reset Password</h2>
                    <p>Create a new password for your account</p>
                </div>

                <?php if (!empty($message)): ?>
                    <?php
                    $display_message = str_replace('error:', '', $message);
                    $alert_class = strpos($message, 'error:') === 0 ? 'error-alert' : 'success-alert';
                    ?>
                    <div class="<?php echo $alert_class; ?>">
                        <i class="fas <?php echo $alert_class === 'error-alert' ? 'fa-exclamation-circle' : 'fa-check-circle'; ?>"></i>
                        <span><?php echo htmlspecialchars($display_message); ?></span>
                    </div>
                <?php endif; ?>

                <?php if ($showForm): ?>
                <form action="reset_password.php?member_id=<?php echo htmlspecialchars($_GET['member_id'] ?? ''); ?>&reset_code=<?php echo htmlspecialchars($_GET['reset_code'] ?? ''); ?>" method="POST" id="resetPasswordForm">
                    <div class="input-group">
                        <label for="new_password"><i class="fas fa-lock"></i> New Password</label>
                        <div class="password-field">
                            <div class="input-wrapper">
                                <input type="password" name="new_password" id="new_password" placeholder="Create new password" required oninput="validatePassword()">
                                <button type="button" class="password-toggle" onclick="togglePassword('new_password')"><i class="fas fa-eye"></i></button>
                            </div>
                        </div>
                        <div class="validation-message" id="new-password-message">
                            <i class="fas fa-info-circle"></i> Min 8 chars, 1 uppercase, 1 lowercase, 1 number
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="confirm_password"><i class="fas fa-lock"></i> Confirm Password</label>
                        <div class="password-field">
                            <div class="input-wrapper">
                                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm new password" required oninput="validatePassword()">
                                <button type="button" class="password-toggle" onclick="togglePassword('confirm_password')"><i class="fas fa-eye"></i></button>
                            </div>
                        </div>
                        <div class="validation-message" id="confirm-password-message">
                            <i class="fas fa-info-circle"></i> Must match password
                        </div>
                    </div>

                    <button type="submit" name="submit" class="submit-button" id="submitBtn">
                        <i class="fas fa-sync-alt"></i> Reset Password
                    </button>
                </form>

                <div class="register-prompt">
                    <i class="fas fa-arrow-left"></i>
                    <span>Remember your password?</span>
                    <a href="login.php" class="register-link">Sign In</a>
                </div>

                <?php else: ?>
                <div class="register-prompt" style="margin-bottom: 1rem;">
                    <i class="fas fa-exclamation-triangle" style="color: #f57c00;"></i>
                    <span>Invalid or expired reset link</span>
                </div>
                <div class="register-prompt">
                    <i class="fas fa-redo-alt"></i>
                    <span>Need a new reset link?</span>
                    <a href="forgot_password.php" class="register-link">Request Reset</a>
                </div>
                <?php endif; ?>

                <div class="login-footer">
                    <p>&copy; <?php echo date('Y'); ?> IskolarEase. All rights reserved.</p>
                    <div class="footer-links">
                        <a href="#">Privacy Policy</a> • <a href="#">Terms of Service</a> • <a href="#">Contact Support</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const newPassword = document.getElementById('new_password');
        const confirmPassword = document.getElementById('confirm_password');
        const resetForm = document.getElementById('resetPasswordForm');
        const submitBtn = document.getElementById('submitBtn');
        const newMsg = document.getElementById('new-password-message');
        const confirmMsg = document.getElementById('confirm-password-message');

        function togglePassword(fieldId) {
            const input = document.getElementById(fieldId);
            const icon = input.nextElementSibling.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'fas fa-eye';
            }
        }

        function validatePassword() {
            const pass = newPassword.value;
            const confirm = confirmPassword.value;

            const hasLength = pass.length >= 8;
            const hasUpper = /[A-Z]/.test(pass);
            const hasLower = /[a-z]/.test(pass);
            const hasNumber = /[0-9]/.test(pass);
            const isValid = hasLength && hasUpper && hasLower && hasNumber;

            newMsg.className = `validation-message ${isValid ? 'valid' : ''}`;
            if (isValid) {
                newMsg.innerHTML = '<i class="fas fa-info-circle"></i> Password is strong';
            } else {
                let req = [];
                if (!hasLength) req.push('8+ chars');
                if (!hasUpper) req.push('uppercase');
                if (!hasLower) req.push('lowercase');
                if (!hasNumber) req.push('number');
                newMsg.innerHTML = `<i class="fas fa-info-circle"></i> Need: ${req.join(', ')}`;
            }

            const match = pass && confirm && pass === confirm;
            confirmMsg.className = `validation-message ${match ? 'valid' : ''}`;
            if (confirm.length === 0) {
                confirmMsg.innerHTML = '<i class="fas fa-info-circle"></i> Must match password';
            } else if (match) {
                confirmMsg.innerHTML = '<i class="fas fa-info-circle"></i> Passwords match';
            } else {
                confirmMsg.innerHTML = '<i class="fas fa-info-circle"></i> Passwords do not match';
            }
        }

        newPassword?.addEventListener('input', validatePassword);
        confirmPassword?.addEventListener('input', validatePassword);

        if (resetForm) {
            resetForm.addEventListener('submit', function(e) {
                const pass = newPassword.value;
                const confirm = confirmPassword.value;

                if (pass.length < 8 || !/[A-Z]/.test(pass) || !/[a-z]/.test(pass) || !/[0-9]/.test(pass) || pass !== confirm) {
                    e.preventDefault();
                    alert('Please meet all password requirements');
                    return false;
                }

                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Resetting...';
            });
        }

        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>
</html>
