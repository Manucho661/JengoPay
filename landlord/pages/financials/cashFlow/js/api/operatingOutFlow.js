export async function operatingOutFlow() {
    try {
        const response = await fetch("../cashflow/actions/operatingOutFlow.php");

        if (!response.ok) {
            console.log("Server couldn't be reached");
            return; // stop execution if response is bad
        }

        const data = await response.json();
        console.log(data);

        return data; // good practice to return the result
    } catch (err) {
        console.log('Runtime error encountered:', err);
    }
}