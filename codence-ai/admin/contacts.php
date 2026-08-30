<?php
// ============================================================
// admin/contacts.php
// Admin: View, mark read/unread, delete contact messages
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
// FLASH MESSAGE
// ============================================================

if (isset($_SESSION['admin_contacts_msg'])) {

    $msg = $_SESSION['admin_contacts_msg'];
    $msg_type = 'success';

    unset($_SESSION['admin_contacts_msg']);
}


// ============================================================
// HANDLE POST ACTIONS
// ============================================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = $_POST['action'] ?? '';
    $contact_id = (int)($_POST['contact_id'] ?? 0);


    // ----- Mark Status -----
    if ($action === 'mark_status' && $contact_id > 0) {

        $requested_status = $_POST['new_status'] ?? '';

        $allowed_statuses = [
            'unread',
            'read',
            'replied'
        ];

        $new_status = in_array(
            $requested_status,
            $allowed_statuses
        )
            ? $requested_status
            : 'read';


        $stmt = $conn->prepare(
            "UPDATE contacts SET status = ? WHERE id = ?"
        );

        $stmt->bind_param(
            "si",
            $new_status,
            $contact_id
        );

        $stmt->execute();
        $stmt->close();


        $_SESSION['admin_contacts_msg'] =
            'Message status updated.';

        header('Location: contacts.php');
        exit;
    }


    // ----- Delete Message -----
    if ($action === 'delete' && $contact_id > 0) {

        $stmt = $conn->prepare(
            "DELETE FROM contacts WHERE id = ?"
        );

        $stmt->bind_param(
            "i",
            $contact_id
        );

        $stmt->execute();
        $stmt->close();


        $_SESSION['admin_contacts_msg'] =
            'Message deleted.';

        header('Location: contacts.php');
        exit;
    }
}


// ============================================================
// VIEW SINGLE MESSAGE
// ============================================================

$viewing = null;

if (isset($_GET['view'])) {

    $view_id = (int)$_GET['view'];

    $stmt = $conn->prepare(
        "SELECT * FROM contacts WHERE id = ? LIMIT 1"
    );

    $stmt->bind_param(
        "i",
        $view_id
    );

    $stmt->execute();

    $viewing = $stmt
        ->get_result()
        ->fetch_assoc();

    $stmt->close();


    // Automatically mark unread message as read
    if (
        $viewing &&
        $viewing['status'] === 'unread'
    ) {

        $update = $conn->prepare(
            "UPDATE contacts
             SET status = 'read'
             WHERE id = ?"
        );

        $update->bind_param(
            "i",
            $view_id
        );

        $update->execute();
        $update->close();

        $viewing['status'] = 'read';
    }
}


// ============================================================
// FETCH ALL CONTACT MESSAGES
// ============================================================

$contacts_result = $conn->query(
    "SELECT *
     FROM contacts
     ORDER BY created_at DESC"
);

$contacts = $contacts_result
    ? $contacts_result->fetch_all(MYSQLI_ASSOC)
    : [];


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

            <h1>Contact Messages</h1>

            <span>
                <?php echo count($contacts); ?>
                Total Messages
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


            <!-- ==================== SINGLE MESSAGE ==================== -->

            <?php if ($viewing): ?>

                <div class="admin-form message-view">

                    <div class="message-header">

                        <div>

                            <h2 class="message-name">
                                <?php echo htmlspecialchars($viewing['full_name']); ?>
                            </h2>

                            <p class="message-meta">

                                <?php echo htmlspecialchars($viewing['email']); ?>

                                <?php if (!empty($viewing['organization'])): ?>

                                    &nbsp;|&nbsp;

                                    <?php echo htmlspecialchars($viewing['organization']); ?>

                                <?php endif; ?>

                            </p>

                        </div>


                        <span class="status-pill pill-<?php echo htmlspecialchars($viewing['status']); ?>">
                            <?php echo ucfirst(htmlspecialchars($viewing['status'])); ?>
                        </span>

                    </div>


                    <!-- Message -->
                    <div class="message-content">

                        <?php
                        echo nl2br(
                            htmlspecialchars(
                                $viewing['message']
                            )
                        );
                        ?>

                    </div>


                    <!-- Date -->
                    <p class="message-date">

                        Received:
                        <?php
                        echo date(
                            'd M Y, h:i A',
                            strtotime($viewing['created_at'])
                        );
                        ?>

                    </p>


                    <!-- Actions -->
                    <div class="message-actions">

                        <a
                            href="mailto:<?php echo htmlspecialchars($viewing['email']); ?>"
                            class="action-btn primary"
                        >
                            Reply via Email
                        </a>


                        <form method="POST">

                            <input
                                type="hidden"
                                name="action"
                                value="mark_status"
                            >

                            <input
                                type="hidden"
                                name="contact_id"
                                value="<?php echo (int)$viewing['id']; ?>"
                            >

                            <input
                                type="hidden"
                                name="new_status"
                                value="replied"
                            >

                            <button
                                type="submit"
                                class="action-btn secondary"
                            >
                                Mark as Replied
                            </button>

                        </form>


                        <form method="POST">

                            <input
                                type="hidden"
                                name="action"
                                value="mark_status"
                            >

                            <input
                                type="hidden"
                                name="contact_id"
                                value="<?php echo (int)$viewing['id']; ?>"
                            >

                            <input
                                type="hidden"
                                name="new_status"
                                value="unread"
                            >

                            <button
                                type="submit"
                                class="action-btn secondary"
                            >
                                Mark as Unread
                            </button>

                        </form>


                        <form
                            method="POST"
                            onsubmit="return confirm('Delete this message?');"
                        >

                            <input
                                type="hidden"
                                name="action"
                                value="delete"
                            >

                            <input
                                type="hidden"
                                name="contact_id"
                                value="<?php echo (int)$viewing['id']; ?>"
                            >

                            <button
                                type="submit"
                                class="action-btn danger"
                            >
                                Delete
                            </button>

                        </form>


                        <a
                            href="contacts.php"
                            class="action-btn secondary"
                        >
                            &larr; Back to List
                        </a>

                    </div>

                </div>

            <?php endif; ?>


            <!-- ==================== ALL MESSAGES ==================== -->

            <div class="data-section">

                <div class="data-section-header">
                    <h2>All Messages</h2>
                </div>


                <?php if (empty($contacts)): ?>

                    <p class="empty-state">
                        No contact messages yet.
                    </p>

                <?php else: ?>

                    <div class="table-wrapper">

                        <table class="admin-table">

                            <thead>

                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Organization</th>
                                    <th>Received</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>

                            </thead>


                            <tbody>

                                <?php foreach ($contacts as $contact): ?>

                                    <tr class="<?php echo $contact['status'] === 'unread' ? 'unread-row' : ''; ?>">

                                        <td>
                                            <?php echo (int)$contact['id']; ?>
                                        </td>

                                        <td>
                                            <strong>
                                                <?php echo htmlspecialchars($contact['full_name']); ?>
                                            </strong>
                                        </td>

                                        <td class="small-text">
                                            <?php echo htmlspecialchars($contact['email']); ?>
                                        </td>

                                        <td>
                                            <?php
                                            echo !empty($contact['organization'])
                                                ? htmlspecialchars($contact['organization'])
                                                : '—';
                                            ?>
                                        </td>

                                        <td>
                                            <?php
                                            echo date(
                                                'd M Y',
                                                strtotime($contact['created_at'])
                                            );
                                            ?>
                                        </td>

                                        <td>

                                            <span class="status-pill pill-<?php echo htmlspecialchars($contact['status']); ?>">
                                                <?php echo ucfirst(htmlspecialchars($contact['status'])); ?>
                                            </span>

                                        </td>

                                        <td>

                                            <a
                                                href="contacts.php?view=<?php echo (int)$contact['id']; ?>"
                                                class="action-btn primary"
                                            >
                                                View
                                            </a>


                                            <form
                                                method="POST"
                                                class="inline-form"
                                                onsubmit="return confirm('Delete this message?');"
                                            >

                                                <input
                                                    type="hidden"
                                                    name="action"
                                                    value="delete"
                                                >

                                                <input
                                                    type="hidden"
                                                    name="contact_id"
                                                    value="<?php echo (int)$contact['id']; ?>"
                                                >

                                                <button
                                                    type="submit"
                                                    class="action-btn danger"
                                                >
                                                    Delete
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