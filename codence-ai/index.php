<?php
include 'includes/navbar.php';
?>

<link rel="stylesheet" href="css/home.css">

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-container">

        <span class="hero-tagline">REDEFINING DIGITAL EFFICIENCY</span>

        <h1 class="hero-title">
            Build Smarter<br>
            <span class="hero-title-italic">Digital Experiences</span>
        </h1>

        <p class="hero-subtitle">
            We fuse elite architectural engineering with generative intelligence
            to deliver systems that don't just function—they anticipate.
            Precision software for the high-stakes enterprise.
        </p>

        <div class="hero-actions">

            <button class="btn btn-primary trigger-booking">
                Book 1:1 Call
                <svg width="16" height="16" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2.5"
                     stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </button>

            <a href="services.php" class="btn-text-link">
                Explore Services
            </a>

        </div>
    </div>
</section>


<!-- Stats Section -->
<section class="stats-marquee-section">
    <div class="stats-marquee-wrapper">

        <div class="stats-marquee-track">

            <div class="stat-box">
                <span class="stat-value">100%</span>
                <span class="stat-label">RESPONSIVE — ALL DEVICES</span>
            </div>

            <div class="stat-box">
                <span class="stat-value">50+</span>
                <span class="stat-label">PROJECT DELIVERED</span>
            </div>

            <div class="stat-box">
                <span class="stat-value">24ms</span>
                <span class="stat-label">AVG. LATENCY</span>
            </div>

            <div class="stat-box">
                <span class="stat-value">4.9/5</span>
                <span class="stat-label">PARTNER RATING</span>
            </div>

            <!-- Duplicate items for marquee animation -->
            <div class="stat-box">
                <span class="stat-value">100%</span>
                <span class="stat-label">RESPONSIVE — ALL DEVICES</span>
            </div>

            <div class="stat-box">
                <span class="stat-value">50+</span>
                <span class="stat-label">PROJECT DELIVERED</span>
            </div>

            <div class="stat-box">
                <span class="stat-value">24ms</span>
                <span class="stat-label">AVG. LATENCY</span>
            </div>

            <div class="stat-box">
                <span class="stat-value">4.9/5</span>
                <span class="stat-label">PARTNER RATING</span>
            </div>

        </div>
    </div>
</section>


<!-- Core Capabilities -->
<section class="capabilities-section">
    <div class="capabilities-container">

        <h2 class="capabilities-title">Core Capabilities</h2>

        <p class="capabilities-subtitle">
            Expertise spanning the entire AI lifecycle, from raw data architecture
            to user-facing refined intelligence layers.
        </p>

        <div class="capabilities-grid">

            <div class="capability-card card-wide">
                <h3 class="capability-card-title">
                    Agentic AI Workflows
                </h3>

                <p class="capability-card-desc">
                    We build self-correcting agents that handle complex business
                    logic, reducing operational overhead by up to 70%.
                </p>

                <div class="capability-tags">
                    <span class="capability-tag">LLM INTEGRATION</span>
                    <span class="capability-tag">RAG SYSTEMS</span>
                </div>
            </div>


            <div class="capability-card card-narrow">
                <h3 class="capability-card-title">
                    Web Application
                </h3>

                <p class="capability-card-desc">
                    We develop fast, secure, and scalable web applications
                    tailored to your business needs, delivering seamless user
                    experiences and long-term performance.
                </p>
            </div>

        </div>
    </div>
</section>


<!-- Testimonial -->
<section class="testimonial-hero-section">
    <div class="testimonial-hero-container">

        <div class="testimonial-content-side">

            <div class="testimonial-quote-icon">
                <svg width="48" height="38" viewBox="0 0 48 38"
                     fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M0 38V21.375C0 9.5 7.6 1.9 20.2667 0L22.8 5.06667C15.2 6.96667 11.4 11.0833 11.4 16.4667H21.5333V38H0ZM26.4667 38V21.375C26.4667 9.5 34.0667 1.9 46.7333 0L49.2667 5.06667C41.6667 6.96667 37.8667 11.0833 37.8667 16.4667H48V38H26.4667Z"
                        fill="#80E0D0"/>
                </svg>
            </div>

            <blockquote class="testimonial-quote-text">
                "Codence AI didn't just build a tool; they redefined how our
                executive team interacts with our internal Agents and Web Apps.
                The ROI was visible within the first fiscal quarter."
            </blockquote>

            <div class="testimonial-author-box">
                <h4 class="author-name">Ahmed Qadri</h4>
                <p class="author-title">CTO, NexusGlobal Systems</p>
            </div>

        </div>

        <div class="testimonial-image-side">
            <img src="images/logo-2.jpeg"
                 alt="NexusGlobal Systems Architecture"
                 class="testimonial-graphic">
        </div>

    </div>
</section>


<!-- CTA Section -->
<section class="home-cta-section">
    <div class="home-cta-container">

        <h2 class="home-cta-title">Ready to Elevate?</h2>

        <p class="home-cta-subtitle">
            Join the ranks of forward-thinking enterprises leveraging Codence AI
            to dominate their verticals. Limited partnership slots available for Q3.
        </p>

        <button class="btn btn-primary trigger-booking">
            Schedule Your Consultation
        </button>

    </div>
</section>


<?php
include 'includes/footer.php';
?>