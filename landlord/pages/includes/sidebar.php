<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="menu-item active">
        <span><a href="/Jengopay/landlord/pages/Dashboard/dashboard.php" style="text-decoration: none;"><i class="fas fa-home"></i> Dashboard</a></span>
    </div>
    <div class="menu-item">
        <span><i class="fas fa-building"> </i> <a href="/Jengopay/landlord/pages/Buildings/buildings.php">Buildings </a> </span>
    </div>
    <div class="menu-item">
        <span><i class="fas fa-users"></i> <a href="/Jengopay/landlord/pages/Buildings/all_tenants.php">Tenants</a> </span>
    </div>

    <div class="menu-item">
        <span>
            <i class="fas fa-comments"></i> <a href="/Jengopay/landlord/pages/communications/inAppMessages.php">Communication</a>
        </span>
    </div>
    
    <div class="menu-item" onclick="toggleSubmenu('financials')">
        <span><i class="fas fa-dollar-sign"></i> Financials</span>
        <i class="fas fa-chevron-down chevron" id="financials-chevron"></i>
    </div>
    <div class="submenu" id="financials-submenu">
        <div class="submenu-item"><a href="/Jengopay/landlord/pages/financials/invoices/invoice.php">Invoices</a></div>
        <div class="submenu-item"><a href="/Jengopay/landlord/pages/financials/expenses/expenses.php">Expenses</a></div>
        <div class="submenu-item"><a href="/Jengopay/landlord/pages/financials/balanceSheet/balanceSheet.php">Balance Sheet</a></div>
        <div class="submenu-item"><a href="/Jengopay/landlord/pages/financials/cashflow/cashflow.php"> Cash flow</a></div>
        <div class="submenu-item"><a href="/Jengopay/landlord/pages/financials/profit&loss/profit&loss.php"> Profit&Loss</a></div>
    </div>
    <div class="menu-item" onclick="toggleSubmenu('reports')">
        <span><i class="fas fa-chart-bar"></i> Reports</span>
        <i class="fas fa-chevron-down chevron" id="reports-chevron"></i>
    </div>
    <div class="submenu" id="reports-submenu">
        <div class="submenu-item"><a href="/Jengopay/landlord/pages/financials/generalledger/general_ledger.php"> General Ledger</a></div>
        <div class="submenu-item"><a href="/Jengopay/landlord/pages/financials/trialbalance/trial_balance.php"> Trial Balance</a></div>
        <div class="submenu-item"><a href="/Jengopay//landlord/pages/financials/agedreceivable/aged_receivable.php"> Aged Receivable</a></div>
        <div class="submenu-item"><a href="/Jengopay/landlord/pages/financials/agedpayable/aged_payable.php"> Aged Payable</a></div>
    </div>
    <div class="menu-item">
        <span><i class="fas fa-tools"></i> <a href="/Jengopay/landlord/pages/maintenance/maintenance.php">Maintenance </a> </span>
    </div>
    <div class="menu-item">
        <span><i class="fas fa-cog"></i> Settings</span>
    </div>
    <div class="menu-item">
        <span><i class="fas fa-sign-out-alt"></i> Log Out</span>
    </div>
</aside>