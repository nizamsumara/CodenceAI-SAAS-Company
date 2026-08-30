<?php
// ============================================================
// admin/logout.php
// Admin logout
// ============================================================

session_start();

// Destroy session
$_SESSION = [];
session_destroy();

// Clear Remember Me cookie if present
if (isset($_COOKIE['remember_email'])) {
    setcookie('remember_email', '', time() - 3600, '/');
}

// Redirect to public homepage
header('Location: ../index.php');
exit;
?>