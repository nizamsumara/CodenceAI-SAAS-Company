// js/modal.js
// Handles booking modal, calendar, time slots,
// and authentication modal.

document.addEventListener('DOMContentLoaded', () => {

    /* =========================================================
       BOOKING MODAL
    ========================================================= */

    const bookingModal = document.getElementById('bookingModal');
    const closeBookingModal = document.getElementById('closeBookingModal');
    const bookingForm = document.getElementById('bookingForm');

    const bookingServiceSelect = document.getElementById('bookingService');
    const otherServiceWrapper = document.getElementById('otherServiceWrapper');
    const bookingOtherServiceInput = document.getElementById('bookingOtherService');

    const openBookingModal = () => {
        if (!bookingModal) return;

        bookingModal.classList.add('active');
        document.body.style.overflow = 'hidden';
    };

    const closeBookingModalHandler = () => {
        if (!bookingModal) return;

        bookingModal.classList.remove('active');
        document.body.style.overflow = '';
    };


    /* Close button */

    if (closeBookingModal) {
        closeBookingModal.addEventListener('click', closeBookingModalHandler);
    }


    /* Close when clicking outside modal */

    if (bookingModal) {
        bookingModal.addEventListener('click', (event) => {
            if (event.target === bookingModal) {
                closeBookingModalHandler();
            }
        });
    }


    /* Other service field */

    if (bookingServiceSelect && otherServiceWrapper && bookingOtherServiceInput) {

        bookingServiceSelect.addEventListener('change', () => {

            const isOther = bookingServiceSelect.value === 'other';

            otherServiceWrapper.classList.toggle('field-hidden', !isOther);

            if (isOther) {
                bookingOtherServiceInput.setAttribute('required', 'required');
            } else {
                bookingOtherServiceInput.removeAttribute('required');
                bookingOtherServiceInput.value = '';
            }
        });
    }


    /* =========================================================
       CALENDAR
    ========================================================= */

    const calendarMonthYear = document.getElementById('calendarMonthYear');
    const calendarDaysGrid = document.getElementById('calendarDaysGrid');

    const selectedDateInput = document.getElementById('selectedDateInput');

    const prevMonthBtn = document.getElementById('prevMonth');
    const nextMonthBtn = document.getElementById('nextMonth');

    let currentDate = new Date();
    let selectedDate = null;

    const monthNames = [
        'January',
        'February',
        'March',
        'April',
        'May',
        'June',
        'July',
        'August',
        'September',
        'October',
        'November',
        'December'
    ];


    const renderCalendar = () => {

        if (!calendarDaysGrid || !calendarMonthYear) return;

        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();

        calendarMonthYear.textContent = `${monthNames[month]} ${year}`;

        calendarDaysGrid.innerHTML = '';


        /* First day of month */

        const firstDay = new Date(year, month, 1).getDay();

        /*
         * Convert Sunday = 0 to Monday = 0.
         */
        const startingDayIndex = firstDay === 0 ? 6 : firstDay - 1;

        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const daysInPreviousMonth = new Date(year, month, 0).getDate();


        /* Previous month's days */

        for (let i = startingDayIndex; i > 0; i--) {

            const dayElement = document.createElement('div');

            dayElement.className = 'cal-day other-month disabled';
            dayElement.textContent = daysInPreviousMonth - i + 1;

            calendarDaysGrid.appendChild(dayElement);
        }


        /* Today's date */

        const today = new Date();
        today.setHours(0, 0, 0, 0);


        /* Current month's days */

        for (let day = 1; day <= daysInMonth; day++) {

            const dayElement = document.createElement('div');

            const date = new Date(year, month, day);
            date.setHours(0, 0, 0, 0);

            dayElement.className = 'cal-day';
            dayElement.textContent = String(day).padStart(2, '0');


            /* Disable past dates */

            if (date < today) {

                dayElement.classList.add('disabled');

            } else {

                /* Restore selected date */

                if (
                    selectedDate &&
                    date.getTime() === selectedDate.getTime()
                ) {
                    dayElement.classList.add('selected');
                }


                /* Select date */

                dayElement.addEventListener('click', () => {

                    document
                        .querySelectorAll('.cal-day.selected')
                        .forEach(day => {
                            day.classList.remove('selected');
                        });

                    dayElement.classList.add('selected');

                    selectedDate = date;

                    if (selectedDateInput) {

                        const formattedDate =
                            `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

                        selectedDateInput.value = formattedDate;
                    }
                });
            }

            calendarDaysGrid.appendChild(dayElement);
        }
    };


    /* Render calendar */

    if (calendarDaysGrid) {
        renderCalendar();
    }


    /* Previous month */

    if (prevMonthBtn) {

        prevMonthBtn.addEventListener('click', () => {

            currentDate.setMonth(currentDate.getMonth() - 1);

            renderCalendar();
        });
    }


    /* Next month */

    if (nextMonthBtn) {

        nextMonthBtn.addEventListener('click', () => {

            currentDate.setMonth(currentDate.getMonth() + 1);

            renderCalendar();
        });
    }


    /* =========================================================
       TIME SLOTS
    ========================================================= */

    const timeSlotButtons = document.querySelectorAll('.time-slot-btn');
    const selectedTimeInput = document.getElementById('selectedTimeInput');

    timeSlotButtons.forEach(button => {

        button.addEventListener('click', () => {

            timeSlotButtons.forEach(slot => {
                slot.classList.remove('selected');
            });

            button.classList.add('selected');

            if (selectedTimeInput) {
                selectedTimeInput.value =
                    button.getAttribute('data-time') || '';
            }
        });
    });


    /* =========================================================
       BOOKING FORM VALIDATION
    ========================================================= */

    if (bookingForm) {

        bookingForm.addEventListener('submit', (event) => {

            if (selectedDateInput && !selectedDateInput.value) {

                event.preventDefault();

                alert('Please select a date from the calendar.');

                return;
            }


            if (selectedTimeInput && !selectedTimeInput.value) {

                event.preventDefault();

                alert('Please select a preferred time slot.');

                return;
            }
        });
    }


    /* =========================================================
       AUTHENTICATION MODAL
    ========================================================= */

    const authOverlay = document.getElementById('authModalOverlay');
    const authCloseBtn = document.getElementById('authModalClose');

    const signInView = document.getElementById('signInView');
    const signUpView = document.getElementById('signUpView');

    const switchToSignUp = document.getElementById('switchToSignUp');
    const switchToSignIn = document.getElementById('switchToSignIn');


    const showAuthView = (view) => {

        if (!authOverlay || !signInView || !signUpView) return;

        if (view === 'signup') {

            signInView.style.display = 'none';
            signUpView.style.display = 'block';

        } else {

            signUpView.style.display = 'none';
            signInView.style.display = 'block';
        }

        authOverlay.style.display = 'flex';

        requestAnimationFrame(() => {
            authOverlay.classList.add('active');
        });

        document.body.style.overflow = 'hidden';
    };


    /* Global functions used by navbar/buttons */

    window.openSignInModal = () => {
        showAuthView('signin');
    };


    window.openSignUpModal = () => {
        showAuthView('signup');
    };


    window.closeAuthModal = () => {

        if (!authOverlay) return;

        authOverlay.classList.remove('active');

        setTimeout(() => {

            authOverlay.style.display = 'none';
            document.body.style.overflow = '';

        }, 300);
    };


    /* Auth close button */

    if (authCloseBtn) {

        authCloseBtn.addEventListener('click', () => {
            window.closeAuthModal();
        });
    }


    /* Click outside auth modal */

    if (authOverlay) {

        authOverlay.addEventListener('click', (event) => {

            if (event.target === authOverlay) {
                window.closeAuthModal();
            }
        });
    }


    /* Switch Sign In → Sign Up */

    if (switchToSignUp) {

        switchToSignUp.addEventListener('click', () => {
            showAuthView('signup');
        });
    }


    /* Switch Sign Up → Sign In */

    if (switchToSignIn) {

        switchToSignIn.addEventListener('click', () => {
            showAuthView('signin');
        });
    }


    /* =========================================================
       GLOBAL MODAL TRIGGERS
    ========================================================= */

    document.addEventListener('click', (event) => {

        const bookingTrigger =
            event.target.closest('.trigger-booking');

        if (bookingTrigger) {

            event.preventDefault();

            openBookingModal();

            return;
        }


        const signInTrigger =
            event.target.closest(
                '.trigger-signin, [data-auth-trigger="signin"]'
            );

        if (signInTrigger) {

            event.preventDefault();

            window.openSignInModal();

            return;
        }


        const signUpTrigger =
            event.target.closest(
                '.trigger-signup, [data-auth-trigger="signup"]'
            );

        if (signUpTrigger) {

            event.preventDefault();

            window.openSignUpModal();
        }
    });

});
