<?php
// ============================================================
// admin/edit-user.php
// Admin: Edit user's basic information
// Password is not displayed or edited here
// ============================================================

session_start();
require_once '../includes/db.php';

// Admin protection
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

$user_id = (int)($_GET['id'] ?? 0);

if ($user_id <= 0) {
    header('Location: users.php');
    exit;
}

$form_error = '';

// ============================================================
// HANDLE FORM SUBMISSION
// ============================================================

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_user'])) {

    $full_name = trim(
        $_POST['full_name'] ?? ''
    );

    $phone = trim(
        $_POST['phone'] ?? ''
    );

    $role_input = $_POST['role'] ?? 'user';

    $role = in_array(
        $role_input,
        ['user', 'admin']
    )
        ? $role_input
        : 'user';


    if (empty($full_name)) {

        $form_error = 'Full name is required.';

    } elseif (
        $user_id === (int)$_SESSION['user_id']
        && $role !== 'admin'
    ) {

        $form_error = 'You cannot change your own role.';

    } else {

        $stmt = $conn->prepare(
            "UPDATE users
             SET full_name = ?,
                 phone = ?,
                 role = ?
             WHERE id = ?"
        );

        $stmt->bind_param(
            "sssi",
            $full_name,
            $phone,
            $role,
            $user_id
        );

        $stmt->execute();
        $stmt->close();

        $_SESSION['admin_users_msg'] =
            'User updated successfully.';

        header('Location: users.php');
        exit;
    }
}

// ============================================================
// FETCH USER
// ============================================================

$stmt = $conn->prepare(
    "SELECT
        id,
        full_name,
        email,
        phone,
        role,
        created_at
     FROM users
     WHERE id = ?
     LIMIT 1"
);

$stmt->bind_param("i", $user_id);
$stmt->execute();

$user = $stmt
    ->get_result()
    ->fetch_assoc();

$stmt->close();

if (!$user) {
    header('Location: users.php');
    exit;
}

// Common header
include 'includes/admin_header.php';
?>

<div class="admin-layout">

    <?php include 'includes/admin_sidebar.php'; ?>

    <main class="admin-main">

        <div class="admin-topbar">

            <h1>Edit User</h1>

            <a
                href="users.php"
                class="action-btn secondary"
            >
                &larr; Back to Users
            </a>

        </div>


        <div class="admin-content">

            <?php if ($form_error): ?>

                <div class="admin-alert error">
                    <?php echo htmlspecialchars($form_error); ?>
                </div>

            <?php endif; ?>


            <div class="admin-form">

                <h2 class="form-title">
                    User #<?php echo (int)$user['id']; ?>
                </h2>


                <form
                    method="POST"
                    action="edit-user.php?id=<?php echo (int)$user_id; ?>"
                >

                    <div class="form-grid">

                        <!-- Full Name -->
                        <div class="form-group full">

                            <label class="form-label">
                                Full Name
                            </label>

                            <input
                                type="text"
                                name="full_name"
                                class="form-control"
                                required
                                value="<?php echo htmlspecialchars($user['full_name']); ?>"
                            >

                        </div>


                        <!-- Email -->
                        <div class="form-group full">

                            <label class="form-label">
                                Email Address
                            </label>

                            <input
                                type="email"
                                class="form-control disabled-input"
                                value="<?php echo htmlspecialchars($user['email']); ?>"
                                disabled
                            >

                            <small class="form-help">
                                Email cannot be changed here.
                            </small>

                        </div>


                        <!-- Phone -->
                        <div class="form-group">

                            <label class="form-label">
                                Phone Number
                            </label>

                            <input
                                type="tel"
                                name="phone"
                                class="form-control"
                                value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>"
                            >

                        </div>


                        <!-- Role -->
                        <div class="form-group">

                            <label class="form-label">
                                Role
                            </label>

                            <select
                                name="role"
                                class="form-control"
                            >

                                <option
                                    value="user"
                                    <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>
                                >
                                    User
                                </option>

                                <option
                                    value="admin"
                                    <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>
                                >
                                    Admin
                                </option>

                            </select>

                        </div>


                        <!-- Password Information -->
                        <div class="form-info full">

                            <strong>Password:</strong>
                            Not shown here for security.
                            Users manage their own password.

                        </div>

                    </div>


                    <div class="form-actions">

                        <button
                            type="submit"
                            name="save_user"
                            class="action-btn primary"
                        >
                            Save Changes
                        </button>

                        <a
                            href="users.php"
                            class="action-btn secondary"
                        >
                            Cancel
                        </a>

                    </div>

                </form>

            </div>


            <div class="member-info">

                Member since:
                <?php
                echo date(
                    'd M Y',
                    strtotime($user['created_at'])
                );
                ?>

            </div>

        </div>

    </main>

</div>

</body>
</html>