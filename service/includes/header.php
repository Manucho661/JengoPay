<!-- Header -->
<header class="header">
    <div style="display: flex; align-items: center; gap: 1rem;">
        <div class="hamburger" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </div>
        <div class="logo d-flex">
            <div class="rounded-circle d-flex align-items-center justify-content-center text-warning"
                style="background:#00192D; width:40px; height:40px; font-weight:600;">
                <b>JP</b>
            </div>
            <div class="logo-text">Jengo<span>Pay</span></div>
        </div>
    </div>

    <!-- subscription section -->
    <div class="d-flex align-items-center gap-2" style="font-size: 0.9rem;">
        <span class="text-muted">Subscription:</span>
        <span class="badge bg-danger">Inactive</span>
        <button class="btn btn-sm" style="background: linear-gradient(135deg, #00192D, #002B5B); color:white; width:100%; white-space: nowrap;">
            Subscribe
        </button>
    </div>

    <div class="header-right">
        <div class="notification-icon">
            <i class="fas fa-envelope"></i>
            <span class="notification-badge">5</span>
        </div>
        <div class="notification-icon">
            <i class="fas fa-bell"></i>
            <span class="notification-badge">3</span>
        </div>
        <span class="user-name">John Doe</span>
        <button class="logout-btn">
            <i class="fas fa-sign-out-alt"></i> <span>Log Out</span>
        </button>
    </div>
</header>
