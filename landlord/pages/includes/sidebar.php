<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="menu-item active">
        <span><a href="/Jengopay/landlord/pages/Dashboard/index2.php" style="text-decoration: none;"><i class="fas fa-home"></i> Dashboard</a></span>
    </div>
    <div class="menu-item" onclick="toggleSubmenu('properties')">
        <span><i class="fas fa-building"></i>Properties</span>
        <i class="fas fa-chevron-down chevron" id="properties-chevron"></i>
    </div>
    <div class="submenu" id="properties-submenu">
        <div class="submenu-item"><a href="/Jengopay/landlord/pages/buildings/single_units.php"><i class="fas fa-building"></i>Single Units</a></div>
        <div class="submenu-item"> <a href="/Jengopay/landlord/pages/buildings/bed_sitter_units.php"><i class="fas fa-building"></i> BedSitters</a></div>
        <div class="submenu-item"> <a href="/Jengopay/landlord/pages/buildings/multi_room_units.php"><i class="fas fa-building"></i> Multirooms</a></div>
    </div>
    <!-- <div class="menu-item">
        <span><a href="/Jengopay/landlord/pages/Buildings/buildings.php"><i class="fas fa-building"></i> Properties</a></span>
    </div> -->
    <div class="menu-item" onclick="toggleSubmenu('users')">
        <span><i class="fas fa-users"></i> Users</span>
        <i class="fas fa-chevron-down chevron" id="users-chevron"></i>
    </div>
    <div class="submenu" id="users-submenu">
        <div class="submenu-item"><a href="/Jengopay/landlord/pages/buildings/all_tenants.php"><i class="fas fa-users"></i>Tenants</a></div>
        <div class="submenu-item">Service Providers</div>
    </div>
    <div class="menu-item" onclick="toggleSubmenu('communication')">
        <span><i class="fas fa-comments"></i> Communication</span>
        <i class="fas fa-chevron-down chevron" id="communication-chevron"></i>
    </div>
    <div class="submenu" id="communication-submenu">
        <div class="submenu-item"> <a href="/Jengopay/landlord/pages/communications/texts.php"> In-app Messages</a></div>
        <div class="submenu-item"><a href="/Jengopay/landlord/pages/communications/announcements/announcements.php">Announcements</a></div>
        <div class="submenu-item">Emails</div>
    </div>
    <div class="menu-item" onclick="toggleSubmenu('financials')">
        <span><i class="fas fa-dollar-sign"></i> Financials</span>
        <i class="fas fa-chevron-down chevron" id="financials-chevron"></i>
    </div>
    <div class="submenu" id="financials-submenu">
        <div class="submenu-item"><a href="/Jengopay/landlord/pages/financials/invoices/invoice.php">Invoices</a></div>
        <div class="submenu-item"><a href="/Jengopay/landlord/pages/financials/expenses/expenses.php">Expenses</a></div>
        <div class="submenu-item"><a href="/Jengopay/landlord/pages/financials/balanceSheet/balanceSheet.php">Balance Sheet</a></div>
        <div class="submenu-item"><a href="/Jengopay/landlord/pages/financials/Rent.php">Rental Income</a></div>
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
      <span><i class="fas fa-tools"></i> <a href="/Jengopay/landlord/pages/maintenance/maintenance.php">Maintenance Requests</a> </span>  
    </div>
    <div class="menu-item">
        <span><i class="fas fa-cog"></i> Settings</span>
    </div>
    <div class="menu-item">
        <span><i class="fas fa-sign-out-alt"></i> Log Out</span>
    </div>
</aside>