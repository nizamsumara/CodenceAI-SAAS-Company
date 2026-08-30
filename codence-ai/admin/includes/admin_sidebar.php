<?php
// Shared admin sidebar
?>

<aside class="admin-sidebar">

    <div class="sidebar-brand">
        <h2>Codence AI</h2>
        <p>Admin Panel</p>
    </div>

    <nav class="sidebar-nav">

        <div class="nav-section-label">Main</div>

        <a href="dashboard.php"
           class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : ''; ?>">
            
            Dashboard
        </a>


        <div class="nav-section-label">Management</div>

        <a href="users.php"
           class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) === 'users.php' || basename($_SERVER['PHP_SELF']) === 'edit-user.php' ? 'active' : ''; ?>">
            
            Users
        </a>


        <a href="services.php"
           class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) === 'services.php' || basename($_SERVER['PHP_SELF']) === 'edit-service.php' ? 'active' : ''; ?>">
        
            Services
        </a>


        <a href="bookings.php"
           class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) === 'bookings.php' ? 'active' : ''; ?>">
            
            Bookings

            <?php if (isset($pending_bk) && $pending_bk > 0): ?>
                <span class="badge">
                    <?php echo $pending_bk; ?>
                </span>
            <?php endif; ?>

        </a>


        <a href="contacts.php"
           class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) === 'contacts.php' ? 'active' : ''; ?>">
            
            Contact Messages

            <?php if (isset($unread_msgs) && $unread_msgs > 0): ?>
                <span class="badge">
                    <?php echo $unread_msgs; ?>
                </span>
            <?php endif; ?>

        </a>


        <div class="nav-section-label">Account</div>

        <a href="../profile.php" class="sidebar-link">
            
            My Profile
        </a>


        <a href="../index.php" class="sidebar-link">
            
            View Website
        </a>

    </nav>


    <div class="sidebar-footer">
        <a href="logout.php">
            Sign Out &rarr;
        </a>
    </div>

</aside>