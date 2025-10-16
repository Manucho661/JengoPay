// the expansion and collapse of an event Request.
export function expandCollapseRequest() {
    document.getElementById('requests-container').addEventListener('click', (e) => {
        // Step 1: Check if a child element was clicked
        const child = e.target.closest('.more');
        console.log("Clicked target:", e.target);

        if (!child) return; // Ignore clicks outside children

        // Step 2: Find that child's parent
        const parent = child.closest('.job-card');

        const description = parent.querySelector('.description');

        if (description.classList.contains('collapsed')) {
            description.classList.remove('collapsed');
            child.textContent = 'less';
        } else {
            description.classList.add('collapsed');
            child.textContent = 'more';
        }

        console.log('Removed "collapsed" class from:', parent);
    });
}
