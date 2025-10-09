<nav class="sidebar" style="position:relative;">
  <h5 class="text-center text-uppercase">Dashboard</h5>
  <a href="/Jengopay/landlord/pages/Dashboard/index2.php">📊 Dashboard</a>

  <!-- Property -->
  <div class="menu-group">
    <div class="menu-header" onclick="toggleMenu(this)">
      <span class="arrow" style="color: #FFC107 !important;"><i class="bi bi-caret-right-fill arrow"></i></span> 🏠 Buildings
    </div>
    <div class="submenu">
      <a href="/Jengopay/landlord/pages/Buildings/buildings.php" >🏢 Buildings</a>
      <!-- Add more options later -->
    </div>
  </div>

  <div class="menu-group">
    <div class="menu-header" onclick="toggleMenu(this)">
      <span class="arrow" style="color: #FFC107 !important;"><i class="bi bi-caret-right-fill arrow"></i></span> 👥 People
    </div>
    <div class="submenu">
      <a href="/Jengopay/landlord/pages/people/tenants.php">👥 Tenants</a>
      <a href="/Jengopay/landlord/pages/serviceProviders/providers">👥 Providers</a>
    </div>
  </div>

  <!-- Communications -->
  <div class="menu-group">
    <div class="menu-header" onclick="toggleMenu(this)">
      <span class="arrow" style="color: #FFC107 !important;"><i class="bi bi-caret-right-fill arrow"></i></span> 💬 Communications
    </div>
    <div class="submenu">
    <a href="/Jengopay/landlord/pages/communications/texts.php">💬 In-app Messages</a>
      <a href="/Jengopay/landlord/pages/communications/announcements/announcements.php">📢 Announcements</a>
    </div>
  </div>
  
  <a href="/Jengopay/landlord/pages/inspections/inspections.php">🕵️ Inspections</a>
  <a href="/Jengopay/landlord/pages/maintenance/maintenance.php">🛠 Repairs & Maintenance</a>

  <!-- Financials -->
  <div class="menu-group">
    <div class="menu-header" onclick="toggleMenu(this)">
      <span class="arrow" style="color: #FFC107 !important;"><i class="bi bi-caret-right-fill arrow"></i></span> 💼 Financials
    </div>
    <div class="submenu">
      <a href="/Jengopay/landlord/pages/financials/Rent.php">💸Rent</a>
      <a href="/Jengopay/landlord/pages/financials/expenses/expenses.php">💵 Expenses</a>
      <a href="/Jengopay//landlord/pages/financials/cashflow.php">💵 Cashflow</a>
      <a href="/Jengopay/landlord/pages/financials/invoices/invoice.php">📄 Invoices</a>
      <a href="/Jengopay/landlord/pages/financials/balanceSheet/balanceSheet.php">📊 Balance Sheet</a>
      <a href="/Jengopay/landlord/pages/financials/cashflow/cashflow.php">📊 Cash flow</a>
      <a href="/Jengopay/landlord/pages/financials/profit&loss/profit&loss.php">📊 Profit&Loss</a>
    </div>
  </div>
  <!-- Financials -->
  <div class="menu-group">
    <div class="menu-header" onclick="toggleMenu(this)">
      <span class="arrow" style="color: #FFC107 !important;"><i class="bi bi-caret-right-fill arrow"></i></span> 💼 Reports
    </div>
    <div class="submenu">
      <a href="/Jengopay/landlord/pages/financials/generalledger/general_ledger.php"><i class="fas fa-book-open"></i>General Ledger</a>
      <a href="/Jengopay/landlord/pages/financials/trialbalance/trial_balance.php"><i class="fas fa-balance-scale"></i> Trial Balance</a>
      <a href="/Jengopay//landlord/pages/financials/agedreceivable/aged_receivable.php"><i class="bi bi-receipt"></i> Aged Receivable</a>
      <a href="/Jengopay/landlord/pages/financials/agedpayable/aged_payable.php"><i class="bi bi-journal-minus"></i>Aged Payable</a>
    </div>
  </div>
    <a href="#">⚙️ Settings</a>
    <a href="#" class="logout"> 🔓  Log Out</a>
</nav>
