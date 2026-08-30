<?php

session_start();

include "includes/db.php";


// Check user login
if (!isset($_SESSION["user_id"])) {

    $_SESSION["signin_error"] = "Please login to view your profile.";

    header("Location: index.php");
    exit();
}


$user_id = $_SESSION["user_id"];


// Get user information
$sql = "SELECT * FROM users WHERE id = $user_id";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 1) {

    $user = mysqli_fetch_assoc($result);

} else {

    session_destroy();

    header("Location: index.php");
    exit();
}


// Get user's bookings
$sql = "SELECT * FROM bookings
        WHERE user_id = $user_id
        ORDER BY created_at DESC";

$booking_result = mysqli_query($conn, $sql);


// Welcome message
$welcome_message = "";

if (isset($_GET["welcome"])) {
    $welcome_message = "Welcome to Codence AI! Your account has been created successfully.";
}


// Booking success message
$booking_message = "";

if (isset($_GET["booking"]) && $_GET["booking"] == "success") {

    if (isset($_SESSION["booking_success"])) {
        $booking_message = $_SESSION["booking_success"];
    } else {
        $booking_message = "Your booking was submitted successfully.";
    }

    unset($_SESSION["booking_success"]);
}


include "includes/navbar.php";

?>


<style>

.profile-section {
    padding: 5rem 4rem;
    background-color: var(--background);
    min-height: 70vh;
}

.profile-container {
    max-width: 1100px;
    margin: auto;
}

.profile-header {
    display: flex;
    align-items: center;
    gap: 2rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid var(--border-divider);
    margin-bottom: 3rem;
}

.profile-avatar {
    width: 72px;
    height: 72px;
    background-color: var(--accent-brand);
    border-radius: 50%;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 28px;
    font-weight: 800;
    color: white;
}

.profile-name {
    font-size: 28px;
    font-weight: 800;
    color: var(--text-primary);
    margin: 0 0 5px 0;
}

.profile-email {
    font-size: 14px;
    color: var(--text-muted);
    margin: 0;
}

.profile-role {
    display: inline-block;
    margin-top: 8px;
    padding: 5px 12px;

    background: var(--accent-brand);
    color: white;

    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
}


/* Messages */

.alert-box {
    padding: 1rem 1.5rem;
    margin-bottom: 2rem;
    font-size: 14px;
}

.alert-success {
    background: #f0fdf4;
    color: #166534;
    border: 1px solid #bbf7d0;
}


/* Account Information */

.section-title {
    font-size: 22px;
    font-weight: 800;
    color: var(--text-primary);

    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;

    border-bottom: 1px solid var(--border-divider);
}

.profile-info-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;

    margin-bottom: 3rem;
}

.info-card {
    background: var(--surface);
    border: 1px solid var(--border-divider);
    padding: 1.5rem;
}

.info-card-label {
    display: block;

    font-size: 10px;
    font-weight: 800;

    letter-spacing: 0.12em;

    color: var(--text-muted);

    text-transform: uppercase;

    margin-bottom: 0.5rem;
}

.info-card-value {
    font-size: 16px;
    font-weight: 700;
    color: var(--text-primary);
}


/* Booking Table */

.bookings-table {
    width: 100%;
    border-collapse: collapse;

    font-size: 14px;
}

.bookings-table th {
    text-align: left;

    padding: 0.85rem 1rem;

    font-size: 10px;
    font-weight: 800;

    text-transform: uppercase;

    color: var(--text-muted);

    border-bottom: 2px solid var(--border-divider);

    background: #fafafa;
}

.bookings-table td {
    padding: 1rem;

    border-bottom: 1px solid var(--border-divider);

    color: var(--text-primary);
}


/* Status */

.status-badge {
    display: inline-block;

    padding: 5px 10px;

    font-size: 10px;
    font-weight: 800;

    text-transform: uppercase;
}

.pending {
    background: #fef9c3;
    color: #854d0e;
}

.confirmed {
    background: #dcfce7;
    color: #166534;
}

.completed {
    background: #dbeafe;
    color: #1e40af;
}

.cancel {
    background: #fee2e2;
    color: #991b1b;
}


/* Empty bookings */

.empty-state {
    text-align: center;

    padding: 4rem 2rem;

    color: var(--text-muted);

    border: 1px dashed var(--border-divider);
}

.empty-state h3 {
    font-size: 18px;
    color: var(--text-primary);

    margin-bottom: 0.5rem;
}


/* Bottom buttons */

.profile-actions {
    display: flex;
    gap: 1rem;

    padding-top: 1.5rem;

    margin-top: 3rem;

    border-top: 1px solid var(--border-divider);
}

</style>


<section class="profile-section">

    <div class="profile-container">


        <!-- Success Messages -->

        <?php if (!empty($welcome_message)) { ?>

            <div class="alert-box alert-success">
                <?php echo htmlspecialchars($welcome_message); ?>
            </div>

        <?php } ?>


        <?php if (!empty($booking_message)) { ?>

            <div class="alert-box alert-success">
                <?php echo htmlspecialchars($booking_message); ?>
            </div>

        <?php } ?>


        <!-- Profile Header -->

        <div class="profile-header">

            <div class="profile-avatar">

                <?php
                echo strtoupper(substr($user["full_name"], 0, 1));
                ?>

            </div>


            <div>

                <h1 class="profile-name">
                    <?php echo htmlspecialchars($user["full_name"]); ?>
                </h1>

                <p class="profile-email">
                    <?php echo htmlspecialchars($user["email"]); ?>
                </p>

                <span class="profile-role">
                    <?php echo htmlspecialchars($user["role"]); ?>
                </span>

            </div>

        </div>


        <!-- Account Information -->

        <h2 class="section-title">
            Account Information
        </h2>


        <div class="profile-info-grid">


            <div class="info-card">

                <span class="info-card-label">
                    Full Name
                </span>

                <div class="info-card-value">
                    <?php echo htmlspecialchars($user["full_name"]); ?>
                </div>

            </div>


            <div class="info-card">

                <span class="info-card-label">
                    Email Address
                </span>

                <div class="info-card-value">
                    <?php echo htmlspecialchars($user["email"]); ?>
                </div>

            </div>


            <div class="info-card">

                <span class="info-card-label">
                    Phone Number
                </span>

                <div class="info-card-value">

                    <?php

                    if (!empty($user["phone"])) {
                        echo htmlspecialchars($user["phone"]);
                    } else {
                        echo "Not provided";
                    }

                    ?>

                </div>

            </div>


            <div class="info-card">

                <span class="info-card-label">
                    Account Role
                </span>

                <div class="info-card-value">
                    <?php echo ucfirst($user["role"]); ?>
                </div>

            </div>


            <div class="info-card">

                <span class="info-card-label">
                    Member Since
                </span>

                <div class="info-card-value">

                    <?php
                    echo date("d M Y", strtotime($user["created_at"]));
                    ?>

                </div>

            </div>


            <div class="info-card">

                <span class="info-card-label">
                    Total Bookings
                </span>

                <div class="info-card-value">

                    <?php
                    echo mysqli_num_rows($booking_result);
                    ?>

                </div>

            </div>

        </div>


        <!-- My Bookings -->

        <h2 class="section-title">
            My Consultations
        </h2>


        <?php if (mysqli_num_rows($booking_result) == 0) { ?>


            <div class="empty-state">

                <h3>
                    No Bookings Yet
                </h3>

                <p>
                    You haven't scheduled any consultations yet.
                </p>

                <button class="btn btn-primary trigger-booking">
                    Schedule a Consultation
                </button>

            </div>


        <?php } else { ?>


            <table class="bookings-table">

                <thead>

                    <tr>

                        <th>#</th>
                        <th>Category</th>
                        <th>Service</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Booked On</th>

                    </tr>

                </thead>


                <tbody>


                <?php while ($booking = mysqli_fetch_assoc($booking_result)) { ?>

                    <tr>

                        <td>
                            <?php echo $booking["id"]; ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($booking["service_category"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($booking["sub_service"]); ?>
                        </td>

                        <td>
                            <?php
                            echo date(
                                "d M Y",
                                strtotime($booking["preferred_date"])
                            );
                            ?>
                        </td>

                        <td>
                            <?php
                            echo date(
                                "h:i A",
                                strtotime($booking["preferred_time"])
                            );
                            ?>
                        </td>

                        <td>

                            <span class="status-badge <?php echo $booking["status"]; ?>">

                                <?php
                                echo ucfirst($booking["status"]);
                                ?>

                            </span>

                        </td>

                        <td>
                            <?php
                            echo date(
                                "d M Y",
                                strtotime($booking["created_at"])
                            );
                            ?>
                        </td>

                    </tr>

                <?php } ?>


                </tbody>

            </table>


        <?php } ?>


        


            <a href="logout.php" class="btn btn-secondary">
                Sign Out
            </a>

        </div>


    </div>

</section>


<?php include "includes/footer.php"; ?>