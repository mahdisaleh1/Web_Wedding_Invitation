<?php
// Handle form submission
include 'config.php'; // Include configuration file if needed
session_start();

if (!isset($_GET['guest'])) {
    echo "Guest ID missing.";
    exit;
}
$guestId = intval($_GET['guest']);
// Fetch guest details from the database
$stmt = $con->prepare("SELECT * FROM guests WHERE guest_id = ?");
$stmt->bind_param("i", $guestId);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo "Guest not found.";
    exit;
}
$guest = $result->fetch_assoc();
// Initialize variables
$name = $guest['full_name'];
$guests_number = $guest['number_invited'];
$guest_status = $guest['rsvp_status'];
if ($guest_status !== 'pending') {
    echo "<script>alert('You have already responded to the invitation.'); window.location.href='./confirmations.php?guest=$guestId';</script>";
    exit;
}
/*
$confirmationMsg = '';
$name = '';
$attendance = '';
$guests = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['guest_name']);
    $attendance = $_POST['attendance'];
    $guests = isset($_POST['guests']) ? intval($_POST['guests']) : 0;

    if ($attendance === 'yes') {
        $confirmationMsg = "Thank you, $name! We look forward to seeing you and your $guests guest(s)!";
    } else {
        $confirmationMsg = "Thank you for your response, $name. We'll miss you at our wedding.";
    }
}*/
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
                <a class="navbar-brand" href="./index.php?guest=<?php echo $guestId;?>">
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
                        <a class="nav-link" href="./index.php?guest=<?php echo $guestId;?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#invitation">Confirmation</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#footer">Contact Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="login-btn-mobile" href="./login.php?guest=<?php echo $guestId;?>">LOGIN</a>
                    </li>
                </ul>
                <a class="btn-login" href="./login.php?guest=<?php echo $guestId;?>">LOGIN</a>
            </div>
        </nav>
    </section>

    <section id="invitation">
        <div class="card">
            <h1>YOUR PRESENCE IS JOYFULLY REQUESTED AT THE WEDDING OF</h1>
            <div class="names">Ali Abdallah <br> & <br> Jana Saleh</div>
            <div class="details">
                <div><strong>Date:</strong> 29 August 2025</div>
                <div><strong>Time:</strong> 8:00 PM</div>
                <div><strong>Location:</strong> Eau De Vie - Phoenicia Hotel - Beirut </div>
            </div>
            
            <form method="" autocomplete="off" action="">
                <input type="hidden" name="guest_id" value="<?= htmlspecialchars($guestId) ?>">
                <label>Full Name:</label>
                <input type="text" name="guest_name" placeholder="Your Name" readonly value=" <?= htmlspecialchars($name) ?>">
                <label>Number of Guests:</label>
                <input type="number" name="guests" min="0" max="10" placeholder="Number of Guests (excluding you)" value="<?= htmlspecialchars($guests_number) ?>" readonly>

                <!--<button type="submit">Confirm Invitation</button>-->
                <a href="./confirmations.php?guest=<?php echo $guestId;?>" class="confirmation_btn">Confirm Invitation?</a>
                <div class="confirmation-note">
                    <span class="span1">Kindly RSVP before</span>
                    <span class="span2">08 August 2025  </span>
                </div>
            </form>
        </div>
    </section>
    <!-- GOOGLE MAP -->
    <section id="google-map">
        <h2>Venue Location</h2>
        <div class="map-container">
            <!--<iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d26653.371930766334!2d35.46732295317009!3d33.379641498696145!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x151e94a5d7a1ac15%3A0x23629441e0514c4c!2sNabatieh!5e0!3m2!1sen!2slb!4v1741612492083!5m2!1sen!2slb"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>-->
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3311.569541309588!2d35.49240507506903!3d33.90073842571607!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x151f16dc7dbed627%3A0x4c436908c262b02!2sInterContinental%20Phoenicia%20Beirut%20by%20IHG!5e0!3m2!1sen!2slb!4v1751981708731!5m2!1sen!2slb" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        <div>
            <a href="https://maps.app.goo.gl/hToA9mibpdrsUvYaA" target="_blank" class="map-link">
                Open in Google Maps
            </a>
        </div>
    </section>
    <footer style="background: #f8f5f2;" id="footer">
        <div class="container">

            <div class="footer-info">
                <div class="footer-section">
                    <h4>Contact</h4>
                    <p>Email: <a href="mailto:n/a">N/A</a></p>
                    <p>Phone Jana: <a href="tel:+96181958683">+961 81 958 683</a></p>
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
</body>
<style>
    .confirmation-note {
        flex-direction: row;
        justify-content: center;
        align-items: center;
        display: flex;
        gap: 10px;
    }
    .confirmation-note .span2 {
        direction: ltr;
        font-weight: bold;
    }
</style>
</html>