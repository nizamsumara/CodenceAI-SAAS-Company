<?php

session_start();

include "includes/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];
    $password = $_POST["password"];

    // Check empty fields
    if (empty($email) || empty($password)) {

        $_SESSION["signin_error"] = "Email and password are required.";

        header("Location: index.php");
        exit();

    }


    // Find user
    $sql = "SELECT * FROM users WHERE email='$email'";

    $result = mysqli_query($conn, $sql);


    // Check user exists
    if (mysqli_num_rows($result) == 1) {

        $user = mysqli_fetch_assoc($result);


        // Check password
        if (password_verify($password, $user["password"])) {

            // Create session
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["user_name"] = $user["full_name"];
            $_SESSION["user_role"] = $user["role"];


            // Remember Me
            if (isset($_POST["remember_me"])) {

                setcookie(
                    "remember_email",
                    $user["email"],
                    time() + (30 * 24 * 60 * 60),
                    "/"
                );

            } else {

                // Delete cookie
                if (isset($_COOKIE["remember_email"])) {

                    setcookie(
                        "remember_email",
                        "",
                        time() - 3600,
                        "/"
                    );

                }

            }


            // Redirect according to role
            if ($user["role"] == "admin") {

                header("Location: admin/dashboard.php");
                exit();

            } else {

                header("Location: profile.php");
                exit();

            }

        } else {

            $_SESSION["signin_error"] = "Invalid email or password.";

            header("Location: index.php");
            exit();

        }

    } else {

        $_SESSION["signin_error"] = "Invalid email or password.";

        header("Location: index.php");
        exit();

    }

}

?>