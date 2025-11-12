
export async function operatingInflow() {
    try {
        const response = await fetch("actions/operatingInFlow.php");


        const data = await response.json();
        console.log(data);

        return data; // good practice to return the result
    } catch (err) {
        console.log('Runtime error encountered:', err);
    }
}
