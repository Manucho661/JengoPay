<?php
$currentActivePage = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
?>

<aside class="sidebar" id="sidebar">

    <div class="menu-item <?= $currentActivePage == 'dashboard.php' ? 'active' : '' ?>">
        <span>
            <a href="/Jengopay/landlord/pages/Dashboard/dashboard.php" class="">
                <i class="fas fa-home"></i> Dashboard
            </a>
        </span>
    </div>

    <div class="menu-item <?= $currentActivePage == 'buildings.php' ? 'active' : '' ?>">
        <span>
            <i class="fas fa-building"></i>
            <a href="/Jengopay/landlord/pages/Buildings/buildings.php">Buildings</a>
        </span>
    </div>

    <div class="menu-item <?= $currentActivePage == 'units.php' ? 'active' : '' ?>">
        <span>
            <i class="fas fa-door-open"></i>
            <a href="/Jengopay/landlord/pages/Units/units.php">Units</a>
        </span>
    </div>

    <div class="menu-item <?= $currentActivePage == 'all_tenants.php' ? 'active' : '' ?>">
        <span>
            <i class="fas fa-users"></i>
            <a href="/Jengopay/landlord/pages/Buildings/all_tenants.php">Tenants</a>
        </span>
    </div>

    <div class="menu-item <?= $currentActivePage == 'inAppMessages.php' ? 'active' : '' ?>">
        <span>
            <i class="fas fa-comments"></i>
            <a href="/Jengopay/landlord/pages/communications/inAppMessages.php">Communication</a>
        </span>
    </div>

    <!-- Financials -->
    <div class="menu-item" onclick="toggleSubmenu('financials')">
        <span><i class="fas fa-dollar-sign"></i> Financials</span>
        <i class="fas fa-chevron-down chevron" id="financials-chevron"></i>
    </div>

    <div class="submenu" id="financials-submenu">
        <div class="submenu-item <?= $currentActivePage == 'invoices.php' ? 'active' : '' ?>">
            <a href="/Jengopay/landlord/pages/financials/invoices/invoices.php">Invoices</a>
        </div>

        <div class="submenu-item <?= $currentActivePage == 'expenses.php' ? 'active' : '' ?>">
            <a href="/Jengopay/landlord/pages/financials/expenses/expenses.php">Expenses</a>
        </div>

        <div class="submenu-item <?= $currentActivePage == 'balanceSheet.php' ? 'active' : '' ?>">
            <a href="/Jengopay/landlord/pages/financials/balanceSheet/balanceSheet.php">Balance Sheet</a>
        </div>

        <div class="submenu-item <?= $currentActivePage == 'cashflow.php' ? 'active' : '' ?>">
            <a href="/Jengopay/landlord/pages/financials/cashflow/cashflow.php">Cash Flow</a>
        </div>

        <div class="submenu-item <?= $currentActivePage == 'profit&loss.php' ? 'active' : '' ?>">
            <a href="/Jengopay/landlord/pages/financials/profit&loss/profit&loss.php">Profit & Loss</a>
        </div>
    </div>

    <!-- Reports -->
    <div class="menu-item" onclick="toggleSubmenu('reports')">
        <span><i class="fas fa-chart-bar"></i> Reports</span>
        <i class="fas fa-chevron-down chevron" id="reports-chevron"></i>
    </div>

    <div class="submenu" id="reports-submenu">
        <div class="submenu-item <?= $currentActivePage == 'general_ledger.php' ? 'active' : '' ?>">
            <a href="/Jengopay/landlord/pages/financials/generalledger/general_ledger.php">General Ledger</a>
        </div>

        <div class="submenu-item <?= $currentActivePage == 'trial_balance.php' ? 'active' : '' ?>">
            <a href="/Jengopay/landlord/pages/financials/trialbalance/trial_balance.php">Trial Balance</a>
        </div>

        <div class="submenu-item <?= $currentActivePage == 'aged_receivable.php' ? 'active' : '' ?>">
            <a href="/Jengopay/landlord/pages/financials/agedreceivable/aged_receivable.php">Aged Receivable</a>
        </div>

        <div class="submenu-item <?= $currentActivePage == 'aged_payable.php' ? 'active' : '' ?>">
            <a href="/Jengopay/landlord/pages/financials/agedpayable/aged_payable.php">Aged Payable</a>
        </div>
    </div>

    <div class="menu-item <?= $currentActivePage == 'maintenance.php' ? 'active' : '' ?>">
        <span>
            <i class="fas fa-tools"></i>
            <a href="/Jengopay/landlord/pages/maintenance/maintenance.php">Maintenance</a>
        </span>
    </div>

    <div class="menu-item <?= $currentActivePage == 'settings.php' ? 'active' : '' ?>">
        <span><i class="fas fa-cog"></i> Settings</span>
    </div>

</aside>
