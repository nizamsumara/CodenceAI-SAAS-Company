<?php

$booking_services = array();

$sql = "SELECT s.id, s.title, sc.name AS category_name
        FROM services s
        JOIN service_categories sc ON s.category_id = sc.id
        WHERE s.status = 'active'
        ORDER BY sc.id, s.id";

$result = mysqli_query($conn, $sql);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $booking_services[$row['category_name']][] = $row;
    }
}

$booking_error = "";
$booking_success = "";

if (isset($_SESSION['booking_error'])) {
    $booking_error = $_SESSION['booking_error'];
    unset($_SESSION['booking_error']);
}

if (isset($_SESSION['booking_success'])) {
    $booking_success = $_SESSION['booking_success'];
    unset($_SESSION['booking_success']);
}

?>

<div id="bookingModal" class="booking-modal-overlay">

    <div class="booking-modal-container">

        <button type="button"
                class="booking-modal-close"
                id="closeBookingModal"
                aria-label="Close Modal">
            &times;
        </button>

        <?php if ($booking_success != ""): ?>

            <div style="padding:4rem;text-align:center;">

                <div style="font-size:48px;margin-bottom:1rem;">
                    ✓
                </div>

                <h2 style="font-size:28px;font-weight:800;margin-bottom:1rem;">
                    Booking Confirmed!
                </h2>

                <p style="font-size:15px;line-height:1.6;">
                    <?php echo htmlspecialchars($booking_success); ?>
                </p>

                <button type="button"
                        class="btn btn-primary"
                        onclick="closeBookingModal()"
                        style="margin-top:2rem;">
                    Close
                </button>

            </div>

        <?php else: ?>

            <form id="bookingForm"
                  action="booking.php"
                  method="POST"
                  class="booking-form-grid">

                <!-- LEFT PANEL -->

                <div class="booking-left-panel">

                    <div class="booking-header">

                        <h2 class="booking-title">
                            Schedule<br>Consultation
                        </h2>

                        <p class="booking-subtitle">
                            Book a consultation today and explore innovative
                            solutions designed to help your business succeed.
                        </p>

                    </div>

                    <?php if ($booking_error != ""): ?>

                        <div class="auth-alert error"
                             style="margin-bottom:1rem;">
                            <?php echo htmlspecialchars($booking_error); ?>
                        </div>

                    <?php endif; ?>


                    <div class="booking-fields-stack">

                        <!-- Full Name -->

                        <div class="form-group">

                            <label for="bookingFullName"
                                   class="form-label">
                                Full Name
                            </label>

                            <input type="text"
                                   id="bookingFullName"
                                   name="full_name"
                                   class="form-input"
                                   placeholder="e.g. Adrian Sterling"
                                   value="<?php
                                   if (isset($_SESSION['user_name'])) {
                                       echo htmlspecialchars($_SESSION['user_name']);
                                   }
                                   ?>"
                                   required>

                        </div>


                        <!-- Email -->

                        <div class="form-group">

                            <label for="bookingEmail"
                                   class="form-label">
                                Business Email
                            </label>

                            <input type="email"
                                   id="bookingEmail"
                                   name="email"
                                   class="form-input"
                                   placeholder="adrian@codence.ai"
                                   required>

                        </div>


                        <!-- Phone -->

                        <div class="form-group">

                            <label for="bookingPhone"
                                   class="form-label">
                                Phone Number
                            </label>

                            <input type="tel"
                                   id="bookingPhone"
                                   name="phone"
                                   class="form-input"
                                   placeholder="+91 00000 00000"
                                   required>

                        </div>


                        <!-- Service -->

                        <div class="form-group">

                            <label for="bookingService"
                                   class="form-label">
                                Select Service
                            </label>

                            <select id="bookingService"
                                    name="service_id"
                                    class="form-select"
                                    required>

                                <option value="" disabled selected>
                                    Select a service...
                                </option>

                                <?php foreach ($booking_services as $category => $services): ?>

                                    <optgroup label="<?php echo htmlspecialchars($category); ?>">

                                        <?php foreach ($services as $service): ?>

                                            <option value="<?php echo $service['id']; ?>">
                                                <?php echo htmlspecialchars($service['title']); ?>
                                            </option>

                                        <?php endforeach; ?>

                                    </optgroup>

                                <?php endforeach; ?>

                                <option value="other">
                                    Other Service
                                </option>

                            </select>

                        </div>


                        <!-- Other Service -->

                        <div class="form-group field-hidden"
                             id="otherServiceWrapper">

                            <label for="bookingOtherService"
                                   class="form-label">
                                Specify Other Service
                            </label>

                            <input type="text"
                                   id="bookingOtherService"
                                   name="other_service"
                                   class="form-input"
                                   placeholder="Specify your custom requirements...">

                        </div>

                    </div>

                </div>


                <!-- RIGHT PANEL -->

                <div class="booking-right-panel">

                    <!-- Calendar -->

                    <div class="calendar-widget">

                        <div class="calendar-header">

                            <span class="calendar-month-year"
                                  id="calendarMonthYear">
                                Select Date
                            </span>

                            <div class="calendar-nav">

                                <button type="button"
                                        id="prevMonth"
                                        class="cal-nav-btn">
                                    &lt;
                                </button>

                                <button type="button"
                                        id="nextMonth"
                                        class="cal-nav-btn">
                                    &gt;
                                </button>

                            </div>

                        </div>


                        <div class="calendar-weekdays">
                            <span>MON</span>
                            <span>TUE</span>
                            <span>WED</span>
                            <span>THU</span>
                            <span>FRI</span>
                            <span>SAT</span>
                            <span>SUN</span>
                        </div>


                        <div class="calendar-days-grid"
                             id="calendarDaysGrid">
                        </div>


                        <input type="hidden"
                               id="selectedDateInput"
                               name="booking_date"
                               required>

                    </div>


                    <!-- Time -->

                    <div class="time-slots-wrapper">

                        <label class="form-label">
                            Preferred Time (EST)
                        </label>

                        <div class="time-slots-grid">

                            <button type="button"
                                    class="time-slot-btn"
                                    data-time="09:00:00">
                                09:00 AM - 11:00 AM
                            </button>

                            <button type="button"
                                    class="time-slot-btn"
                                    data-time="12:00:00">
                                12:00 PM - 02:00 PM
                            </button>

                            <button type="button"
                                    class="time-slot-btn"
                                    data-time="15:00:00">
                                03:00 PM - 05:00 PM
                            </button>

                            <button type="button"
                                    class="time-slot-btn"
                                    data-time="18:00:00">
                                06:00 PM - 07:00 PM
                            </button>

                        </div>

                        <input type="hidden"
                               id="selectedTimeInput"
                               name="booking_time"
                               required>

                    </div>


                    <!-- Notes -->

                    <div class="form-group">

                        <label for="bookingNotes"
                               class="form-label">
                            Technical Project Requirements
                        </label>

                        <textarea id="bookingNotes"
                                  name="notes"
                                  class="form-textarea"
                                  rows="3"
                                  placeholder="Describe your current tech stack, scale requirements, and primary AI objectives..."></textarea>

                    </div>


                    <!-- Submit -->

                    <div class="booking-action-row">

                        <p class="cancellation-notice">
                            Cancellations require 24h notice.
                        </p>

                        <?php if (isset($_SESSION['user_id'])): ?>

                            <button type="submit"
                                    class="btn btn-primary btn-confirm-booking">
                                CONFIRM BOOKING
                            </button>

                        <?php else: ?>

                            <button type="button"
                                    class="btn btn-primary btn-confirm-booking"
                                    onclick="closeBookingAndOpenAuth()">
                                SIGN IN TO BOOK &rarr;
                            </button>

                        <?php endif; ?>

                    </div>

                </div>

            </form>

        <?php endif; ?>

    </div>

</div>


<script>

function closeBookingAndOpenAuth()
{
    var bookingModal = document.getElementById("bookingModal");

    if (bookingModal) {
        bookingModal.classList.remove("active");
        document.body.style.overflow = "";
    }

    setTimeout(function() {

        if (typeof openSignInModal === "function") {
            openSignInModal();
        }

    }, 300);
}


function closeBookingModal()
{
    var bookingModal = document.getElementById("bookingModal");

    if (bookingModal) {
        bookingModal.classList.remove("active");
        document.body.style.overflow = "";
    }
}

</script>