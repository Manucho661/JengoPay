function checkSidebar() {
    const sidebar = document.querySelector('.sidebar');

    if (window.innerWidth <= 768) {
        sidebar.classList.add('hidden');
    } else {
        sidebar.classList.remove('hidden');
    }
}

// Run on load
checkSidebar();

// Run on screen resize
window.addEventListener('resize', checkSidebar);
