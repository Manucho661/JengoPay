// Script for dataTables.

$(document).ready(function() {
    $('#Texts').DataTable({
        "paging": true,
        "searching": true,
        "info": true,
        "lengthMenu": [5, 10, 25, 50],
        "language": {
            "search": "Filter records:",
            "lengthMenu": "Show _MENU_ entries"
        }
    });
});