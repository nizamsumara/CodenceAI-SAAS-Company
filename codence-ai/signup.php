<?php

session_start();

include "includes/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $full_name = $_POST["full_name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $password = $_POST["password"];


    // Check required fields

    if (empty($full_name) || empty($email) || empty($password)) {

        $_SESSION["signup_error"] =
            "Full name, email and password are required.";

        header("Location: index.php");
        exit();

    }


    // Check email format

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $_SESSION["signup_error"] =
            "Please enter a valid email address.";

        header("Location: index.php");
        exit();

    }


    // Check password length

    if (strlen($password) < 8) {

        $_SESSION["signup_error"] =
            "Password must be at least 8 characters.";

        header("Location: index.php");
        exit();

    }


    // Check if email already exists

    $sql = "SELECT * FROM users WHERE email='$email'";

    $result = mysqli_query($conn, $sql);


    if (mysqli_num_rows($result) > 0) {

        $_SESSION["signup_error"] =
            "This email address is already registered.";

        header("Location: index.php");
        exit();

    }


    // Hash password

    $hashed_password = password_hash(
        $password,
        PASSWORD_DEFAULT
    );


    // Insert user

    $sql = "INSERT INTO users
            (full_name, email, phone, password, role)
            VALUES
            ('$full_name', '$email', '$phone', '$hashed_password', 'user')";


    if (mysqli_query($conn, $sql)) {

        // Get newly created user ID
        $user_id = mysqli_insert_id($conn);


        // Create session

        $_SESSION["user_id"] = $user_id;
        $_SESSION["user_name"] = $full_name;
        $_SESSION["user_role"] = "user";


        // Success message

        $_SESSION["signup_success"] =
            "Account created successfully.";


        header("Location: profile.php");
        exit();

    } else {

        $_SESSION["signup_error"] =
            "Registration failed. Please try again.";

        header("Location: index.php");
        exit();

    }

}

?>