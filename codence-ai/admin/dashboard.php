<?php
// ============================================================
// admin/dashboard.php
// Admin Dashboard
// ============================================================

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

session_start();
require_once '../includes/db.php';


// ============================================================
// ADMIN PROTECTION
// ============================================================

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

// ============================================================
// FETCH STATISTICS
// ============================================================

// Total normal users
$total_users_result = $conn->query(
    "SELECT COUNT(*) AS cnt
     FROM users
     WHERE role = 'user'"
);

$total_users = $total_users_result
    ? (int)$total_users_result->fetch_assoc()['cnt']
    : 0;


// Active services
$total_services_result = $conn->query(
    "SELECT COUNT(*) AS cnt
     FROM services
     WHERE status = 'active'"
);

$total_services = $total_services_result
    ? (int)$total_services_result->fetch_assoc()['cnt']
    : 0;


// Total bookings
$total_bookings_result = $conn->query(
    "SELECT COUNT(*) AS cnt
     FROM bookings"
);

$total_bookings = $total_bookings_result
    ? (int)$total_bookings_result->fetch_assoc()['cnt']
    : 0;


// Pending bookings
$pending_bk_result = $conn->query(
    "SELECT COUNT(*) AS cnt
     FROM bookings
     WHERE status = 'pending'"
);

$pending_bk = $pending_bk_result
    ? (int)$pending_bk_result->fetch_assoc()['cnt']
    : 0;


// Confirmed bookings
$confirmed_bk_result = $conn->query(
    "SELECT COUNT(*) AS cnt
     FROM bookings
     WHERE status = 'confirmed'"
);

$confirmed_bk = $confirmed_bk_result
    ? (int)$confirmed_bk_result->fetch_assoc()['cnt']
    : 0;


// Unread contact messages
$unread_msgs_result = $conn->query(
    "SELECT COUNT(*) AS cnt
     FROM contacts
     WHERE status = 'unread'"
);

$unread_msgs = $unread_msgs_result
    ? (int)$unread_msgs_result->fetch_assoc()['cnt']
    : 0;


// ============================================================
// RECENT BOOKINGS
// ============================================================

$recent_bookings_result = $conn->query(
    "SELECT
        id,
        full_name,
        sub_service,
        preferred_date,
        status,
        created_at
     FROM bookings
     ORDER BY created_at DESC
     LIMIT 5"
);

$recent_bookings = $recent_bookings_result
    ? $recent_bookings_result->fetch_all(MYSQLI_ASSOC)
    : [];


// ============================================================
// RECENT CONTACTS
// ============================================================

$recent_contacts_result = $conn->query(
    "SELECT
        id,
        full_name,
        email,
        status,
        created_at
     FROM contacts
     ORDER BY created_at DESC
     LIMIT 5"
);

$recent_contacts = $recent_contacts_result
    ? $recent_contacts_result->fetch_all(MYSQLI_ASSOC)
    : [];


// ============================================================
// COMMON HEADER
// ============================================================

include 'includes/admin_header.php';
?>

<div class="admin-layout">

    <?php include 'includes/admin_sidebar.php'; ?>


    <main class="admin-main">

        <!-- ==================================================
             TOPBAR
        =================================================== -->

        <div class="admin-topbar">

            <h1>Dashboard</h1>

            <span>
                Welcome back,
                <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?>

                &nbsp;|&nbsp;

                <?php echo date('d M Y'); ?>
            </span>

        </div>


        <div class="admin-content">


            <!-- ==================================================
                 STATISTICS
            =================================================== -->

            <div class="stats-grid">

                <!-- Total Users -->
                <div class="stat-card highlight">

                    <span class="stat-card-label">
                        Total Users
                    </span>

                    <span class="stat-card-value">
                        <?php echo $total_users; ?>
                    </span>

                    <span class="stat-card-sub">
                        Registered accounts
                    </span>

                </div>


                <!-- Active Services -->
                <div class="stat-card highlight">

                    <span class="stat-card-label">
                        Active Services
                    </span>

                    <span class="stat-card-value">
                        <?php echo $total_services; ?>
                    </span>

                    <span class="stat-card-sub">
                        Live on website
                    </span>

                </div>


                <!-- Total Bookings -->
                <div class="stat-card highlight">

                    <span class="stat-card-label">
                        Total Bookings
                    </span>

                    <span class="stat-card-value">
                        <?php echo $total_bookings; ?>
                    </span>

                    <span class="stat-card-sub">
                        All time consultations
                    </span>

                </div>


                <!-- Pending -->
                <div class="stat-card">

                    <span class="stat-card-label">
                        Pending Bookings
                    </span>

                    <span class="stat-card-value">
                        <?php echo $pending_bk; ?>
                    </span>

                    <span class="stat-card-sub">
                        Awaiting confirmation
                    </span>

                </div>


                <!-- Confirmed -->
                <div class="stat-card">

                    <span class="stat-card-label">
                        Confirmed Bookings
                    </span>

                    <span class="stat-card-value">
                        <?php echo $confirmed_bk; ?>
                    </span>

                    <span class="stat-card-sub">
                        Successfully confirmed
                    </span>

                </div>


                <!-- Unread Messages -->
                <div class="stat-card">

                    <span class="stat-card-label">
                        Unread Messages
                    </span>

                    <span class="stat-card-value">
                        <?php echo $unread_msgs; ?>
                    </span>

                    <span class="stat-card-sub">
                        Contact form submissions
                    </span>

                </div>

            </div>


            <!-- ==================================================
                 RECENT DATA
            =================================================== -->

            <div class="two-col-grid">


                <!-- ==================================================
                     RECENT BOOKINGS
                =================================================== -->

                <div class="data-section">

                    <div class="data-section-header">

                        <h2>
                            Recent Bookings
                        </h2>

                        <a href="bookings.php">
                            View All &rarr;
                        </a>

                    </div>


                    <?php if (empty($recent_bookings)): ?>

                        <div class="empty-state">
                            No bookings yet.
                        </div>

                    <?php else: ?>

                        <div class="table-wrapper">

                            <table class="admin-table">

                                <thead>

                                    <tr>
                                        <th>Client</th>
                                        <th>Service</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>

                                </thead>


                                <tbody>

                                    <?php foreach ($recent_bookings as $bk): ?>

                                        <tr>

                                            <td>
                                                <?php
                                                echo htmlspecialchars(
                                                    $bk['full_name']
                                                );
                                                ?>
                                            </td>

                                            <td>
                                                <?php
                                                echo htmlspecialchars(
                                                    $bk['sub_service']
                                                );
                                                ?>
                                            </td>

                                            <td>
                                                <?php
                                                echo date(
                                                    'd M',
                                                    strtotime(
                                                        $bk['preferred_date']
                                                    )
                                                );
                                                ?>
                                            </td>

                                            <td>

                                                <span class="status-pill pill-<?php echo htmlspecialchars($bk['status']); ?>">
                                                    <?php
                                                    echo ucfirst(
                                                        $bk['status']
                                                    );
                                                    ?>
                                                </span>

                                            </td>

                                        </tr>

                                    <?php endforeach; ?>

                                </tbody>

                            </table>

                        </div>

                    <?php endif; ?>

                </div>


                <!-- ==================================================
                     RECENT CONTACTS
                =================================================== -->

                <div class="data-section">

                    <div class="data-section-header">

                        <h2>
                            Recent Messages
                        </h2>

                        <a href="contacts.php">
                            View All &rarr;
                        </a>

                    </div>


                    <?php if (empty($recent_contacts)): ?>

                        <div class="empty-state">
                            No messages yet.
                        </div>

                    <?php else: ?>

                        <div class="table-wrapper">

                            <table class="admin-table">

                                <thead>

                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                    </tr>

                                </thead>


                                <tbody>

                                    <?php foreach ($recent_contacts as $msg): ?>

                                        <tr>

                                            <td>
                                                <?php
                                                echo htmlspecialchars(
                                                    $msg['full_name']
                                                );
                                                ?>
                                            </td>

                                            <td>
                                                <?php
                                                echo htmlspecialchars(
                                                    $msg['email']
                                                );
                                                ?>
                                            </td>

                                            <td>

                                                <span class="status-pill pill-<?php echo htmlspecialchars($msg['status']); ?>">
                                                    <?php
                                                    echo ucfirst(
                                                        $msg['status']
                                                    );
                                                    ?>
                                                </span>

                                            </td>

                                        </tr>

                                    <?php endforeach; ?>

                                </tbody>

                            </table>

                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </main>

</div>

</body>
</html>