<?php
require './mailer/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require './config/connect.php';

session_start();

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

    if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $conn->prepare("SELECT member_id FROM user_info WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $resetCode = bin2hex(random_bytes(8));
            $expires_at = date("Y-m-d H:i:s", strtotime('+1 hour'));

            $stmt = $conn->prepare("DELETE FROM password_resets WHERE member_id = ?");
            $stmt->bind_param("i", $user['member_id']);
            $stmt->execute();

            $stmt = $conn->prepare("INSERT INTO password_resets (member_id, email, reset_code, expires_at) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $user['member_id'], $email, $resetCode, $expires_at);
            $stmt->execute();

            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'iskolarease.ucc@gmail.com';
                $mail->Password   = 'gvrc xsof larq eyfx';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port       = 465;

                $mail->setFrom('iskolarease.ucc@gmail.com', 'IskolarEase');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Password Reset Request - IskolarEase';
                $mail->Body = "
                <html>
                <head>
                    <style>
                        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                        .header { background: linear-gradient(135deg, #2e7d32, #1a4d1e); color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
                        .content { background: #f9f9f9; padding: 30px; border: 1px solid #ddd; border-top: none; }
                        .code { background: #fff; padding: 20px; text-align: center; font-size: 32px; font-weight: bold; color: #2e7d32; border: 2px dashed #f57c00; margin: 20px 0; border-radius: 10px; }
                        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
                        .warning { color: #f57c00; font-weight: bold; }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='header'>
                            <h2>IskolarEase Password Reset</h2>
                        </div>
                        <div class='content'>
                            <p>Hello,</p>
                            <p>We received a request to reset your password for your IskolarEase account. Use the verification code below:</p>

                            <div class='code'>
                                $resetCode
                            </div>

                            <p><span class='warning'>⚠️ Important:</span> This code will expire in 1 hour.</p>

                            <p>If you didn't request this password reset, please ignore this email or contact support if you have concerns.</p>

                            <p>Best regards,<br>IskolarEase Team</p>
                        </div>
                        <div class='footer'>
                            <p>This is an automated message, please do not reply to this email.</p>
                            <p>&copy; " . date('Y') . " IskolarEase. All rights reserved.</p>
                        </div>
                    </div>
                </body>
                </html>
                ";

                $mail->AltBody = "Your password reset code is: $resetCode\n\nThis code will expire in 1 hour.\n\nIf you didn't request this, please ignore this email.";

                $mail->send();
                $_SESSION['reset_email'] = $email;
                $_SESSION['success_message'] = 'Password reset code has been sent to your email! Please check your inbox.';

                header("Location: reset_code.php");
                exit();

            } catch (Exception $e) {
                $message = 'error:Failed to send email. Please try again later.';
                $message_type = 'error';
            }
        } else {
            $message = 'error:No account found with that email address. Please check and try again.';
            $message_type = 'error';
        }
    } else {
        $message = 'error:Please enter a valid email address.';
        $message_type = 'error';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | IskolarEase - University of Caloocan City</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style/forgot_password.css">
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
                        <i class="fas fa-shield-alt"></i>
                        <span>Secure Reset</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-clock"></i>
                        <span>1-Hour Validity</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-envelope"></i>
                        <span>Email Delivery</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-key"></i>
                        <span>Easy Recovery</span>
                    </div>
                </div>

                <div class="testimonial">
                    <p>Reset your password securely and get back to managing your academic journey with IskolarEase.</p>
                </div>
            </div>
        </div>

        <div class="right-panel">
            <a href="index.php" class="home-button">
                <i class="fas fa-home"></i>
                <span>Home</span>
            </a>

            <div class="forgot-container">
                <img src="logo.png" alt="IskolarEase" class="mobile-logo">

                <div class="forgot-header">
                    <h2><i class="fas fa-lock"></i> Forgot Password?</h2>
                    <p>Enter your email to reset your password</p>
                </div>

                <?php if (!empty($message)): ?>
                    <?php
                    $parts = explode(':', $message, 2);
                    $display_message = $parts[1] ?? $message;
                    $display_type = $parts[0] ?? 'error';
                    ?>
                    <div class="<?php echo $display_type === 'success' ? 'success-alert' : 'error-alert'; ?>">
                        <i class="fas <?php echo $display_type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                        <span><?php echo htmlspecialchars($display_message); ?></span>
                    </div>
                <?php endif; ?>

                <div class="register-prompt" style="background: #f0f9ff; border-left-color: #f57c00;">
                    <i class="fas fa-info-circle" style="color: #f57c00;"></i>
                    <span style="color: #334155;">We'll send a 16-character verification code to your email. The code will expire in 1 hour.</span>
                </div>

                <form action="forgot_password.php" method="POST" class="forgot-form" id="forgotForm">
                    <div class="input-group">
                        <label for="email">
                            <i class="fas fa-envelope"></i> Email Address
                        </label>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            required
                            placeholder="Enter your email address"
                            value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                            autofocus
                        >
                    </div>

                    <button type="submit" name="submit" class="submit-button" id="submitBtn">
                        <i class="fas fa-paper-plane"></i> Send Reset Code
                    </button>
                </form>

                <div class="register-prompt">
                    <i class="fas fa-key"></i>
                    <span>Already have a reset code?</span>
                    <a href="reset_code.php" class="register-link">Enter Code</a>
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
        const forgotForm = document.getElementById('forgotForm');
        const submitBtn = document.getElementById('submitBtn');
        const emailInput = document.getElementById('email');

        if (forgotForm) {
            forgotForm.addEventListener('submit', function(e) {
                const email = emailInput.value.trim();
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (!emailPattern.test(email)) {
                    e.preventDefault();
                    showError(emailInput, 'Please enter a valid email address');
                    return false;
                }

                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner spinner"></i> Sending...';
            });
        }

        if (emailInput) {
            emailInput.addEventListener('blur', function() {
                const email = this.value.trim();
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (email && !emailPattern.test(email)) {
                    showError(this, 'Please enter a valid email address');
                } else {
                    removeError(this);
                }
            });

            emailInput.addEventListener('input', function() {
                removeError(this);
            });
        }

        function showError(input, message) {
            removeError(input);

            const inputGroup = input.closest('.input-group');
            input.classList.add('error');

            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message';
            errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;

            inputGroup.appendChild(errorDiv);
        }

        function removeError(input) {
            const inputGroup = input.closest('.input-group');
            input.classList.remove('error');

            const existingError = inputGroup.querySelector('.error-message');
            if (existingError) {
                existingError.remove();
            }
        }

        let submitted = false;
        if (forgotForm) {
            forgotForm.addEventListener('submit', function(e) {
                if (submitted) {
                    e.preventDefault();
                    return false;
                }
                submitted = true;
            });
        }

        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>
</html>
