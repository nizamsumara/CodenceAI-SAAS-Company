<?php
// ============================================================
// admin/users.php
// Admin: View, edit role, delete users
// ============================================================

session_start();
require_once '../includes/db.php';

// Admin protection
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

$msg = '';
$msg_type = '';

// Flash message
if (isset($_SESSION['admin_users_msg'])) {

    $msg = $_SESSION['admin_users_msg'];
    $msg_type = 'success';

    unset($_SESSION['admin_users_msg']);
}

// ============================================================
// HANDLE POST ACTIONS
// ============================================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = $_POST['action'] ?? '';

    // --------------------------------------------------------
    // DELETE USER
    // --------------------------------------------------------

    if ($action === 'delete') {

        $del_id = (int)($_POST['user_id'] ?? 0);

        // Prevent deleting yourself
        if ($del_id > 0 && $del_id !== (int)$_SESSION['user_id']) {

            $stmt = $conn->prepare(
                "DELETE FROM users WHERE id = ?"
            );

            $stmt->bind_param("i", $del_id);
            $stmt->execute();
            $stmt->close();

            $msg = 'User deleted successfully.';
            $msg_type = 'success';

        } else {

            $msg = 'You cannot delete your own account.';
            $msg_type = 'error';
        }
    }

    // --------------------------------------------------------
    // CHANGE ROLE
    // --------------------------------------------------------

    elseif ($action === 'change_role') {

        $uid = (int)($_POST['user_id'] ?? 0);

        $role_input = $_POST['role'] ?? 'user';

        $role = in_array($role_input, ['user', 'admin'])
            ? $role_input
            : 'user';

        // Prevent changing your own role
        if ($uid > 0 && $uid !== (int)$_SESSION['user_id']) {

            $stmt = $conn->prepare(
                "UPDATE users SET role = ? WHERE id = ?"
            );

            $stmt->bind_param("si", $role, $uid);
            $stmt->execute();
            $stmt->close();

            $msg = 'User role updated.';
            $msg_type = 'success';
        }
    }
}

// ============================================================
// FETCH USERS
// ============================================================

$users_result = $conn->query(
    "SELECT
        id,
        full_name,
        email,
        phone,
        role,
        created_at
     FROM users
     ORDER BY created_at DESC"
);

$users = $users_result
    ? $users_result->fetch_all(MYSQLI_ASSOC)
    : [];

// Common header
include 'includes/admin_header.php';
?>

<div class="admin-layout">

    <?php include 'includes/admin_sidebar.php'; ?>

    <main class="admin-main">

        <div class="admin-topbar">

            <h1>Manage Users</h1>

            <span>
                <?php echo count($users); ?> Total Users
            </span>

        </div>


        <div class="admin-content">

            <!-- Message -->
            <?php if ($msg): ?>

                <div class="admin-alert <?php echo htmlspecialchars($msg_type); ?>">
                    <?php echo htmlspecialchars($msg); ?>
                </div>

            <?php endif; ?>


            <div class="data-section">

                <div class="data-section-header">
                    <h2>All Users</h2>
                </div>


                <?php if (empty($users)): ?>

                    <div class="empty-state">
                        No users found.
                    </div>

                <?php else: ?>

                    <div class="table-wrapper">

                        <table class="admin-table">

                            <thead>

                                <tr>
                                    <th>#</th>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Role</th>
                                    <th>Joined</th>
                                    <th>Actions</th>
                                </tr>

                            </thead>


                            <tbody>

                                <?php foreach ($users as $u): ?>

                                    <tr>

                                        <td>
                                            <?php echo (int)$u['id']; ?>
                                        </td>

                                        <td>
                                            <strong>
                                                <?php echo htmlspecialchars($u['full_name']); ?>
                                            </strong>
                                        </td>

                                        <td>
                                            <?php echo htmlspecialchars($u['email']); ?>
                                        </td>

                                        <td>
                                            <?php
                                            echo htmlspecialchars(
                                                $u['phone'] ?? '—'
                                            );
                                            ?>
                                        </td>

                                        <td>

                                            <span class="status-pill <?php echo $u['role'] === 'admin' ? 'pill-confirmed' : 'pill-read'; ?>">
                                                <?php echo ucfirst($u['role']); ?>
                                            </span>

                                        </td>

                                        <td>
                                            <?php
                                            echo date(
                                                'd M Y',
                                                strtotime($u['created_at'])
                                            );
                                            ?>
                                        </td>

                                        <td>

                                            <?php if ((int)$u['id'] !== (int)$_SESSION['user_id']): ?>

                                                <!-- Change Role -->
                                                <form
                                                    method="POST"
                                                    class="inline-form"
                                                >

                                                    <input
                                                        type="hidden"
                                                        name="action"
                                                        value="change_role"
                                                    >

                                                    <input
                                                        type="hidden"
                                                        name="user_id"
                                                        value="<?php echo (int)$u['id']; ?>"
                                                    >

                                                    <select
                                                        name="role"
                                                        class="mini-select"
                                                        onchange="this.form.submit()"
                                                    >

                                                        <option
                                                            value="user"
                                                            <?php echo $u['role'] === 'user' ? 'selected' : ''; ?>
                                                        >
                                                            User
                                                        </option>

                                                        <option
                                                            value="admin"
                                                            <?php echo $u['role'] === 'admin' ? 'selected' : ''; ?>
                                                        >
                                                            Admin
                                                        </option>

                                                    </select>

                                                </form>


                                                <!-- Edit -->
                                                <a
                                                    href="edit-user.php?id=<?php echo (int)$u['id']; ?>"
                                                    class="action-btn primary"
                                                >
                                                    Edit
                                                </a>


                                                <!-- Delete -->
                                                <form
                                                    method="POST"
                                                    class="inline-form"
                                                    onsubmit="return confirm('Delete this user?');"
                                                >

                                                    <input
                                                        type="hidden"
                                                        name="action"
                                                        value="delete"
                                                    >

                                                    <input
                                                        type="hidden"
                                                        name="user_id"
                                                        value="<?php echo (int)$u['id']; ?>"
                                                    >

                                                    <button
                                                        type="submit"
                                                        class="action-btn danger"
                                                    >
                                                        Delete
                                                    </button>

                                                </form>

                                            <?php else: ?>

                                                <span class="current-user">
                                                    (You)
                                                </span>

                                            <?php endif; ?>

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