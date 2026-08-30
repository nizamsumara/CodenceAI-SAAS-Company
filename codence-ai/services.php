
<?php

include 'includes/db.php';
include 'includes/navbar.php';

/*
|--------------------------------------------------------------------------
| Fetch active services
|--------------------------------------------------------------------------
*/

$sql = "SELECT 
            s.id,
            s.title,
            s.short_description,
            sc.id AS category_id,
            sc.name AS category_name
        FROM services s
        INNER JOIN service_categories sc
            ON s.category_id = sc.id
        WHERE s.status = 'active'
        ORDER BY sc.id ASC, s.id ASC";

$result = mysqli_query($conn, $sql);

$services_by_category = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {

        $category_id = $row['category_id'];

        if (!isset($services_by_category[$category_id])) {
            $services_by_category[$category_id] = [
                'name' => $row['category_name'],
                'services' => []
            ];
        }

        $services_by_category[$category_id]['services'][] = $row;
    }
}

/*
|--------------------------------------------------------------------------
| Find AI Automation category
| This will be opened by default.
|--------------------------------------------------------------------------
*/

$default_category_id = null;

foreach ($services_by_category as $category_id => $category) {

    if (strtolower(trim($category['name'])) === 'ai automation') {
        $default_category_id = $category_id;
        break;
    }
}

/*
|--------------------------------------------------------------------------
| If AI Automation does not exist, open first category
|--------------------------------------------------------------------------
*/

if ($default_category_id === null && !empty($services_by_category)) {
    $default_category_id = array_key_first($services_by_category);
}

?>

<link rel="stylesheet" href="css/services.css">


<!-- =========================================================
     SERVICES HERO
========================================================= -->

<section class="services-hero">

    <div class="services-container">

        <div class="services-eyebrow">
            <span class="eyebrow-line"></span>
            <span>WHAT WE BUILD</span>
        </div>

        <h1 class="services-title">
            Digital solutions<br>
            <span>built for growth.</span>
        </h1>

        <p class="services-description">
            From intelligent AI automation to modern web and mobile
            applications, we build technology that helps businesses
            work smarter, move faster, and scale confidently.
        </p>

    </div>

</section>


<!-- =========================================================
     SERVICES MAIN
========================================================= -->

<section class="services-section">

    <div class="services-container">

        <?php if (empty($services_by_category)): ?>

            <div class="services-empty">

                <div class="empty-number">00</div>

                <h2>Services coming soon.</h2>

                <p>
                    We're preparing our digital solutions.
                    Please check back shortly.
                </p>

            </div>

        <?php else: ?>


            <!-- =================================================
                 CATEGORY TABS
            ================================================== -->

            <div class="category-tabs">

                <?php
                $category_index = 0;

                foreach ($services_by_category as $category_id => $category):

                    $category_index++;

                    $is_active = ($category_id == $default_category_id);

                    $service_count = count($category['services']);
                ?>

                    <button
                        type="button"
                        class="category-tab <?php echo $is_active ? 'active' : ''; ?>"
                        data-category="<?php echo $category_id; ?>"
                    >

                        <span class="category-tab-number">
                            <?php echo str_pad($category_index, 2, '0', STR_PAD_LEFT); ?>
                        </span>

                        <span class="category-tab-name">
                            <?php echo htmlspecialchars($category['name']); ?>
                        </span>

                        <span class="category-tab-count">
                            <?php echo $service_count; ?>
                        </span>

                        <span class="category-tab-arrow">
                            →
                        </span>

                    </button>

                <?php endforeach; ?>

            </div>


            <!-- =================================================
                 CATEGORY CONTENT
            ================================================== -->

            <div class="category-content-wrapper">

                <?php
                $category_index = 0;

                foreach ($services_by_category as $category_id => $category):

                    $category_index++;

                    $is_active = ($category_id == $default_category_id);
                ?>

                    <div
                        class="category-content <?php echo $is_active ? 'active' : ''; ?>"
                        data-content="<?php echo $category_id; ?>"
                    >

                        <!-- Category Header -->

                        <div class="category-heading">

                            <div class="category-heading-left">

                                <span class="category-index">
                                    <?php echo str_pad($category_index, 2, '0', STR_PAD_LEFT); ?>
                                </span>

                                <div>

                                    <h2>
                                        <?php echo htmlspecialchars($category['name']); ?>
                                    </h2>

                                    <p>
                                        <?php
                                        if (strtolower($category['name']) === 'ai automation') {
                                            echo 'Intelligent systems that automate repetitive work and help your business operate smarter.';
                                        } elseif (strtolower($category['name']) === 'web development') {
                                            echo 'High-performance digital experiences designed around your business goals.';
                                        } elseif (strtolower($category['name']) === 'app development') {
                                            echo 'Modern mobile applications built for performance, usability, and scale.';
                                        } else {
                                            echo 'Technology solutions designed around your business needs.';
                                        }
                                        ?>
                                    </p>

                                </div>

                            </div>

                            <div class="category-total">

                                <strong>
                                    <?php echo str_pad(count($category['services']), 2, '0', STR_PAD_LEFT); ?>
                                </strong>

                                <span>
                                    SERVICES
                                </span>

                            </div>

                        </div>


                        <!-- Service Cards -->

                        <div class="services-grid">

                            <?php
                            $service_index = 0;

                            foreach ($category['services'] as $service):

                                $service_index++;
                            ?>

                                <article class="service-card">

                                    <div class="service-card-top">

                                        <span class="service-number">
                                            <?php
                                            echo str_pad(
                                                $service_index,
                                                2,
                                                '0',
                                                STR_PAD_LEFT
                                            );
                                            ?>
                                        </span>

                                        <span class="service-dot"></span>

                                    </div>


                                    <div class="service-card-body">

                                        <h3>
                                            <?php
                                            echo htmlspecialchars(
                                                $service['title']
                                            );
                                            ?>
                                        </h3>

                                        <p>
                                            <?php
                                            echo htmlspecialchars(
                                                $service['short_description']
                                            );
                                            ?>
                                        </p>

                                    </div>


                                    <div class="service-card-footer">

                                        <button
                                            type="button"
                                            class="service-book-button trigger-booking"
                                        >

                                            <span>
                                                Book Consultation
                                            </span>

                                            <span class="button-arrow">
                                                →
                                            </span>

                                        </button>

                                    </div>

                                </article>

                            <?php endforeach; ?>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    </div>

</section>


<!-- =========================================================
     CTA
========================================================= -->

<section class="services-cta">

    <div class="services-cta-inner">

        <div class="cta-label">
            READY WHEN YOU ARE
        </div>

        <h2>
            Have a project<br>
            <span>in mind?</span>
        </h2>

        <p>
            Let's turn your idea into a powerful digital solution.
        </p>

        <button
            type="button"
            class="cta-button trigger-booking"
        >

            <span>Start a Conversation</span>

            <span>→</span>

        </button>

    </div>

</section>


<script>

document.addEventListener("DOMContentLoaded", function () {

    const tabs = document.querySelectorAll(".category-tab");
    const contents = document.querySelectorAll(".category-content");

    tabs.forEach(function (tab) {

        tab.addEventListener("click", function () {

            const categoryId = this.dataset.category;

            /*
            ------------------------------------------
            Remove active state
            ------------------------------------------
            */

            tabs.forEach(function (item) {
                item.classList.remove("active");
            });

            contents.forEach(function (content) {
                content.classList.remove("active");
            });


            /*
            ------------------------------------------
            Activate selected category
            ------------------------------------------
            */

            this.classList.add("active");

            const selectedContent =
                document.querySelector(
                    '[data-content="' + categoryId + '"]'
                );

            if (selectedContent) {
                selectedContent.classList.add("active");
            }

        });

    });

});

</script>


<?php include 'includes/footer.php'; ?>
