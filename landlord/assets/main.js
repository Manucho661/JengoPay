

async function getSummary() {
    const response = await fetch(`../../actions/getSummary.php`);
    const summary = await response.json();
    console.log("Fetched data:", summary);

    document.getElementById('totalProperties').textContent = summary.totalBuildings;
}
getSummary();


function toggleSubmenu(menuId) {
    const submenu = document.getElementById(menuId + '-submenu');
    const chevron = document.getElementById(menuId + '-chevron');

    submenu.classList.toggle('open');
    chevron.classList.toggle('rotate');
}

function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('hidden');
}

// Close sidebar when clicking outside on mobile
document.addEventListener('click', function (event) {
    const sidebar = document.getElementById('sidebar');
    const hamburger = document.querySelector('.hamburger');

    if (window.innerWidth <= 768) {
        if (!sidebar.contains(event.target) && !hamburger.contains(event.target)) {
            sidebar.classList.add('hidden');
        }
    }
});

// Handle window resize
window.addEventListener('resize', function () {
    const sidebar = document.getElementById('sidebar');
    if (window.innerWidth > 768) {
        sidebar.classList.remove('hidden');
    } else {
        sidebar.classList.add('hidden');
    }
});

// Initialize sidebar state on load
window.addEventListener('load', function () {
    const sidebar = document.getElementById('sidebar');
    if (window.innerWidth <= 768) {
        sidebar.classList.add('hidden');
    }
});

// control the notification part at the header
function toggleNotifications() {
    const dropdown = document.getElementById('notificationsDropdown');
    dropdown.classList.toggle('show');
}