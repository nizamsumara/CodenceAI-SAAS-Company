<?php
// ============================================================
// admin/services.php
// Admin: Add, Edit, Delete, Activate/Deactivate services
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

// Flash message from edit-service.php
if (isset($_SESSION['admin_services_msg'])) {
    $msg = $_SESSION['admin_services_msg'];
    $msg_type = 'success';
    unset($_SESSION['admin_services_msg']);
}

// ============================================================
// HANDLE POST ACTIONS
// ============================================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = $_POST['action'] ?? '';

    // --------------------------------------------------------
    // ADD SERVICE
    // --------------------------------------------------------
    if ($action === 'add') {

        $title      = trim($_POST['title'] ?? '');
        $cat_id     = (int)($_POST['category_id'] ?? 0);
        $short_desc = trim($_POST['short_description'] ?? '');
        $full_desc  = trim($_POST['full_description'] ?? '');

        $status_input = $_POST['status'] ?? 'active';
        $status = in_array($status_input, ['active', 'inactive'])
            ? $status_input
            : 'active';

        if (empty($title) || $cat_id <= 0) {

            $msg = 'Title and category are required.';
            $msg_type = 'error';

        } else {

            // Create slug
            $slug = strtolower(
                preg_replace('/[^a-zA-Z0-9]+/', '-', $title)
            );

            $slug = trim($slug, '-');

            // Check whether slug already exists
            $slug_check = $conn->prepare(
                "SELECT id FROM services WHERE slug = ?"
            );

            $slug_check->bind_param("s", $slug);
            $slug_check->execute();

            if ($slug_check->get_result()->num_rows > 0) {
                $slug .= '-' . time();
            }

            $slug_check->close();

            // Insert service
            $stmt = $conn->prepare(
                "INSERT INTO services
                (category_id, title, slug, short_description, full_description, status)
                VALUES (?, ?, ?, ?, ?, ?)"
            );

            $stmt->bind_param(
                "isssss",
                $cat_id,
                $title,
                $slug,
                $short_desc,
                $full_desc,
                $status
            );

            if ($stmt->execute()) {
                $msg = 'Service added successfully.';
                $msg_type = 'success';
            } else {
                $msg = 'Failed to add service.';
                $msg_type = 'error';
            }

            $stmt->close();
        }
    }

    // --------------------------------------------------------
    // TOGGLE SERVICE STATUS
    // --------------------------------------------------------
    elseif ($action === 'toggle_status') {

        $sid = (int)($_POST['service_id'] ?? 0);

        $new_status_input = $_POST['new_status'] ?? 'inactive';

        $new_status = ($new_status_input === 'active')
            ? 'active'
            : 'inactive';

        if ($sid > 0) {

            $stmt = $conn->prepare(
                "UPDATE services SET status = ? WHERE id = ?"
            );

            $stmt->bind_param("si", $new_status, $sid);
            $stmt->execute();
            $stmt->close();

            $msg = 'Service status updated.';
            $msg_type = 'success';
        }
    }

    // --------------------------------------------------------
    // DELETE SERVICE
    // --------------------------------------------------------
    elseif ($action === 'delete') {

        $sid = (int)($_POST['service_id'] ?? 0);

        if ($sid > 0) {

            $stmt = $conn->prepare(
                "DELETE FROM services WHERE id = ?"
            );

            $stmt->bind_param("i", $sid);
            $stmt->execute();
            $stmt->close();

            $msg = 'Service deleted.';
            $msg_type = 'success';
        }
    }
}

// ============================================================
// FETCH CATEGORIES
// ============================================================

$categories_result = $conn->query(
    "SELECT * FROM service_categories ORDER BY id ASC"
);

$categories = $categories_result
    ? $categories_result->fetch_all(MYSQLI_ASSOC)
    : [];

// ============================================================
// FETCH SERVICES
// ============================================================

$services_result = $conn->query(
    "SELECT
        s.*,
        sc.name AS cat_name
     FROM services s
     JOIN service_categories sc
        ON s.category_id = sc.id
     ORDER BY sc.id ASC, s.id ASC"
);

$services = $services_result
    ? $services_result->fetch_all(MYSQLI_ASSOC)
    : [];

// ============================================================
// COMMON ADMIN HEADER
// ============================================================

include 'includes/admin_header.php';
?>

<div class="admin-layout">

    <?php include 'includes/admin_sidebar.php'; ?>

    <main class="admin-main">

        <!-- Topbar -->
        <div class="admin-topbar">
            <h1>Manage Services</h1>

            <span>
                <?php echo count($services); ?> Total Services
            </span>
        </div>

        <div class="admin-content">

            <!-- Message -->
            <?php if ($msg): ?>

                <div class="admin-alert <?php echo htmlspecialchars($msg_type); ?>">
                    <?php echo htmlspecialchars($msg); ?>
                </div>

            <?php endif; ?>


            <!-- ==================================================
                 ADD SERVICE
            =================================================== -->

            <div class="data-section">

                <div class="data-section-header">
                    <h2>Add New Service</h2>
                </div>

                <div class="admin-form">

                    <form method="POST">

                        <input
                            type="hidden"
                            name="action"
                            value="add"
                        >

                        <div class="form-grid">

                            <div class="form-group">

                                <label class="form-label">
                                    Service Title *
                                </label>

                                <input
                                    type="text"
                                    name="title"
                                    class="form-control"
                                    placeholder="e.g. AI Chatbot"
                                    required
                                >

                            </div>


                            <div class="form-group">

                                <label class="form-label">
                                    Category *
                                </label>

                                <select
                                    name="category_id"
                                    class="form-control"
                                    required
                                >

                                    <option value="">
                                        Select category...
                                    </option>

                                    <?php foreach ($categories as $cat): ?>

                                        <option value="<?php echo (int)$cat['id']; ?>">
                                            <?php echo htmlspecialchars($cat['name']); ?>
                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </div>


                            <div class="form-group">

                                <label class="form-label">
                                    Short Description
                                </label>

                                <input
                                    type="text"
                                    name="short_description"
                                    class="form-control"
                                    placeholder="One line description..."
                                >

                            </div>


                            <div class="form-group">

                                <label class="form-label">
                                    Status
                                </label>

                                <select
                                    name="status"
                                    class="form-control"
                                >

                                    <option value="active">
                                        Active
                                    </option>

                                    <option value="inactive">
                                        Inactive
                                    </option>

                                </select>

                            </div>


                            <div class="form-group full">

                                <label class="form-label">
                                    Full Description
                                </label>

                                <textarea
                                    name="full_description"
                                    class="form-control"
                                    rows="4"
                                    placeholder="Detailed description..."
                                ></textarea>

                            </div>

                        </div>


                        <div class="form-actions">

                            <button
                                type="submit"
                                class="action-btn primary"
                            >
                                + Add Service
                            </button>

                        </div>

                    </form>

                </div>

            </div>


            <!-- ==================================================
                 SERVICES TABLE
            =================================================== -->

            <div class="data-section">

                <div class="data-section-header">
                    <h2>All Services</h2>
                </div>

                <?php if (empty($services)): ?>

                    <div class="empty-state">
                        No services found.
                    </div>

                <?php else: ?>

                    <div class="table-wrapper">

                        <table class="admin-table">

                            <thead>

                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Short Description</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>

                            </thead>

                            <tbody>

                                <?php foreach ($services as $svc): ?>

                                    <tr>

                                        <td>
                                            <?php echo (int)$svc['id']; ?>
                                        </td>

                                        <td>
                                            <strong>
                                                <?php echo htmlspecialchars($svc['title']); ?>
                                            </strong>
                                        </td>

                                        <td>
                                            <?php echo htmlspecialchars($svc['cat_name']); ?>
                                        </td>

                                        <td>
                                            <?php
                                            echo htmlspecialchars(
                                                substr(
                                                    $svc['short_description'] ?? '',
                                                    0,
                                                    60
                                                )
                                            );
                                            ?>
                                        </td>

                                        <td>

                                            <span class="status-pill pill-<?php echo htmlspecialchars($svc['status']); ?>">
                                                <?php echo ucfirst($svc['status']); ?>
                                            </span>

                                        </td>

                                        <td>

                                            <!-- Toggle -->
                                            <form
                                                method="POST"
                                                class="inline-form"
                                            >

                                                <input
                                                    type="hidden"
                                                    name="action"
                                                    value="toggle_status"
                                                >

                                                <input
                                                    type="hidden"
                                                    name="service_id"
                                                    value="<?php echo (int)$svc['id']; ?>"
                                                >

                                                <input
                                                    type="hidden"
                                                    name="new_status"
                                                    value="<?php echo $svc['status'] === 'active' ? 'inactive' : 'active'; ?>"
                                                >

                                                <button
                                                    type="submit"
                                                    class="action-btn secondary"
                                                >
                                                    <?php
                                                    echo $svc['status'] === 'active'
                                                        ? 'Deactivate'
                                                        : 'Activate';
                                                    ?>
                                                </button>

                                            </form>


                                            <!-- Edit -->
                                            <a
                                                href="edit-service.php?id=<?php echo (int)$svc['id']; ?>"
                                                class="action-btn primary"
                                            >
                                                Edit
                                            </a>


                                            <!-- Delete -->
                                            <form
                                                method="POST"
                                                class="inline-form"
                                                onsubmit="return confirm('Delete this service?');"
                                            >

                                                <input
                                                    type="hidden"
                                                    name="action"
                                                    value="delete"
                                                >

                                                <input
                                                    type="hidden"
                                                    name="service_id"
                                                    value="<?php echo (int)$svc['id']; ?>"
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