export async function login(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    try {
        const response = await fetch('./actions/verifyLoginDetails.php', {
            method: "POST",
            body: formData,
        });

        const data = await response.json();
        console.log(data);
        if (data.userRole === 'provider') {
            // Redirect to provider page
            window.location.href = '/Jengopay/service/requestOrders.php';
        } else if (data.userRole === 'landlord') {
            // Redirect to employer page
            window.location.href = '/Jengopay/landlord/pages/dashboard/index2.php';
        } else {
            alert('Unknown user role');
        }
    } catch (err) {
        console.error(err);
    }
}
