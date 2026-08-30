<?php

$signin_error = "";
$signup_error = "";
$signup_success = "";

// Get messages from session
if (isset($_SESSION['signin_error'])) {
    $signin_error = $_SESSION['signin_error'];
    unset($_SESSION['signin_error']);
}

if (isset($_SESSION['signup_error'])) {
    $signup_error = $_SESSION['signup_error'];
    unset($_SESSION['signup_error']);
}

if (isset($_SESSION['signup_success'])) {
    $signup_success = $_SESSION['signup_success'];
    unset($_SESSION['signup_success']);
}

?>

<div class="auth-modal-overlay" id="authModalOverlay">

    <div class="auth-modal-wrapper">

        <button type="button"
                class="auth-modal-close"
                id="authModalClose"
                aria-label="Close modal">
            &times;
        </button>


        <!-- Sign In -->

        <div class="auth-view" id="signInView">

            <div class="auth-view-header">

                <span class="auth-view-tagline">
                    AUTHENTICATION
                </span>

                <h2 class="auth-view-title">
                    Sign In
                </h2>

                <p class="auth-view-subtitle">
                    Access your consultations, solution dashboard, and architecture blueprints.
                </p>

            </div>


            <form id="signInForm"
                  class="auth-form"
                  method="POST"
                  action="login.php">

                <?php if ($signin_error != "") { ?>

                    <div class="auth-alert error">
                        <?php echo htmlspecialchars($signin_error); ?>
                    </div>

                <?php } ?>


                <div class="auth-form-group">

                    <label for="signInEmail" class="auth-form-label">
                        EMAIL ADDRESS
                    </label>

                    <input type="email"
                           id="signInEmail"
                           name="email"
                           class="auth-form-input"
                           placeholder="name@company.com"
                           required
                           autocomplete="email"
                           value="<?php
                               if (isset($_COOKIE['remember_email'])) {
                                   echo htmlspecialchars($_COOKIE['remember_email']);
                               }
                           ?>">

                </div>


                <div class="auth-form-group">

                    <label for="signInPassword" class="auth-form-label">
                        PASSWORD
                    </label>

                    <input type="password"
                           id="signInPassword"
                           name="password"
                           class="auth-form-input"
                           placeholder="••••••••"
                           required
                           autocomplete="current-password">

                </div>


                <!-- Remember Me -->

                <div class="auth-form-group">

                    <input type="checkbox"
                           id="rememberMe"
                           name="remember_me"
                           value="1"
                           <?php
                           if (isset($_COOKIE['remember_email'])) {
                               echo "checked";
                           }
                           ?>>

                    <label for="rememberMe">
                        Remember Me
                    </label>

                </div>


                <button type="submit"
                        class="auth-submit-btn"
                        id="signInSubmitBtn">

                    <span>
                        SIGN IN &rarr;
                    </span>

                </button>


                <div class="auth-toggle-footer">

                    <span>
                        Don't have an account?
                    </span>

                    <button type="button"
                            class="auth-toggle-btn"
                            id="switchToSignUp">
                        Create Account
                    </button>

                </div>

            </form>

        </div>



        <!-- Sign Up -->

        <div class="auth-view"
             id="signUpView"
             style="display: none;">

            <div class="auth-view-header">

                <span class="auth-view-tagline">
                    GET STARTED
                </span>

                <h2 class="auth-view-title">
                    Create Account
                </h2>

                <p class="auth-view-subtitle">
                    Join Codence AI to schedule enterprise architectural consultations.
                </p>

            </div>


            <form id="signUpForm"
                  class="auth-form"
                  method="POST"
                  action="signup.php">

                <?php if ($signup_error != "") { ?>

                    <div class="auth-alert error">
                        <?php echo htmlspecialchars($signup_error); ?>
                    </div>

                <?php } ?>


                <?php if ($signup_success != "") { ?>

                    <div class="auth-alert success">
                        <?php echo htmlspecialchars($signup_success); ?>
                    </div>

                <?php } ?>


                <div class="auth-form-group">

                    <label for="signUpFullName" class="auth-form-label">
                        FULL NAME
                    </label>

                    <input type="text"
                           id="signUpFullName"
                           name="full_name"
                           class="auth-form-input"
                           placeholder="Adrian Sterling"
                           required
                           autocomplete="name">

                </div>


                <div class="auth-form-group">

                    <label for="signUpEmail" class="auth-form-label">
                        CORPORATE EMAIL
                    </label>

                    <input type="email"
                           id="signUpEmail"
                           name="email"
                           class="auth-form-input"
                           placeholder="name@company.com"
                           required
                           autocomplete="email">

                </div>


                <div class="auth-form-group">

                    <label for="signUpPhone" class="auth-form-label">
                        PHONE NUMBER (OPTIONAL)
                    </label>

                    <input type="tel"
                           id="signUpPhone"
                           name="phone"
                           class="auth-form-input"
                           placeholder="+91 00000 00000"
                           autocomplete="tel">

                </div>


                <div class="auth-form-group">

                    <label for="signUpPassword" class="auth-form-label">
                        PASSWORD
                    </label>

                    <input type="password"
                           id="signUpPassword"
                           name="password"
                           class="auth-form-input"
                           placeholder="Minimum 8 characters"
                           required
                           autocomplete="new-password">

                </div>


                <button type="submit"
                        class="auth-submit-btn"
                        id="signUpSubmitBtn">

                    <span>
                        CREATE ACCOUNT &rarr;
                    </span>

                </button>


                <div class="auth-toggle-footer">

                    <span>
                        Already have an account?
                    </span>

                    <button type="button"
                            class="auth-toggle-btn"
                            id="switchToSignIn">
                        Sign In
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
