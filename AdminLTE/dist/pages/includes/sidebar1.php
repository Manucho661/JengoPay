<nav class="sidebar" style="position:relative;">
  <h5 class="text-center text-uppercase">Dashboard</h5>
  <a href="../Dashboard/index2.php">📊 Dashboard</a>

  <!-- Property -->
  <div class="menu-group">
    <div class="menu-header" onclick="toggleMenu(this)">
      <span class="arrow" style="color: #FFC107 !important;"><i class="bi bi-caret-right-fill arrow"></i></span> 🏠 Property
    </div>
    <div class="submenu">
      <a href="../property/reg_form.php">🏢 Buildings</a>
      <!-- Add more options later -->
    </div>
  </div>

  <div class="menu-group">
    <div class="menu-header" onclick="toggleMenu(this)">
      <span class="arrow" style="color: #FFC107 !important;"><i class="bi bi-caret-right-fill arrow"></i></span> 👥 People
    </div>
    <div class="submenu">
      <a href="../people/tenants.php">👥 Tenants</a>
      <a href="../serviceProvider/providers.php">👥 Providers</a>
    </div>
  </div>

  <!-- Communications -->
  <div class="menu-group">
    <div class="menu-header" onclick="toggleMenu(this)">
      <span class="arrow" style="color: #FFC107 !important;"><i class="bi bi-caret-right-fill arrow"></i></span> 💬 Communications
    </div>
    <div class="submenu">
    <a href="../communications/texts.php">💬 In-app Messages</a>
      <a href="../communications/ann.php">📢 Announcements</a>

    </div>
  </div>

  <a href="../inspections/inspections.php">🕵️ Inspections</a>
  <a href="../Rent/Rent.php">💸 Rent</a>
  <a href="../maintenance/maintenance.php">🛠 Repairs & Maintenance</a>

  <!-- Financials -->
  <div class="menu-group">
    <div class="menu-header" onclick="toggleMenu(this)">
      <span class="arrow" style="color: #FFC107 !important;"><i class="bi bi-caret-right-fill arrow"></i></span> 💼 Financials
    </div>
    <div class="submenu">
      <a href="../financials/expenses/expenses.php">💵 Expenses</a>
      <a href="#">📄 Invoices</a>
      <a href="#">📊 Balance Sheet</a>
    </div>
  </div>
    <a href="#">⚙️ Settings</a>
    <a href="#" class="logout"> 🔓  Log Out</a>
</nav>
