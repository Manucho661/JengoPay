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

// the expansion and collapse applications.
export function expandCollapseApplication() {
    document.getElementById('applicationsListGroup').addEventListener('click', (e) => {
        // Step 1: Check if a child element was clicked
        const child = e.target.closest('.more');
        console.log("Clicked target:", e.target);

        if (!child) return; // Ignore clicks outside children

        // Step 2: Find that child's parent
        const parent = child.closest('.list-group-item');

        const description = parent.querySelector('.appliedJobDescription');

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

// CHAT AREA
// const chatPanel = document.getElementById('chatPanel');
// const openChatPanel = document.getElementById('openChatPanel');
// const closeChatPanel = document.getElementById('closeChatPanel');
// const chatItems = document.querySelectorAll('.chat-item');
// const chatModal = new bootstrap.Modal(document.getElementById('chatModal'));
// const chatModalLabel = document.getElementById('chatModalLabel');

// openChatPanel.addEventListener('click', () => {
//     chatPanel.style.display = 'flex';
// });

// closeChatPanel.addEventListener('click', () => {
//     chatPanel.style.display = 'none';
// });

// chatItems.forEach(item => {
//     item.addEventListener('click', () => {
//         const client = item.dataset.client;
//         chatModalLabel.textContent = `Chat with ${client}`;
//         chatPanel.style.display = 'none';
//         chatModal.show();
//     });
// });
