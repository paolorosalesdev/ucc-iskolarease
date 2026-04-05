<?php
session_start();
require './config/connect.php';

$message = '';
$message_type = '';
$email = $_SESSION['reset_email'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resetCode = isset($_POST['reset_code']) ? trim($_POST['reset_code']) : '';

    if (!empty($resetCode)) {
        $stmt = $conn->prepare("SELECT member_id, email, expires_at FROM password_resets WHERE reset_code = ?");
        $stmt->bind_param("s", $resetCode);
        $stmt->execute();
        $resetResult = $stmt->get_result();

        if ($resetResult->num_rows > 0) {
            $resetRecord = $resetResult->fetch_assoc();
            $currentTime = new DateTime();
            $expiresAt = new DateTime($resetRecord['expires_at']);

            if ($expiresAt > $currentTime) {
                header("Location: reset_password.php?member_id=" . $resetRecord['member_id'] . "&reset_code=" . urlencode($resetCode));
                exit();
            } else {
                $message = 'error:Reset code has expired. Please request a new one.';
                $message_type = 'error';
            }
        } else {
            $message = 'error:Invalid reset code.';
            $message_type = 'error';
        }
    } else {
        $message = 'error:Please enter the reset code.';
        $message_type = 'error';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Reset Code | IskolarEase - University of Caloocan City</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style/reset_code.css">
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
                        <span>Secure Verification</span>
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
                    <p>Enter the verification code sent to your email to reset your password securely.</p>
                </div>
            </div>
        </div>

        <div class="right-panel">
            <a href="index.php" class="home-button">
                <i class="fas fa-home"></i>
                <span>Home</span>
            </a>

            <div class="reset-code-container">
                <img src="logo.png" alt="IskolarEase" class="mobile-logo">

                <div class="reset-code-header">
                    <h2><i class="fas fa-shield-alt"></i> Verify Code</h2>
                    <p>Enter the reset code sent to your email</p>
                </div>

                <?php if (!empty($email)): ?>
                <div class="info-message">
                    <i class="fas fa-envelope"></i>
                    <span>Code sent to: <strong><?php echo htmlspecialchars($email); ?></strong></span>
                </div>
                <?php endif; ?>

                <?php if (!empty($message)): ?>
                    <?php
                    $display_message = str_replace('error:', '', $message);
                    ?>
                    <div class="error-alert">
                        <i class="fas fa-exclamation-circle"></i>
                        <span><?php echo htmlspecialchars($display_message); ?></span>
                    </div>
                <?php endif; ?>

                <div class="register-prompt" style="background: #f0f9ff; border-left-color: #f57c00; margin-bottom: 1.5rem;">
                    <i class="fas fa-info-circle" style="color: #f57c00;"></i>
                    <span style="color: #334155;">Please check your inbox and spam folder for the 16-character verification code.</span>
                </div>

                <form action="reset_code.php" method="POST" class="reset-code-form" id="resetCodeForm">
                    <div class="input-group">
                        <label for="reset_code">
                            <i class="fas fa-key"></i> Verification Code
                        </label>
                        <input
                            type="text"
                            name="reset_code"
                            id="reset_code"
                            required
                            placeholder="Enter 16-character code"
                            maxlength="16"
                            pattern="[A-Za-z0-9]{16}"
                            title="Please enter the 16-character verification code"
                            autofocus
                        >
                        <small style="display: block; margin-top: 0.5rem; color: #64748b;">
                            <i class="fas fa-info-circle"></i> Enter the 16-character code from your email
                        </small>
                    </div>

                    <button type="submit" name="submit" class="submit-button" id="submitBtn">
                        <i class="fas fa-check-circle"></i> Verify Code
                    </button>
                </form>

                <div class="register-prompt" style="margin-bottom: 1rem;">
                    <i class="fas fa-clock"></i>
                    <span>Didn't receive the code?</span>
                    <a href="forgot_password.php" class="register-link">Resend Code</a>
                </div>

                <div class="register-prompt">
                    <i class="fas fa-arrow-left"></i>
                    <span>Remember your password?</span>
                    <a href="login.php" class="register-link">Sign In</a>
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
        const resetCodeForm = document.getElementById('resetCodeForm');
        const submitBtn = document.getElementById('submitBtn');
        const codeInput = document.getElementById('reset_code');

        if (resetCodeForm) {
            resetCodeForm.addEventListener('submit', function(e) {
                const code = codeInput.value.trim();
                const codePattern = /^[A-Za-z0-9]{16}$/;

                if (!codePattern.test(code)) {
                    e.preventDefault();
                    showError(codeInput, 'Please enter a valid 16-character code');
                    return false;
                }

                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner spinner"></i> Verifying...';
            });
        }

        if (codeInput) {
            codeInput.addEventListener('input', function(e) {
                let value = this.value.replace(/[^A-Za-z0-9]/g, '').toUpperCase();
                if (value.length > 16) {
                    value = value.slice(0, 16);
                }
                this.value = value;
                removeError(this);
            });

            codeInput.addEventListener('blur', function() {
                const code = this.value.trim();
                const codePattern = /^[A-Za-z0-9]{16}$/;

                if (code && !codePattern.test(code)) {
                    showError(this, 'Code must be exactly 16 characters');
                } else {
                    removeError(this);
                }
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
        if (resetCodeForm) {
            resetCodeForm.addEventListener('submit', function(e) {
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

        if (codeInput && !codeInput.value) {
            codeInput.focus();
        }
    </script>
</body>
</html>
