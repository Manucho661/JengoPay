export async function editSupplier(editForm) {
    {
        const formData = new FormData(editForm);
        const response = await fetch("actions/editSupplier.php", {
            method: "POST",
            body: formData,
        });
        window.location.reload();
    };
}