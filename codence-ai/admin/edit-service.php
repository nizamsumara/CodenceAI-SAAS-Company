<?php
// ============================================================
// admin/edit-service.php
// Admin: Edit an existing service
// ============================================================

session_start();
require_once '../includes/db.php';

// Admin protection
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

$service_id = (int)($_GET['id'] ?? 0);

if ($service_id <= 0) {
    header('Location: services.php');
    exit;
}

$form_error = '';

// ============================================================
// HANDLE FORM SUBMISSION
// ============================================================

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_service'])) {

    $title = trim($_POST['title'] ?? '');

    $cat_id = (int)($_POST['category_id'] ?? 0);

    $short_desc = trim(
        $_POST['short_description'] ?? ''
    );

    $full_desc = trim(
        $_POST['full_description'] ?? ''
    );

    $status_input = $_POST['status'] ?? 'active';

    $status = in_array(
        $status_input,
        ['active', 'inactive']
    )
        ? $status_input
        : 'active';


    if (empty($title) || $cat_id <= 0) {

        $form_error = 'Title and category are required.';

    } else {

        $stmt = $conn->prepare(
            "UPDATE services
             SET category_id = ?,
                 title = ?,
                 short_description = ?,
                 full_description = ?,
                 status = ?
             WHERE id = ?"
        );

        $stmt->bind_param(
            "issssi",
            $cat_id,
            $title,
            $short_desc,
            $full_desc,
            $status,
            $service_id
        );

        $stmt->execute();
        $stmt->close();

        $_SESSION['admin_services_msg'] =
            'Service updated successfully.';

        header('Location: services.php');
        exit;
    }
}

// ============================================================
// FETCH SERVICE
// ============================================================

$stmt = $conn->prepare(
    "SELECT *
     FROM services
     WHERE id = ?
     LIMIT 1"
);

$stmt->bind_param("i", $service_id);
$stmt->execute();

$service = $stmt
    ->get_result()
    ->fetch_assoc();

$stmt->close();

if (!$service) {
    header('Location: services.php');
    exit;
}

// ============================================================
// FETCH CATEGORIES
// ============================================================

$categories_result = $conn->query(
    "SELECT *
     FROM service_categories
     ORDER BY id ASC"
);

$categories = $categories_result
    ? $categories_result->fetch_all(MYSQLI_ASSOC)
    : [];

// Common header
include 'includes/admin_header.php';
?>

<div class="admin-layout">

    <?php include 'includes/admin_sidebar.php'; ?>

    <main class="admin-main">

        <div class="admin-topbar">

            <h1>Edit Service</h1>

            <a
                href="services.php"
                class="action-btn secondary"
            >
                &larr; Back to Services
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
                    Service #<?php echo (int)$service['id']; ?>
                </h2>


                <form
                    method="POST"
                    action="edit-service.php?id=<?php echo (int)$service_id; ?>"
                >

                    <div class="form-grid">

                        <!-- Title -->
                        <div class="form-group full">

                            <label class="form-label">
                                Service Title *
                            </label>

                            <input
                                type="text"
                                name="title"
                                class="form-control"
                                required
                                value="<?php echo htmlspecialchars($service['title']); ?>"
                            >

                        </div>


                        <!-- Category -->
                        <div class="form-group">

                            <label class="form-label">
                                Category *
                            </label>

                            <select
                                name="category_id"
                                class="form-control"
                                required
                            >

                                <?php foreach ($categories as $cat): ?>

                                    <option
                                        value="<?php echo (int)$cat['id']; ?>"
                                        <?php echo ($cat['id'] == $service['category_id']) ? 'selected' : ''; ?>
                                    >
                                        <?php echo htmlspecialchars($cat['name']); ?>
                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>


                        <!-- Status -->
                        <div class="form-group">

                            <label class="form-label">
                                Status
                            </label>

                            <select
                                name="status"
                                class="form-control"
                            >

                                <option
                                    value="active"
                                    <?php echo $service['status'] === 'active' ? 'selected' : ''; ?>
                                >
                                    Active
                                </option>

                                <option
                                    value="inactive"
                                    <?php echo $service['status'] === 'inactive' ? 'selected' : ''; ?>
                                >
                                    Inactive
                                </option>

                            </select>

                        </div>


                        <!-- Short Description -->
                        <div class="form-group full">

                            <label class="form-label">
                                Short Description
                            </label>

                            <input
                                type="text"
                                name="short_description"
                                class="form-control"
                                value="<?php echo htmlspecialchars($service['short_description'] ?? ''); ?>"
                            >

                        </div>


                        <!-- Full Description -->
                        <div class="form-group full">

                            <label class="form-label">
                                Full Description
                            </label>

                            <textarea
                                name="full_description"
                                class="form-control"
                                rows="5"
                            ><?php echo htmlspecialchars($service['full_description'] ?? ''); ?></textarea>

                        </div>

                    </div>


                    <div class="form-actions">

                        <button
                            type="submit"
                            name="save_service"
                            class="action-btn primary"
                        >
                            Save Changes
                        </button>

                        <a
                            href="services.php"
                            class="action-btn secondary"
                        >
                            Cancel
                        </a>

                    </div>

                </form>

            </div>

        </div>

    </main>

</div>

</body>
</html>