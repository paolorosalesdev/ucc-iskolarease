<?php
include('./config/connect.php');

$showSuccess = false;

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $role = $_POST['role'];

    $select = "SELECT * FROM user_info WHERE email = '$email'";
    $result = mysqli_query($conn, $select);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($result) > 0) {
        $error = 'User already exists!';
    } else {
        if (strlen($password) < 8) {
            $error = 'Password must be at least 8 characters long!';
        } elseif (!preg_match('/[A-Z]/', $password)) {
            $error = 'Password must contain at least one uppercase letter!';
        } elseif (!preg_match('/[a-z]/', $password)) {
            $error = 'Password must contain at least one lowercase letter!';
        } elseif ($password != $cpassword) {
            $error = 'Passwords do not match!';
        } else {
            $pass = md5($password);

            if ($role == 'provider') {
                $insert = "INSERT INTO user_info (name, email, contact, password, role, status, provider_status)
                           VALUES ('$name', '$email', '$contact', '$pass', '$role', 'enabled', 'unverified')";
            } else {
                $insert = "INSERT INTO user_info (name, email, contact, password, role, status)
                           VALUES ('$name', '$email', '$contact', '$pass', '$role', 'enabled')";
            }

            if (mysqli_query($conn, $insert)) {
                $showSuccess = true;
            } else {
                $error = "Insertion failed: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | IskolarEase - University of Caloocan City</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="style/register.css">
    <style>
        .verification-notice {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            border-radius: 10px;
            margin: 20px 0;
            display: none;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .verification-notice i {
            margin-right: 10px;
            font-size: 1.2em;
        }

        .verification-notice h4 {
            margin: 0 0 5px 0;
            font-size: 1.1em;
        }

        .verification-notice p {
            margin: 0;
            font-size: 0.9em;
            opacity: 0.9;
        }
    </style>
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
                    <p>Join IskolarEase today! Discover scholarship opportunities!</p>
                </div>
            </div>
        </div>

        <div class="right-panel">
            <a href="index.php" class="home-button">
                <i class="fas fa-home"></i>
                <span>Home</span>
            </a>

            <div class="register-container">
                <img src="logo.png" alt="IskolarEase" class="mobile-logo">
                <div class="register-header">
                    <h2>Create Account</h2>
                    <p>Join IskolarEase today</p>
                </div>

                <div class="verification-notice" id="verification-notice">
                    <i class="fas fa-shield-alt"></i>
                    <div>
                        <h4>Provider Account Verification Required</h4>
                        <p>As a Scholaship provider, your account will need to be verified by our administrators before you can post scholarships. You'll receive a notification once verified.</p>
                    </div>
                </div>

                <?php if (isset($error)): ?>
                    <div class="error-alert">
                        <i class="fas fa-exclamation-circle"></i>
                        <span><?php echo $error; ?></span>
                    </div>
                <?php endif; ?>

                <form action="" method="post" onsubmit="return validateForm()" class="register-form">
                    <div class="form-row">
                        <div class="input-group">
                            <label for="name">
                                <i class="fas fa-user"></i> Full Name
                            </label>
                            <div class="input-wrapper">
                                <input type="text" name="name" id="name" placeholder="John Doe" required oninput="validateName()">
                            </div>
                            <div class="validation-message" id="name-message">
                                <i class="fas fa-info-circle"></i> Letters and spaces only
                            </div>
                        </div>

                        <div class="input-group">
                            <label for="contact">
                                <i class="fas fa-phone"></i> Contact Number
                            </label>
                            <div class="input-wrapper">
                                <input type="tel" name="contact" id="contact" placeholder="09123456789" required maxlength="11" oninput="validateContact()">
                            </div>
                            <div class="validation-message" id="contact-message">
                                <i class="fas fa-info-circle"></i> 11 digits starting with 09
                            </div>
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="email">
                            <i class="fas fa-envelope"></i> Email Address
                        </label>
                        <div class="input-wrapper">
                            <input type="email" name="email" id="email" placeholder="name@example.com" required oninput="validateEmail()">
                        </div>
                        <div class="validation-message" id="email-message">
                            <i class="fas fa-info-circle"></i> Valid email address
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="input-group">
                            <label for="password">
                                <i class="fas fa-lock"></i> Password
                            </label>
                            <div class="password-field">
                                <div class="input-wrapper">
                                    <input type="password" name="password" id="password" placeholder="Create a password" required oninput="validatePassword()">
                                    <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="validation-message" id="password-message">
                                <i class="fas fa-info-circle"></i> Min 8 chars, 1 uppercase, 1 lowercase
                            </div>
                        </div>

                        <div class="input-group">
                            <label for="cpassword">
                                <i class="fas fa-lock"></i> Confirm Password
                            </label>
                            <div class="password-field">
                                <div class="input-wrapper">
                                    <input type="password" name="cpassword" id="cpassword" placeholder="Confirm password" required oninput="validateConfirmPassword()">
                                    <button type="button" class="password-toggle" onclick="togglePassword('cpassword')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="validation-message" id="cpassword-message">
                                <i class="fas fa-info-circle"></i> Must match password
                            </div>
                        </div>
                    </div>

                    <div class="role-selection">
                        <label>
                            <i class="fas fa-user-tag"></i> I want to register as
                        </label>
                        <div class="role-cards">
                            <label class="role-card">
                                <input type="radio" name="role" value="student" required onchange="validateRole(); toggleVerificationNotice();">
                                <div class="card-content">
                                    <i class="fas fa-graduation-cap"></i>
                                    <span>Student</span>
                                    <small>Access scholarship opportunities</small>
                                </div>
                            </label>
                            <label class="role-card">
                                <input type="radio" name="role" value="provider" required onchange="validateRole(); toggleVerificationNotice();">
                                <div class="card-content">
                                    <i class="fas fa-briefcase"></i>
                                    <span>Provider</span>
                                    <small>Offer scholarships to students</small>
                                </div>
                            </label>
                        </div>
                        <div class="validation-message" id="role-message">
                            <i class="fas fa-info-circle"></i> Select your role
                        </div>
                    </div>

                    <div class="recaptcha-container">
                        <div class="g-recaptcha" data-sitekey="6LefY30sAAAAAD8ZEK_cosSktlXqJ_T_YNJJMKqR"></div>
                    </div>

                    <button type="submit" name="submit" class="register-button">
                        <i class="fas fa-user-plus"></i> Sign Up
                    </button>
                </form>

                <div class="login-prompt">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Already have an account?</span>
                    <a href="login.php" class="login-link">Login</a>
                </div>

                <div class="register-footer">
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

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>

    <?php if (isset($error)): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Error!',
                    text: '<?php echo $error; ?>',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        </script>
    <?php endif; ?>

    <?php if ($showSuccess): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let successMessage = 'Registration successful! Welcome to IskolarEase!';
                let role = '<?php echo $_POST['role'] ?? ''; ?>';

                if (role === 'provider') {
                    successMessage = 'Registration successful! Your provider account has been created and is pending verification. You will be able to post scholarships once your account is verified by an administrator.';
                }

                Swal.fire({
                    title: 'Success!',
                    text: successMessage,
                    icon: 'success',
                    confirmButtonText: role === 'provider' ? 'Got it' : 'Login Now'
                }).then((result) => {
                    if (result.isConfirmed && role !== 'provider') {
                        window.location.href = 'login.php';
                    } else if (result.isConfirmed && role === 'provider') {
                        window.location.href = 'login.php';
                    }
                });
            });
        </script>
    <?php endif; ?>

    <script>
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const toggleButton = passwordInput.nextElementSibling.querySelector('i');

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

        function toTitleCase(str) {
            return str.replace(/\w\S*/g, function(txt) {
                return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
            });
        }

        function toggleVerificationNotice() {
            const role = document.querySelector('input[name="role"]:checked');
            const notice = document.getElementById('verification-notice');

            if (role && role.value === 'provider') {
                notice.style.display = 'flex';
            } else {
                notice.style.display = 'none';
            }
        }

        function validateName() {
            const name = document.getElementById('name');
            const message = document.getElementById('name-message');
            const value = name.value;

            name.value = toTitleCase(value);

            const isValid = value.length >= 2 && /^[a-zA-Z\s]+$/.test(value);
            message.className = `validation-message ${isValid ? 'valid' : 'invalid'}`;
            message.innerHTML = `<i class="fas fa-info-circle"></i> ${isValid ? 'Valid name' : 'Letters and spaces only'}`;
            return isValid;
        }

        function validateContact() {
            const contact = document.getElementById('contact');
            const message = document.getElementById('contact-message');
            const value = contact.value.replace(/[^0-9]/g, '');

            contact.value = value;

            const isValid = value.length === 11 && value.startsWith('09');
            message.className = `validation-message ${isValid ? 'valid' : 'invalid'}`;
            message.innerHTML = `<i class="fas fa-info-circle"></i> ${isValid ? 'Valid number' : '11 digits starting with 09'}`;
            return isValid;
        }

        function validateEmail() {
            const email = document.getElementById('email');
            const message = document.getElementById('email-message');
            const value = email.value;

            const isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
            message.className = `validation-message ${isValid ? 'valid' : 'invalid'}`;
            message.innerHTML = `<i class="fas fa-info-circle"></i> ${isValid ? 'Valid email' : 'Valid email address'}`;
            return isValid;
        }

        function validatePassword() {
            const password = document.getElementById('password');
            const message = document.getElementById('password-message');
            const value = password.value;

            const hasMinLength = value.length >= 8;
            const hasUpperCase = /[A-Z]/.test(value);
            const hasLowerCase = /[a-z]/.test(value);
            const isValid = hasMinLength && hasUpperCase && hasLowerCase;

            message.className = `validation-message ${isValid ? 'valid' : 'invalid'}`;

            if (isValid) {
                message.innerHTML = `<i class="fas fa-info-circle"></i> Strong password`;
            } else {
                let requirements = [];
                if (!hasMinLength) requirements.push('min 8 chars');
                if (!hasUpperCase) requirements.push('1 uppercase');
                if (!hasLowerCase) requirements.push('1 lowercase');
                message.innerHTML = `<i class="fas fa-info-circle"></i> Need: ${requirements.join(', ')}`;
            }

            validateConfirmPassword();
            return isValid;
        }

        function validateConfirmPassword() {
            const password = document.getElementById('password').value;
            const cpassword = document.getElementById('cpassword');
            const message = document.getElementById('cpassword-message');
            const value = cpassword.value;

            const isValid = value === password && value.length > 0;
            message.className = `validation-message ${isValid ? 'valid' : 'invalid'}`;
            message.innerHTML = `<i class="fas fa-info-circle"></i> ${isValid ? 'Passwords match' : 'Must match password'}`;
            return isValid;
        }

        function validateRole() {
            const role = document.querySelector('input[name="role"]:checked');
            const message = document.getElementById('role-message');

            const isValid = role !== null;
            message.className = `validation-message ${isValid ? 'valid' : 'invalid'}`;
            message.innerHTML = `<i class="fas fa-info-circle"></i> ${isValid ? 'Role selected' : 'Select your role'}`;
            return isValid;
        }

        function validateForm() {
            const isNameValid = validateName();
            const isContactValid = validateContact();
            const isEmailValid = validateEmail();
            const isPasswordValid = validatePassword();
            const isConfirmValid = validateConfirmPassword();
            const isRoleValid = validateRole();

            if (!isNameValid) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Please enter a valid name (letters and spaces only)',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return false;
            }

            if (!isContactValid) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Please enter a valid 11-digit Philippine mobile number starting with 09',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return false;
            }

            if (!isEmailValid) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Please enter a valid email address',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return false;
            }

            if (!isPasswordValid) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Password must be at least 8 characters long and contain both uppercase and lowercase letters',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return false;
            }

            if (!isConfirmValid) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Passwords do not match!',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return false;
            }

            if (!isRoleValid) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Please select your role',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return false;
            }

            return true;
        }

        document.getElementById('name').addEventListener('input', validateName);
        document.getElementById('contact').addEventListener('input', validateContact);
        document.getElementById('email').addEventListener('input', validateEmail);
        document.getElementById('password').addEventListener('input', validatePassword);
        document.getElementById('cpassword').addEventListener('input', validateConfirmPassword);

        document.querySelectorAll('input[name="role"]').forEach(radio => {
            radio.addEventListener('change', validateRole);
            radio.addEventListener('change', toggleVerificationNotice);
        });

        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>
</html>
