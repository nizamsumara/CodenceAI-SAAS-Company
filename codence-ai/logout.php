<?php

session_start();

// Clear session
session_unset();
session_destroy();

// Delete remember-me cookie
if (isset($_COOKIE["remember_email"])) {

    setcookie(
        "remember_email",
        "",
        time() - 3600,
        "/"
    );
}

// Go to homepage
header("Location: index.php");
exit();

?>