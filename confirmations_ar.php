<?php
// Handle form submission
include 'config.php';
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
$name = $guest['full_name'];
$guests_number = $guest['number_invited'];
$guest_status = $guest['rsvp_status'];

$confirmationMsg = '';
$attendance = '';
$guests = 1;

// If already responded, show the status message
if ($guest_status === 'accepted') {
    $confirmationMsg = "شكرا لك, $name! لقد اتتمت القبول الى الحفل مسبقا. نتطلع لرؤيتك.";
} elseif ($guest_status === 'declined') {
    $confirmationMsg = "شكرا على اجابتك, $name. لقد اعتذرت عن الحضور مسبقا. سوف نفتقدك في الحفل. اذا غيرت رايك و قررت الحضور, الرجاء تواصل معنا..";
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $attendance = $_POST['attendance'];
    $message = isset($_POST['message']) ? htmlspecialchars($_POST['message']) : '';
    if ($attendance === 'yes') {
        $guests = isset($_POST['guests']) ? intval($_POST['guests']) : 1;
        if ($guests < 1) $guests = 1;
        if ($guests > $guests_number) $guests = $guests_number;
        // Save confirmation to DB
        $stmt = $con->prepare("UPDATE guests SET rsvp_status = 'accepted', rsvp_response_time = NOW() WHERE guest_id = ?");
        $stmt->bind_param("i", $guestId);
        $stmt->execute();

        // Insert into rsvp_responses
        $stmt2 = $con->prepare("INSERT INTO rsvp_responses (guest_id, full_name, attending_count, message) VALUES (?, ?, ?, ?)");
        $stmt2->bind_param("isis", $guestId, $name, $guests, $message);
        $stmt2->execute();

        $confirmationMsg = "شكرا لك, $name! نتطلع لرؤيتك و ضيوفك في حفل الزفاف قريبا!";
    } else {
        // Save decline to DB
        $stmt = $con->prepare("UPDATE guests SET rsvp_status = 'declined', rsvp_response_time = NOW() WHERE guest_id = ?");
        $stmt->bind_param("i", $guestId);
        $stmt->execute();

        $confirmationMsg = "شكرا لك على اجابتك, $name. سوف نفتقدك في الحفل.";
    }
    // Refresh guest status after update
    $stmt = $con->prepare("SELECT rsvp_status FROM guests WHERE guest_id = ?");
    $stmt->bind_param("i", $guestId);
    $stmt->execute();
    $result = $stmt->get_result();
    $guest_status = $result->fetch_assoc()['rsvp_status'];
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Amiri&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
    <meta name="description" content="Join us for the wedding celebration of Ali & Jana on 20 July 2024.">
    <meta name="keywords" content="wedding, invitation, Ali, Jana, Beirut">
    <meta name="author" content="Mahdi Saleh">
    <title>Wedding Invitation ~ Arabic - Ali & Jana </title>
    <link rel="shortcut icon" href="./images/icon.png" type="image/x-icon">
</head>

<body>
    <section id="navbar">
        <nav class="navbar">
            <div class="container">
                <a class="navbar-brand" href="./index.php?guest=<?php echo $guestId; ?>">
                    <img src="./images/ringwedding.jpg" alt="Wedding Invitation Logo">
                    دعوة زفاف
                </a>
                <button class="navbar-toggler" type="button" aria-label="Toggle navigation" onclick="toggleNavbar()">
                    <span class="hamburger"></span>
                    <span class="hamburger"></span>
                    <span class="hamburger"></span>
                    <span class="close-x">&times;</span>
                </button>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="./index_ar.php?guest=<?php echo $guestId; ?>">الصفحة الرئيسية</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#invitation">التأكيد</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#footer">تواصل معنا</a>
                    </li>
                    <li class="nav-item">
                        <a class="login-btn-mobile" href="./login.php?guest=<?php echo $guestId; ?>">LOGIN</a>
                    </li>
                </ul>
                <a class="btn-login" href="./login.php?guest=<?php echo $guestId; ?>">LOGIN</a>
            </div>
        </nav>
    </section>

    <section id="invitation">
        <div class="card">
            <h2>يشرّفنا حضوركم في حفل زفاف علي و جنى</h2>

            <?php if ($confirmationMsg): ?>
                <div class="confirmation-message" style="margin:20px 0; color:green; font-weight:bold;">
                    <?= htmlspecialchars($confirmationMsg) ?>
                    <div class="omt-pay">
                        <span>OMT PAY Number:</span> 123456789
                    </div>
                </div>
            <?php elseif ($guest_status === 'pending'): ?>
                <form method="post" autocomplete="off" action="">
                    <input type="hidden" name="guest_id" value="<?= htmlspecialchars($guestId) ?>">
                    <label>الاسم الكريم:</label>
                    <input type="text" name="guest_name" readonly value="<?= htmlspecialchars($name) ?>">
                    <label>هل ستحضر الحفل؟</label>
                    <div style="margin-bottom:10px;">
                        <label>
                            <input type="radio" name="attendance" value="yes" required onclick="document.getElementById('guests_field').style.display='block'"> قبول
                        </label>
                        <label>
                            <input type="radio" name="attendance" value="no" required onclick="document.getElementById('guests_field').style.display='none'"> اعتذار
                        </label>
                    </div>
                    <div id="guests_field" style="display:none;">
                        <label>عدد الحضور (العدد يشملك):</label>
                        <input type="number" name="guests" min="1" max="<?= htmlspecialchars($guests_number) ?>" value="1">
                        <div style="font-size:14px;color:#333;margin-bottom:20px;">يمكنك احضار <?= htmlspecialchars($guests_number) ?> شخص (اشخاص) و انت منهم </div>
                        <label>اكتب رسالتك العروسين (اختياري):</label>
                        <textarea name="message" rows="4" placeholder="أترك رسالة لنا هنا..."></textarea>
                    </div>
                    <button type="submit" class="confirmation_btn">حفظ</button>
                </form>
                <script>
                    // Show guests field if "Accept" is already selected (for browser back/refresh)
                    document.addEventListener('DOMContentLoaded', function() {
                        var yesRadio = document.querySelector('input[name="attendance"][value="yes"]');
                        if (yesRadio && yesRadio.checked) {
                            document.getElementById('guests_field').style.display = 'block';
                        }
                    });
                </script>
            <?php endif; ?>
        </div>
    </section>
    <!-- GOOGLE MAP -->
    <section id="google-map">
        <h2>موقع الحضور</h2>
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
                افتح في خرائط جوجل
            </a>
        </div>
    </section>
    <footer id="footer">
        <div class="container">
            <div class="footer-info">
                <div class="footer-section">
                    <h4>Contact</h4>
                    <p>Email: <a href="mailto:n/a">N/A</a></p>
                    <p> <a href="tel:+96181958683">+961 81 958 683 :</a>رقم الهاتف(جنى)</p>
                    <p> <a href="tel:+96112345678">+961 1 234 567 :</a>رقم الهاتف(علي)</p>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="#navbar">الصفحة الرئيسية</a></li>
                        <li><a href="confirmation.php">التاكيد</a></li>
                        <li><a href="contact_us.php">تواصل معنا</a></li>
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
        .navbar-brand {
            font-size: 2rem;
            font-family: 'Amiri', serif;
        }

        input[type="text"],
        input[type="number"] {
            text-align: left;
            direction: ltr;
        }

        footer {
            background: #f8f5f2;
            padding: 32px 0;
            text-align: left;
            direction: ltr;
        }

        input[type="radio"] {
            accent-color: #b48a78;
            width: 18px;
            height: 18px;
            vertical-align: middle;
            margin-right: 6px;
            cursor: pointer;
        }

        label input[type="radio"]+span,
        label input[type="radio"] {
            font-family: 'Montserrat', Arial, sans-serif;
            font-size: 18px;
            color: #333;
        }

        label[for] {
            cursor: pointer;
        }

        div[style*="margin-bottom:10px;"] label {
            margin-right: 18px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        @media (max-width: 600px) {
            div[style*="margin-bottom:10px;"] label {
                font-size: 15px;
            }

            input[type="radio"] {
                width: 16px;
                height: 16px;
            }

            footer {
                text-align: center;
            }
        }
    </style>
</body>

</html>