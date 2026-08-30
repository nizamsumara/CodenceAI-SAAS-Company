// js/script.js
// General site-wide JavaScript.

document.addEventListener('DOMContentLoaded', () => {

    /* =========================================================
       AUTO-HIDE SUCCESS ALERTS
    ========================================================= */

    const alerts = document.querySelectorAll(
        '.auth-alert.success, .admin-alert.success'
    );

    alerts.forEach(alertBox => {

        setTimeout(() => {

            alertBox.style.transition = 'opacity 0.4s ease';
            alertBox.style.opacity = '0';

            setTimeout(() => {
                alertBox.style.display = 'none';
            }, 400);

        }, 4000);
    });


    /* =========================================================
       SMOOTH SCROLL
    ========================================================= */

    const anchorLinks = document.querySelectorAll(
        'a[href^="#"]'
    );

    anchorLinks.forEach(anchor => {

        anchor.addEventListener('click', (event) => {

            const targetId = anchor.getAttribute('href');

            if (!targetId || targetId === '#') {
                return;
            }

            const target = document.querySelector(targetId);

            if (!target) {
                return;
            }

            event.preventDefault();

            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        });
    });

});