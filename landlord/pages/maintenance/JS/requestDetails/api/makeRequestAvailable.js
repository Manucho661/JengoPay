export async function makeRequestAvailable() {
    try{
        const response = await fetch("../actions/makeRquestAvailable.php");
        data = response.json;
        console.log(data)
    }
    catch(err){
        console.log(err)
    }
}