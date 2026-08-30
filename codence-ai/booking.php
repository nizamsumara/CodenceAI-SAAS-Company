<?php

session_start();

include "includes/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check user login
    if (!isset($_SESSION["user_id"])) {
        $_SESSION["booking_error"] = "Please login first to book a service.";
        header("Location: index.php");
        exit();
    }

    $user_id = $_SESSION["user_id"];

    $full_name = trim($_POST["full_name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);

    $service_id = $_POST["service_id"];

    $other_service = trim($_POST["other_service"]);

    $booking_date = $_POST["booking_date"];
    $booking_time = $_POST["booking_time"];

    $notes = trim($_POST["notes"]);


    // Required field validation
    if (
        empty($full_name) ||
        empty($email) ||
        empty($phone) ||
        empty($booking_date) ||
        empty($booking_time)
    ) {

        $_SESSION["booking_error"] = "Please fill all required fields.";

        header("Location: index.php");
        exit();
    }


    // Email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $_SESSION["booking_error"] = "Please enter a valid email.";

        header("Location: index.php");
        exit();
    }


    // Check selected service
    $service_category = "Other";
    $sub_service = "Other Service";
    $db_service_id = "NULL";


    if ($service_id == "other") {

        if (!empty($other_service)) {
            $sub_service = $other_service;
        }

    } else {

        $service_id = (int)$service_id;

        $sql = "SELECT services.title, service_categories.name
                FROM services
                INNER JOIN service_categories
                ON services.category_id = service_categories.id
                WHERE services.id = $service_id";

        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 1) {

            $service = mysqli_fetch_assoc($result);

            $service_category = $service["name"];
            $sub_service = $service["title"];

            $db_service_id = $service_id;
        }
    }


    // Check date
    $today = date("Y-m-d");

    if ($booking_date < $today) {

        $_SESSION["booking_error"] = "Please select today or a future date.";

        header("Location: index.php");
        exit();
    }


    // Insert booking
    $sql = "INSERT INTO bookings
            (user_id, service_id, full_name, email, phone,
             service_category, sub_service,
             preferred_date, preferred_time, notes, status)

            VALUES
            (
                $user_id,
                $db_service_id,
                '$full_name',
                '$email',
                '$phone',
                '$service_category',
                '$sub_service',
                '$booking_date',
                '$booking_time',
                '$notes',
                'pending'
            )";


    if (mysqli_query($conn, $sql)) {

        $_SESSION["booking_success"] =
            "Your booking has been submitted successfully.";

        header("Location: profile.php");
        exit();

    } else {

        $_SESSION["booking_error"] =
            "Booking failed. Please try again.";

        header("Location: index.php");
        exit();
    }
}

?>