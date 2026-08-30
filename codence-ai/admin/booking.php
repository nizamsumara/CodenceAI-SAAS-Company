<?php
// ============================================================
// admin/bookings.php
// Admin: View all bookings and update their status
// Protected: Admin only
// ============================================================

session_start();
require_once '../includes/db.php';

// ----- Admin Protection -----
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

$msg = '';
$msg_type = '';


// ============================================================
// HANDLE STATUS UPDATE
// ============================================================

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {

    $booking_id = (int)($_POST['booking_id'] ?? 0);
    $new_status = $_POST['new_status'] ?? '';

    $allowed_statuses = [
        'pending',
        'confirmed',
        'completed',
        'canceled'
    ];

    if (
        $booking_id > 0 &&
        in_array($new_status, $allowed_statuses)
    ) {

        $stmt = $conn->prepare(
            "UPDATE bookings SET status = ? WHERE id = ?"
        );

        $stmt->bind_param(
            "si",
            $new_status,
            $booking_id
        );

        $stmt->execute();
        $stmt->close();

        $_SESSION['admin_bk_msg'] =
            'Booking status updated successfully.';
    }

    $redirect_url = 'bookings.php';

    if (isset($_GET['status'])) {
        $redirect_url .=
            '?status=' . urlencode($_GET['status']);
    }

    header('Location: ' . $redirect_url);
    exit;
}


// ============================================================
// FLASH MESSAGE
// ============================================================

if (isset($_SESSION['admin_bk_msg'])) {

    $msg = $_SESSION['admin_bk_msg'];
    $msg_type = 'success';

    unset($_SESSION['admin_bk_msg']);
}


// ============================================================
// FILTER BY STATUS
// ============================================================

$filter_status = $_GET['status'] ?? 'all';

$allowed_filters = [
    'all',
    'pending',
    'confirmed',
    'completed',
    'cancel'
];

if (!in_array($filter_status, $allowed_filters)) {
    $filter_status = 'all';
}


// ============================================================
// FETCH BOOKINGS
// ============================================================

if ($filter_status === 'all') {

    $bookings_result = $conn->query(
        "SELECT 
            b.*,
            u.email AS account_email
         FROM bookings b
         LEFT JOIN users u ON b.user_id = u.id
         ORDER BY b.created_at DESC"
    );

} else {

    $stmt = $conn->prepare(
        "SELECT 
            b.*,
            u.email AS account_email
         FROM bookings b
         LEFT JOIN users u ON b.user_id = u.id
         WHERE b.status = ?
         ORDER BY b.created_at DESC"
    );

    $stmt->bind_param(
        "s",
        $filter_status
    );

    $stmt->execute();

    $bookings_result = $stmt->get_result();

    $stmt->close();
}


$bookings = $bookings_result
    ? $bookings_result->fetch_all(MYSQLI_ASSOC)
    : [];


// ============================================================
// TIME SLOT DISPLAY
// ============================================================

$time_map = [
    '09:00:00' => '09:00 AM - 11:00 AM',
    '12:00:00' => '12:00 PM - 02:00 PM',
    '15:00:00' => '03:00 PM - 05:00 PM',
    '18:00:00' => '06:00 PM - 07:00 PM'
];


// ============================================================
// SHARED ADMIN HEADER
// ============================================================

include 'includes/admin_header.php';
?>

<div class="admin-layout">

    <?php include 'includes/admin_sidebar.php'; ?>

    <main class="admin-main">

        <!-- Top Bar -->
        <div class="admin-topbar">

            <h1>Manage Bookings</h1>

            <span>
                <?php echo count($bookings); ?>
                Booking(s) Found
            </span>

        </div>


        <!-- Content -->
        <div class="admin-content">

            <!-- Alert -->
            <?php if ($msg): ?>

                <div class="admin-alert <?php echo $msg_type; ?> admin-alert-spacing">
                    <?php echo htmlspecialchars($msg); ?>
                </div>

            <?php endif; ?>


            <!-- Filter Tabs -->
            <div class="filter-tabs">

                <?php foreach (
                    ['all', 'pending', 'confirmed', 'completed', 'cancel']
                    as $status
                ): ?>

                    <a
                        href="bookings.php?status=<?php echo $status; ?>"
                        class="action-btn <?php echo ($filter_status === $status) ? 'primary' : 'secondary'; ?>"
                    >
                        <?php echo ucfirst($status); ?>
                    </a>

                <?php endforeach; ?>

            </div>


            <!-- Bookings -->
            <div class="data-section">

                <div class="data-section-header">
                    <h2>Bookings</h2>
                </div>


                <?php if (empty($bookings)): ?>

                    <p class="empty-state">
                        No bookings found.
                    </p>

                <?php else: ?>

                    <div class="table-wrapper">

                        <table class="admin-table">

                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Customer</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Category</th>
                                    <th>Service</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Notes</th>
                                    <th>Status</th>
                                    <th>Update</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php foreach ($bookings as $booking): ?>

                                    <tr>

                                        <td>
                                            #<?php echo (int)$booking['id']; ?>
                                        </td>

                                        <td>
                                            <strong>
                                                <?php echo htmlspecialchars($booking['full_name']); ?>
                                            </strong>
                                        </td>

                                        <td class="small-text">
                                            <?php echo htmlspecialchars($booking['email']); ?>
                                        </td>

                                        <td>
                                            <?php echo htmlspecialchars($booking['phone']); ?>
                                        </td>

                                        <td>
                                            <?php echo htmlspecialchars($booking['service_category']); ?>
                                        </td>

                                        <td>
                                            <?php echo htmlspecialchars($booking['sub_service']); ?>
                                        </td>

                                        <td>
                                            <?php
                                            echo date(
                                                'd M Y',
                                                strtotime($booking['preferred_date'])
                                            );
                                            ?>
                                        </td>

                                        <td class="nowrap small-text">
                                            <?php
                                            echo $time_map[$booking['preferred_time']]
                                                ?? date(
                                                    'h:i A',
                                                    strtotime($booking['preferred_time'])
                                                );
                                            ?>
                                        </td>

                                        <td class="notes-cell">

                                            <?php
                                            if (!empty($booking['notes'])) {

                                                echo htmlspecialchars(
                                                    substr(
                                                        $booking['notes'],
                                                        0,
                                                        50
                                                    )
                                                ) . '...';

                                            } else {

                                                echo '—';
                                            }
                                            ?>

                                        </td>

                                        <td>

                                            <span class="status-pill pill-<?php echo htmlspecialchars($booking['status']); ?>">
                                                <?php echo ucfirst(htmlspecialchars($booking['status'])); ?>
                                            </span>

                                        </td>

                                        <td>

                                            <form
                                                method="POST"
                                                action="bookings.php<?php echo $filter_status !== 'all' ? '?status=' . urlencode($filter_status) : ''; ?>"
                                                class="status-form"
                                            >

                                                <input
                                                    type="hidden"
                                                    name="booking_id"
                                                    value="<?php echo (int)$booking['id']; ?>"
                                                >

                                                <select
                                                    name="new_status"
                                                    class="mini-select"
                                                >

                                                    <option
                                                        value="pending"
                                                        <?php echo ($booking['status'] === 'pending') ? 'selected' : ''; ?>
                                                    >
                                                        Pending
                                                    </option>

                                                    <option
                                                        value="confirmed"
                                                        <?php echo ($booking['status'] === 'confirmed') ? 'selected' : ''; ?>
                                                    >
                                                        Confirmed
                                                    </option>

                                                    <option
                                                        value="completed"
                                                        <?php echo ($booking['status'] === 'completed') ? 'selected' : ''; ?>
                                                    >
                                                        Completed
                                                    </option>

                                                    <option
                                                        value="cancel"
                                                        <?php echo ($booking['status'] === 'cancel') ? 'selected' : ''; ?>
                                                    >
                                                        Cancel
                                                    </option>

                                                </select>

                                                <button
                                                    type="submit"
                                                    name="update_status"
                                                    class="action-btn primary"
                                                >
                                                    Save
                                                </button>

                                            </form>

                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            </tbody>

                        </table>

                    </div>

                <?php endif; ?>

            </div>

        </div>

    </main>

</div>

</body>
</html>
