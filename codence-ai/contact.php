<?php

session_start();

// Get messages
$contact_error = "";
$contact_success = "";

if (isset($_SESSION['contact_error'])) {
    $contact_error = $_SESSION['contact_error'];
    unset($_SESSION['contact_error']);
}

if (isset($_SESSION['contact_success'])) {
    $contact_success = $_SESSION['contact_success'];
    unset($_SESSION['contact_success']);
}

include 'includes/navbar.php';

?>

<link rel="stylesheet" href="css/contact.css">


<section class="contact-section">

    <div class="contact-container">

        <!-- Messages -->

        <?php if (!empty($contact_success)): ?>

            <div class="contact-message success">
                <?php echo htmlspecialchars($contact_success); ?>
            </div>

        <?php endif; ?>


        <?php if (!empty($contact_error)): ?>

            <div class="contact-message error">
                <?php echo htmlspecialchars($contact_error); ?>
            </div>

        <?php endif; ?>


        <!-- Contact Form -->

        <div class="contact-form-panel">

            <div class="contact-header">

                <span class="contact-tagline">
                    INQUIRY FORM
                </span>

                <h1 class="contact-title">
                    Let's Build Together
                </h1>

                <p class="contact-subtitle">
                    Reach out today and let's build something exceptional together.
                </p>

            </div>


            <form
                action="process-contact.php"
                method="POST"
                class="contact-form"
            >

                <!-- Full Name -->

                <div class="form-group">

                    <label for="full_name">
                        FULL NAME
                    </label>

                    <input
                        type="text"
                        id="full_name"
                        name="full_name"
                        placeholder="Enter your full name"
                        value="<?php
                        if (isset($_SESSION['user_name'])) {
                            echo htmlspecialchars($_SESSION['user_name']);
                        }
                        ?>"
                        required
                    >

                </div>


                <!-- Organization -->

                <div class="form-group">

                    <label for="organization">
                        ORGANIZATION
                    </label>

                    <input
                        type="text"
                        id="organization"
                        name="organization"
                        placeholder="Enter your organization"
                    >

                </div>


                <!-- Email -->

                <div class="form-group">

                    <label for="email">
                        EMAIL ADDRESS
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Enter your email"
                        required
                    >

                </div>


                <!-- Message -->

                <div class="form-group">

                    <label for="message">
                        HOW CAN WE ASSIST?
                    </label>

                    <textarea
                        id="message"
                        name="message"
                        rows="5"
                        placeholder="Describe your inquiry or requirement..."
                        required
                    ></textarea>

                </div>


                <!-- Submit -->

                <div class="contact-submit">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        SEND MESSAGE →
                    </button>

                    <span>
                        TYPICAL RESPONSE: 24 BUSINESS HOURS
                    </span>

                </div>

            </form>

        </div>


        <!-- Social Links -->

        <div class="contact-social">

            <span class="social-title">
                GET IN TOUCH
            </span>

            <div class="social-links">

                <a
                    href="https://instagram.com/codence.ai"
                    target="_blank"
                >
                    <img
                        src="images/icon-1.png"
                        alt="Instagram"
                    >
                </a>


                <a
                    href="https://wa.me/919773024265"
                    target="_blank"
                >
                    <img
                        src="images/icon-2.png"
                        alt="WhatsApp"
                    >
                </a>


                <a
                    href="https://youtube.com/@codencetech"
                    target="_blank"
                >
                    <img
                        src="images/icon-3.png"
                        alt="YouTube"
                    >
                </a>


                <a
                    href="https://linkedin.com/in/nizam-sumara"
                    target="_blank"
                >
                    <img
                        src="images/icon-4.png"
                        alt="LinkedIn"
                    >
                </a>


                <a href="mailto:alqadiragency@gmail.com">

                    <img
                        src="images/icon-5.png"
                        alt="Email"
                    >

                </a>

            </div>

        </div>

    </div>

</section>


<?php include 'includes/footer.php'; ?>