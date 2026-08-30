<?php

session_start();

include 'includes/db.php';


// Only allow POST request

if ($_SERVER["REQUEST_METHOD"] != "POST") {

    header("Location: contact.php");
    exit;

}


// Get form data

$full_name = trim($_POST["full_name"]);
$organization = trim($_POST["organization"]);
$email = trim($_POST["email"]);
$message = trim($_POST["message"]);


// Validate required fields

if (empty($full_name) || empty($email) || empty($message)) {

    $_SESSION["contact_error"] = "Name, email and message are required.";

    header("Location: contact.php");
    exit;
}


// Validate email

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    $_SESSION["contact_error"] = "Please enter a valid email address.";
    header("Location: contact.php");
    exit;
}


// Insert contact message

$sql = "INSERT INTO contacts
        (full_name, organization, email, message, status)
        VALUES
        ('$full_name', '$organization', '$email', '$message', 'unread')";


$result = mysqli_query($conn, $sql);


// Check result

if ($result) {

    $_SESSION["contact_success"] =
        "Thank you! Your message has been sent successfully.";

} else {

    $_SESSION["contact_error"] =
        "Failed to send your message. Please try again.";

}


// Redirect back to contact page

header("Location: contact.php");
exit;

?>