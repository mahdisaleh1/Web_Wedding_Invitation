<?php
// Handle form submission
include './config.php'; // Include configuration file if needed
session_start();
$guestId = isset($_GET['guest']) ? intval($_GET['guest']) : 0;

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $email = $_POST['emailadd'];
    $password = $_POST['password'];
    // Validate email and password
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Email and password are required.";
    } else {
        // Check credentials in the database
        $stmt = $con->prepare("SELECT * FROM users WHERE emailaddress = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Login successful
            $user = $result->fetch_assoc();
            if ($password === $user['password']) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_fullname'] = $user['fullname'];
                header("Location: ./admins/admin_dashboard.php");
                exit();
            } else {
                echo "<script>alert('Invalid password.');</script>";
                $_SESSION['error'] = "Invalid password.";
            }
        } else {
            echo "<script>alert('Invalid email address.');</script>";
            $_SESSION['error'] = "Invalid email address.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Wedding Invitation - Ali & Jana</title>
    <link rel="shortcut icon" href="./images/icon.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
    <meta name="description" content="Join us for the wedding celebration of Ali & Jana on 20 July 2024.">
    <meta name="keywords" content="wedding, invitation, Ali, Jana, Beirut">
    <meta name="author" content="Mahdi Saleh">
</head>

<body>
    <section id="navbar">
        <nav class="navbar">
            <div class="container">
                <a class="navbar-brand" href="./index.php?guest=<?php echo $guestId; ?>">
                    <img src="./images/ringwedding.jpg" alt="Wedding Invitation Logo">
                    Wedding Invitation
                </a>
                <button class="navbar-toggler" type="button" aria-label="Toggle navigation" onclick="toggleNavbar()">
                    <span class="hamburger"></span>
                    <span class="hamburger"></span>
                    <span class="hamburger"></span>
                    <span class="close-x">&times;</span>
                </button>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="./index.php?guest=<?php echo $guestId; ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./index.php?guest=<?php echo $guestId; ?>#invitation">Confirmation</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./index.php?guest=<?php echo $guestId; ?>#footer">Contact Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="login-btn-mobile" href="./login.php?guest=<?php echo $guestId;?>">LOGIN</a>
                    </li>
                </ul>
                <a class="btn-login" href="./login.php?guest=<?php echo $guestId;?>">LOGIN</a>
            </div>
        </nav>
    </section>

    <section id="login_card">
        <div class="container">
            <h1>Login Here</h1>
            <form action="login.php?guest=<?php echo $guestId; ?>" method="post">
                <div class="form-group">
                    <label for="emailadd">Email Address:</label>
                    <input type="email" id="username" name="emailadd" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <div class="form-group links">
                    <a href="./index.php?guest=<?php echo $guestId; ?>" class="cancel-link">Cancel</a>
                    <button type="submit" class="btn-submit">Login</button>
                </div>
                <div class="form-group">
                    <p class="error-message">
                        <?php
                        if (isset($_SESSION['error'])) {
                            echo $_SESSION['error'];
                            unset($_SESSION['error']);
                        }
                        ?>
                    </p>
                </div>
            </form>
        </div>
    </section>

    <footer style="background: #f8f5f2;" id="footer">
        <div class="container">

            <div class="footer-info">
                <div class="footer-section">
                    <h4>Contact</h4>
                    <p>Email: <a href="mailto:ali.jana.wedding@gmail.com">ali.jana.wedding@gmail.com</a></p>
                    <p>Phone Ali: <a href="tel:+96112345678">+961 1 234 5678</a></p>
                    <p>Phone Jana: <a href="tel:+96112345678">+961 1 234 5678</a></p>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="#navbar">Home</a></li>
                        <li><a href="confirmation.php">Confirmation</a></li>
                        <li><a href="contact_us.php">Contact Us</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Follow Us</h4>

                    <a href="#" class="social-icon-link bi-facebook"></a><br>
                    <a href="#" class="social-icon-link bi-instagram"></a><br>
                    <a href="#" class="social-icon-link bi-twitter"></a>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Ali & Jana. All rights reserved.</p>
                <p>Website by <a href="http://mahdisaleh.ct.ws" target="_blank">Mahdi Saleh </a> </p>
            </div>
        </div>
    </footer>
    <script src="js/script.js"></script>
    
    <style>
        #login_card {
            background: #fff8f0;
            min-height: 60vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background:
                linear-gradient(120deg, rgba(123, 94, 234, 0.08) 0%, rgba(255, 255, 255, 0.95) 100%),
                url(./images/weddingbar2_copy.jpg) no-repeat center center / cover;
        }

        #login_card .container {
            max-width: 100%;
            width: 50%;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
            padding: 32px 24px;
            margin: 40px auto;
            background:
                linear-gradient(320deg, rgba(123, 94, 234, 0.08) 0%, rgba(255, 255, 255, 0.95) 100%),
                url(./images/weddingbar2_copy.jpg) no-repeat center center / cover;
        }

        #login_card h1 {
            font-family: 'Montserrat', sans-serif;
            font-size: 2.2rem;
            color: #333;
            text-align: center;
            margin-bottom: 24px;
        }

        #login_card .form-group {
            margin-bottom: 18px;
            width: 100%;
        }

        #login_card label {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            color: #333;
        }

        #login_card input[type="email"],
        #login_card input[type="password"] {
            width: 100%;
            max-width: 100%;
            padding: 10px 12px;
            border: 1px solid #e5d5c6;
            border-radius: 8px;
            margin-top: 6px;
            font-size: 1.25rem;
            font-family: 'Montserrat', sans-serif;
        }

        #login_card .form-group.links {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
        }

        #login_card .cancel-link {
            width: 40%;
            padding: 8px 24px;
            color: #333;
            border: none;
            font-weight: bold;
            border-radius: 8px;
            font-size: 1rem;
            font-family: 'Montserrat', sans-serif;
            cursor: pointer;
            transition: background 0.25s, transform 0.18s, box-shadow 0.18s;
            letter-spacing: 0.5px;
            background-color: #f8f5f2;
            text-decoration: none;
        }
        #login_card .cancel-link:hover {
            background-color: #333;
            color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }
        #login_card .btn-submit {
            width: 50%;
            padding: 12px 24px;
            color: #fff;
            border: none;
            font-weight: bold;
            border-radius: 8px;
            font-size: 1rem;
            font-family: 'Montserrat', sans-serif;
            cursor: pointer;
            transition: background 0.25s, transform 0.18s, box-shadow 0.18s;
            letter-spacing: 0.5px;
            background-color: #333;
        }
        #login_card .btn-submit:hover {
            background-color: #fff;
            color: #333;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        #login_card .error-message {
            color: #d9534f;
            font-size: 0.98rem;
            text-align: center;
            min-height: 22px;
        }

        @media (max-width: 768px) {
            #login_card .container {
                width: 100%;
            }
        }
    </style>

</body>

</html>