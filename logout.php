<?php
include 'config.php'; // Include your database configuration file
session_start();

// Destroy the current session
destroySession();

// Redirect to the homepage or login page
header("Location: ./login.php");
exit();

function destroySession() {
    // Unset all session variables
    $_SESSION = [];

    // Destroy the session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Finally, destroy the session
    session_destroy();
}
?>