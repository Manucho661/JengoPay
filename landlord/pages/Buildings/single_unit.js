$(document).ready(function() {
    // --- Disable past dates for all reading_date inputs ---
    let today = new Date().toISOString().split("T")[0];
    $("input[name='reading_date']").attr("min", today);

    // Recalculate when user types or changes inputs
    $(document).on("keyup change", ".previous_reading, .current_reading, .consumption_cost", function() {
        let modal = $(this).closest(".modal");

        let previousReading = Number(modal.find(".previous_reading").val());
        let currentReading  = Number(modal.find(".current_reading").val());
        let consumptionCost = Number(modal.find(".consumption_cost").val());

        let submitBtn = modal.find("button[type='submit']");

        if (!isNaN(previousReading) && !isNaN(currentReading)) {
            if (currentReading < previousReading) {
                modal.find(".consumption_units").val("");
                modal.find(".final_bill").val("");
                submitBtn.prop("disabled", true).css("opacity", "0.5").attr("title", "Fix readings first");
                return;
            }

            let consumption_units = currentReading - previousReading;
            modal.find(".consumption_units").val(consumption_units);

            if (!isNaN(consumptionCost)) {
                let final_bill = consumptionCost * consumption_units;
                modal.find(".final_bill").val(final_bill);
            }

            submitBtn.prop("disabled", false).css("opacity", "1").removeAttr("title");
        }
    });

    // Prevent form submission if invalid (extra safety)
    $(document).on("submit", "form", function(e) {
        let modal = $(this).closest(".modal");

        let previousReading = Number(modal.find(".previous_reading").val());
        let currentReading  = Number(modal.find(".current_reading").val());
        let readingDate     = modal.find("input[name='reading_date']").val();

        // Check invalid reading values
        if (currentReading < previousReading) {
            e.preventDefault();
            Swal.fire({
                icon: "error",
                title: "Invalid Input",
                text: "Current Reading must be greater than or equal to Previous Reading.",
                confirmButtonColor: "#00192D"
            });
            return;
        }

        // Check invalid date
        if (readingDate) {
            let todayDate = new Date().toISOString().split("T")[0];
            if (readingDate < todayDate) {
                e.preventDefault();
                Swal.fire({
                    icon: "warning",
                    title: "Invalid Date",
                    text: "Reading Date cannot be in the past.",
                    confirmButtonColor: "#00192D"
                });
                return;
            }
        }
    });
});
