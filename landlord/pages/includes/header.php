<!-- Header -->


<header class="header">

    <?php

    set_error_handler(function ($errno, $errstr, $errfile, $errline) {
        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    });

    try {
        // Get the logged-in user's landlord ID
        $userId = $_SESSION['user']['id'];
        // Get the landlord's id based on the logged-in user
        $stmt = $pdo->prepare("SELECT id FROM landlords WHERE user_id = ?");
        $stmt->execute([$userId]);
        $landlord = $stmt->fetch();
        $landlord_id = $landlord['id'];

        // Count buildings that belong to this specific landlord
        $stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM buildings WHERE landlord_id = ?");
        $stmt->execute([$landlord_id]);

        $totalBuildings = $stmt->fetchColumn(); // returns the count of buildings for this landlord
    } catch (Throwable $e) {
        // Handle the error appropriately
        // echo $e->getMessage();
    }
    ?>
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
    <div style="display: flex; align-items: center; gap: 2rem; flex: 1; justify-content: center;">
        <div style="display: flex; align-items: center; gap: 0.5rem; color: #6c757d;">
            <i class="fas fa-home" style="color: var(--accent-color);"></i>
            <div>
                <div style="font-size: 0.75rem; color: #adb5bd;">Total Properties</div>
                <div style="font-weight: 600; color: var(--main-color);" ><?= $totalBuildings ?></div>
            </div>
        </div>
        <div style="display: flex; align-items: center; gap: 0.5rem; color: #6c757d;">
            <i class="fas fa-door-open" style="color: var(--accent-color);"></i>
            <div>
                <div style="font-size: 0.75rem; color: #adb5bd;">Occupied</div>
                <div style="font-weight: 600; color: var(--main-color);">186/195</div>
            </div>
        </div>
        <div style="display: flex; align-items: center; gap: 0.5rem; color: #6c757d;">
            <i class="fas fa-chart-line" style="color: var(--accent-color);"></i>
            <div>
                <div style="font-size: 0.75rem; color: #adb5bd;">Monthly Revenue</div>
                <div style="font-weight: 600; color: var(--main-color);">$124.5K</div>
            </div>
        </div>
    </div>
    <div class="header-right">
        <div class="notification-icon" onclick="toggleNotifications()">
            <i class="fas fa-envelope"></i>
            <span class="notification-badge">5</span>
        </div>
        <div class="notification-icon">
            <i class="fas fa-bell"></i>
            <span class="notification-badge">3</span>
        </div>

        <span class="user-name">
            <?php
            $fullName = isset($_SESSION['user']['name']) ? $_SESSION['user']['name'] : '';

            // Get first name only
            $firstName = explode(" ", trim($fullName))[0];

            // Ensure first letter is uppercase
            echo ucfirst(strtolower($firstName));
            ?>
        </span>

        <form action="/Jengopay/auth/actions/logout.php" method="post" style="display:inline;">
            <button class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> <span>Log Out</span>
            </button>
        </form>

    </div>


    <!-- Notifications Dropdown -->
    <div class="notifications-dropdown" id="notificationsDropdown">
        <div class="notification-header">
            <i class="fas fa-bell me-2"></i>Incoming Messages
        </div>
        <div id="notificationsList">
            <!-- Notifications will be loaded here -->
        </div>
    </div>

</header>