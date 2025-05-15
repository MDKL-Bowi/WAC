<?php
// Start or resume the session
session_start();

// Check if the user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Redirect to login page if the user is not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

// Redirect to home page if the user is already logged in
function requireGuest() {
    if (isLoggedIn()) {
        header("Location: index.php");
        exit();
    }
}
?>