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
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Amiri&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Gulzar&display=swap" rel="stylesheet">
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
                        <a class="nav-link" href="./index.php?guest=<?php echo $guestId; ?>">الصفحة الرئيسية</a>
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
            <div class="header">
                <h2 class="quran-text"> بِسْمِ اللَّـهِ الرَّحْمَـٰنِ الرَّحِيمِ</h2>
                <p class="quran-text">
                    وَمِنْ آيَاتِهِ أَنْ خَلَقَ لَكُم مِّنْ أَنفُسِكُمْ أَزْوَاجًا لِّتَسْكُنُوا إِلَيْهَا وَجَعَلَ بَيْنَكُم مَّوَدَّةً وَرَحْمَةً
                </p>
                <p class="quran-text-end">صَدَقَ اللهُ العَلِيُّ العَظيمْ</p>
            </div>
            <div class="fathers_names">
                <p class="fathers_names_center">تتشرف</p>
                <p class="fathers_names_center"> عائلة </p>
                <p class="fathers_names_right">المرحوم الحاج حسين عبداللّه </p>
                <p class="fathers_names_center">وعائلة</p>
                <p class="fathers_names_left">السيد عباس صالح</p>
            </div>
            <div class="kids_names">
                <p class="kids_names_center">بدعوتكم لحضور حفل زفاف ولديهما</p>
                <p class="kids_names_right">علي</p>
                <p class="kids_names_center">و</p>
                <p class="kids_names_left">جنى</p>
            </div>

            <div class="details">
                <div><strong>و ذلك نهار: </strong>29 اب 2025</div>
                <div><strong>تمام الساعة: </strong> 8:00 مساء</div>
                <div><strong>المكان:</strong> Eau De Vie - Phoenicia Hotel - Beirut </div>
            </div>
            <form method="" autocomplete="off" action="">
                <input type="hidden" name="guest_id" value="<?= htmlspecialchars($guestId) ?>">
                <label>الإسم الكريم:</label>
                <input type="text" name="guest_name" placeholder="Your Name" readonly value=" <?= htmlspecialchars($name) ?>">
                <label>عدد الأشخاص:</label>
                <input type="number" name="guests" min="0" max="10" placeholder="Number of Guests (excluding you)" value="<?= htmlspecialchars($guests_number) ?>" readonly>

                <!--<button type="submit">Confirm Invitation</button>-->
                <a href="./confirmations_ar.php?guest=<?php echo $guestId; ?>" class="confirmation_btn">تأكيد الحضور أو الاعتذار؟</a>
                <div class="confirmation-note">
                    <span class="span1">الرجاء تأكيد الحضور او الاعتذار قبل   </span>
                    <span class="span2">08 August 2025  </span>
                </div>

            </form>
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
    <script src="./js/script.js"></script>
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

    .card h2 {
        font-family: 'Amiri', serif;
        font-size: 1.5em;
        text-align: center;
        margin: 20px 0;
        color: #333;
    }

    /*.card p {
        font-family: 'Amiri', serif;
        font-size: 1.2em;
        text-align: center;
        margin: 10px 0;
        color: #333;
    }*/

    .quran-text {
        font-family: 'Amiri', serif;
        font-size: 1.2em;
        text-align: center;
        margin: 10px 0;
        color: #333;
    }

    .header {
        margin-bottom: 20px;
    }

    .quran-text-end {
        font-family: 'Amiri', serif;
        font-size: 1.5rem;
        text-align: center;
        color: #2d2d2d;
        margin: 6px 0;
    }

    .fathers_names {
        margin: 18px 0 10px 0;
    }

    .fathers_names_center {
        text-align: center;
        direction: rtl;
       font-family: 'Gulzar', sans-serif;
        font-size: 3rem;
        margin: 2px 0;
    }

    .fathers_names_right {
        font-weight: bold;
        text-align: right;
        direction: rtl;
        font-family: 'Amiri', serif;
        font-size: 2rem;
        margin: 2px 80px 2px 20px;
        
    }

    .fathers_names_left {
        font-weight: bold;
        text-align: left;
        direction: rtl;
        font-family: 'Amiri', serif;
        font-size: 2rem;
        margin: 2px 20px 2px 100px;
    }

    .kids_names {
        margin: 18px 0 10px 0;
    }

    .kids_names_center {
        text-align: center;
        direction: rtl;
        font-family: 'Amiri', serif;
        font-size: 2.8rem;
        margin: 0px 0;
    }

    .kids_names_right {
        text-align: right;
        direction: rtl;
        /*font-family: 'Amiri', serif;*/
        font-family: 'Gulzar', sans-serif;
        font-size: 4rem;
        font-weight: 500;
        margin: 2px 80px 2px 20px;
    }

    .kids_names_left {
        text-align: left;
        direction: rtl;
        /*font-family: 'Amiri', serif;*/
        font-family: 'Gulzar', serif;
        font-size: 4rem;
        font-weight: 500;
        margin: -20px 20px 2px 80px;
    }

    @media (max-width: 768px) {
        footer {
            text-align: center;
        }

        .fathers_names_left {
            margin: 2px 20px 2px 20px;
            font-size: 1.6rem;
        }

        .fathers_names_right {
            margin: 2px 20px 2px 20px;
            font-size: 1.6rem;
        }
        .kids_names_left {
            margin: -20px 20px 2px 100px;
            font-size: 3rem;
        }
        .kids_names_right {
            margin: 2px 100px 2px 20px;
            font-size: 3rem;
        }
    }
</style>

</html>