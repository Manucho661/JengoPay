// Function to update URL parameters and redirect
function updateURL(buildingId, month, year) {
  const url = new URL(window.location.href);
  url.searchParams.set('building_id', buildingId);
  url.searchParams.set('month', month);
  url.searchParams.set('year', year);
  window.location.href = url.toString();
}

document.addEventListener('DOMContentLoaded', () => {
  // Custom select dropdown logic
  document.querySelectorAll('.select-option-container').forEach(container => {
      const select = container.querySelector('.custom-select');
      const optionsContainer = container.querySelector('.select-options');
      const options = optionsContainer.querySelectorAll('div');

      // Toggle dropdown on select click
      select.addEventListener('click', (event) => {
          event.stopPropagation(); // Prevent document click listener from closing it immediately
          const isOpen = optionsContainer.style.display === 'block';

          // Close all other dropdowns before opening a new one
          document.querySelectorAll('.select-options').forEach(opt => opt.style.display = 'none');
          document.querySelectorAll('.custom-select').forEach(sel => {
              sel.classList.remove('open');
          });

          // Toggle current dropdown
          optionsContainer.style.display = isOpen ? 'none' : 'block';
          select.classList.toggle('open', !isOpen);
      });

      // Option click handler
      options.forEach(option => {
          option.addEventListener('click', () => {
              const newValue = option.textContent;
              const oldValue = select.textContent;

              if (newValue !== oldValue) { // Only update if selection changed
                  select.textContent = newValue;
                  select.setAttribute('data-value', option.getAttribute('data-value'));

                  options.forEach(opt => opt.classList.remove('selected'));
                  option.classList.add('selected');

                  optionsContainer.style.display = 'none';
                  select.classList.remove('open');

                  // Get current filter values
                  const currentBuildingId = document.querySelector('.select-option-container:nth-of-type(1) .custom-select').getAttribute('data-value');
                  const currentYear = document.querySelector('.select-option-container:nth-of-type(2) .custom-select').getAttribute('data-value');
                  const currentMonth = document.querySelector('.select-option-container:nth-of-type(3) .custom-select').getAttribute('data-value');

                  // Redirect with new filter parameters
                  updateURL(currentBuildingId, currentMonth, currentYear);
              }
          });

          // Highlight on hover (optional, already in your HTML)
          option.addEventListener('mouseenter', () => {
              options.forEach(opt => opt.classList.remove('selected'));
              option.classList.add('selected');
          });
      });
  });

  // Close dropdowns on outside click
  document.addEventListener('click', (e) => {
      if (!e.target.closest('.select-option-container')) {
          document.querySelectorAll('.select-options').forEach(opt => opt.style.display = 'none');
          document.querySelectorAll('.custom-select').forEach(sel => {
              sel.classList.remove('open');
              // sel.style.borderRadius = '5px'; // Adjust this based on your design
          });
      }
  });

  // Initialize DataTable (if you want the table to be interactive)
  // Ensure jQuery and DataTables are loaded before this.
  // This script should be placed after the DataTables JS imports.
  $('#rent').DataTable({
      // Add DataTables options here, e.g.,
      "paging": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "responsive": true // Make table responsive
  });

  // Handle PDF and Excel export buttons
  document.querySelector('.pdf').addEventListener('click', function() {
      alert('PDF Export functionality needs to be implemented!');
      // You would typically send an AJAX request to a PHP script
      // that generates the PDF based on the current filters
      // window.open('generate_pdf.php?building_id=' + currentBuildingId + '&month=' + currentMonth + '&year=' + currentYear, '_blank');
  });

  document.querySelector('.excel').addEventListener('click', function() {
      alert('Excel Export functionality needs to be implemented!');
      // Similar to PDF, send an AJAX request or direct link to PHP script
      // window.open('generate_excel.php?building_id=' + currentBuildingId + '&month=' + currentMonth + '&year=' + currentYear, '_blank');
  });

  // NProgress initialization
  NProgress.configure({ showSpinner: false });
  $(document).on('ajaxStart', function() { NProgress.start(); });
  $(document).on('ajaxStop', function() { NProgress.done(); });
  $(window).on('beforeunload', function() { NProgress.start(); }); // Start on page unload
  $(window).on('load', function() { NProgress.done(); }); // End on page load
});