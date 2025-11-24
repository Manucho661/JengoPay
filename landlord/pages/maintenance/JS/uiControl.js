

export async function otherRequest(event) {
    const otherField = document.getElementById('otherRequestField');
    const requestSelect = document.getElementById('request');

    const btn = event.currentTarget; // the button that triggered the event

    if (otherField.style.display === 'none' || otherField.style.display === '') {
        otherField.style.display = 'block';
        requestSelect.required = false;
        requestSelect.value = '';
        btn.textContent = 'Select from list';
    } else {
        otherField.style.display = 'none';
        requestSelect.required = true;
        document.getElementById('specifyRequest').value = '';
        btn.textContent = 'Other';
    }
}
