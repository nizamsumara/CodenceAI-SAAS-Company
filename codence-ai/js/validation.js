// js/validation.js
// Client-side form validation.
// Server-side PHP validation remains the real security layer.

document.addEventListener('DOMContentLoaded', () => {


    /* =========================================================
       SIGN UP VALIDATION
    ========================================================= */

    const signUpForm = document.getElementById('signUpForm');

    if (signUpForm) {

        signUpForm.addEventListener('submit', (event) => {

            const passwordInput =
                document.getElementById('signUpPassword');

            if (!passwordInput) {
                return;
            }

            if (passwordInput.value.length < 8) {

                event.preventDefault();

                alert(
                    'Password must be at least 8 characters long.'
                );

                passwordInput.focus();
            }
        });
    }


    /* =========================================================
       SIGN IN VALIDATION
    ========================================================= */

    const signInForm = document.getElementById('signInForm');

    if (signInForm) {

        signInForm.addEventListener('submit', (event) => {

            const email =
                document.getElementById('signInEmail');

            const password =
                document.getElementById('signInPassword');


            if (!email || !password) {
                return;
            }


            if (
                !email.value.trim() ||
                !password.value.trim()
            ) {

                event.preventDefault();

                alert(
                    'Please fill in both email and password.'
                );

                if (!email.value.trim()) {
                    email.focus();
                } else {
                    password.focus();
                }
            }
        });
    }


    /* =========================================================
       CONTACT FORM VALIDATION
    ========================================================= */

    const contactForm =
        document.getElementById('contactInquiryForm');

    if (contactForm) {

        contactForm.addEventListener('submit', (event) => {

            const name =
                document.getElementById('contactFullName');

            const email =
                document.getElementById('contactEmail');

            const message =
                document.getElementById('contactAssist');


            if (!name || !email || !message) {
                return;
            }


            if (
                !name.value.trim() ||
                !email.value.trim() ||
                !message.value.trim()
            ) {

                event.preventDefault();

                alert(
                    'Please fill in your name, email, and message.'
                );

                if (!name.value.trim()) {
                    name.focus();
                } else if (!email.value.trim()) {
                    email.focus();
                } else {
                    message.focus();
                }
            }
        });
    }


    /* =========================================================
       EMAIL FORMAT CHECK
    ========================================================= */

    const emailInputs =
        document.querySelectorAll('input[type="email"]');

    const emailPattern =
        /^[^\s@]+@[^\s@]+\.[^\s@]+$/;


    emailInputs.forEach(input => {

        input.addEventListener('blur', () => {

            const value = input.value.trim();


            if (value && !emailPattern.test(value)) {

                input.style.borderColor = '#dc2626';

            } else {

                input.style.borderColor = '';
            }
        });


        /* Remove error styling while typing */

        input.addEventListener('input', () => {

            input.style.borderColor = '';
        });
    });

});