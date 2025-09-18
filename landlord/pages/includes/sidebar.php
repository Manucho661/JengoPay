<nav class="sidebar" style="position:relative;">
  <h5 class="text-center text-uppercase">Dashboard</h5>
  <a href="/Jengopay/landlord/pages/Dashboard/index2.php">📊 Dashboard</a>

  <!-- Property -->
  <div class="menu-group">
    <div class="menu-header" onclick="toggleMenu(this)">
      <span class="arrow" style="color: #FFC107 !important;"><i class="bi bi-caret-right-fill arrow"></i></span> 🏠 Property
    </div>
    <div class="submenu">
      <a href="/Jengopay/landlord/pages/property/reg_form.php" >🏢 Buildings</a>
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
      <a href="/Jengopay/landlord/pages/communications/announcements.php">📢 Announcements</a>
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
      <a href="/Jengopay/landlord/pages/financials/profit&loss/profit&loss.php">📊 Profit&Loss</a>
      <a href="/Jengopay/landlord/pages/financials/gl/gL.php">📊 General Ledger</a>
    </div>
  </div>
    <a href="#">⚙️ Settings</a>
    <a href="#" class="logout"> 🔓  Log Out</a>
</nav>
