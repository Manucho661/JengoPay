export async function registerUser(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    try {

        // console.log('yoyo');
         const response = await fetch('./actions/registerUser.php', {
            method: "POST",
            body: formData,
         });
         
         const data = await response.json();
         console.log(data);
        
         if (data.status === 'success') {
             // Redirect to provider page
             window.location.href = '/Jengopay/auth/login.php';
         } 
         else if (data.userRole === 'landlord') {
            // Redirect to employer page
             window.location.href = '/Jengopay/landlord/pages/dashboard/index2.php';
         } else {
             alert('Unknown user role');
         }
    } catch (err) {
        console.error(err);
    }
}
