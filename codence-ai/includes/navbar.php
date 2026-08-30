<?php

// Start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Database connection
if (!isset($conn)) {
    include 'includes/db.php';
}

// Check remember-me cookie
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_email'])) {

    $email = $_COOKIE['remember_email'];

    $sql = "SELECT id, full_name, role FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {

        $user = mysqli_fetch_assoc($result);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['user_role'] = $user['role'];
    }
}

// Find current page
$active_page = basename($_SERVER['SCRIPT_NAME']);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Codence AI | Intelligence with Precision</title>

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="css/variables.css">
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/layout.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/auth.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/responsive.css">

</head>

<body>

<header class="site-header">

    <nav class="nav-container">

        <!-- Logo -->
        <a href="index.php" class="logo-wrapper">

            <img src="images/logo.png"
                 alt="Codence AI"
                 class="site-logo">

        </a>


        <!-- Navigation -->
        <ul class="nav-menu">

            <li>
                <a href="index.php"
                   class="nav-link <?php echo ($active_page == 'index.php') ? 'active' : ''; ?>">
                    HOME
                </a>
            </li>

            <li>
                <a href="about.php"
                   class="nav-link <?php echo ($active_page == 'about.php') ? 'active' : ''; ?>">
                    ABOUT US
                </a>
            </li>

            <li>
                <a href="services.php"
                   class="nav-link <?php echo ($active_page == 'services.php') ? 'active' : ''; ?>">
                    SERVICES
                </a>
            </li>

            <li>
                <a href="contact.php"
                   class="nav-link <?php echo ($active_page == 'contact.php') ? 'active' : ''; ?>">
                    CONTACT
                </a>
            </li>

        </ul>


        <!-- User Actions -->
        <div class="navbar-actions">

            <?php if (isset($_SESSION['user_id'])): ?>

                <?php if ($_SESSION['user_role'] == 'admin'): ?>

                    <a href="admin/dashboard.php"
                       class="btn btn-secondary">
                        Dashboard
                    </a>

                <?php else: ?>

                    <a href="profile.php"
                       class="btn btn-secondary">
                        Profile
                    </a>

                <?php endif; ?>


                <a href="logout.php"
                   class="btn btn-text">
                    Sign Out
                </a>


            <?php else: ?>

                <button type="button"
                        class="btn btn-text"
                        data-auth-trigger="signin">
                    Sign In
                </button>

                <button type="button"
                        class="btn btn-primary"
                        data-auth-trigger="signup">
                    Get Started
                </button>

            <?php endif; ?>

        </div>

    </nav>

</header>