<!-- meterreading popup -->
<div class="meterpopup-overlay" id="meterPopup">
    <div class="meterpopup-content wide-form">
        <button id="close-btns" class="text-secondary" onclick="meterreadingclosePopup()">×</button>
        <h2 class="assign-title">Add A Meter Reading</h2>
        <form class="wide-form" id="meterForm" method="POST" action="">

 <!-- ✅ Hidden field for JS to access building ID -->
      <input type="hidden" id="building_id" value="<?php echo htmlspecialchars($buildingId); ?>">
        
            <div class="form-group">
                <b><label for="dateInput" class="filter-label">Reading Date</label></b>
                <input type="date" id="dateInput" name="reading_date" class="form-control" required />
            </div>
            <div class="form-group">
                <select id="units" name="unit_number" required onchange="checkPreviousReading()">
                    <option value="">-- Select Unit --</option>
                    <?php foreach ($units as $unit): ?>
                        <option value="<?php echo htmlspecialchars($unit['unit_number']); ?>">
                            <?php echo htmlspecialchars($unit['unit_number']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="meter_type">Meter Type:</label>
                <select id="meter_type" name="meter_type" required>
                    <option value="">-- Select Meter Type --</option>
                    <option value="Water">Water</option>
                    <option value="Electrical">Electrical</option>
                </select>
            </div>

            <div class="form-group">
                <label for="previous_reading">Previous Reading:</label>
                <input type="number" id="previous_reading" name="previous_reading" placeholder="Previous Reading" required>
                <small id="prev_reading_note" style="color: gray; display: none;">
                    This is the first reading for this unit.
                </small>
            </div>

            <div class="form-group">
                <label for="current_reading">Current Reading:</label>
                <input type="number" id="current_reading" name="current_reading" placeholder="Current Reading" required>
            </div>

            <div class="form-group">
                <label>Consumption Units:</label>
                <p id="consumption_preview"><i>Calculated automatically</i></p>
            </div>


            <div class="form-group">
                <label>Consumption Cost:</label>
                <p id="consumption_cost"><i>Calculated automatically</i></p>
                <input type="hidden" id="consumption_cost_value" name="consumption_cost">
            </div>

            <button type="submit" name="submit" class="submit-btn">Create Meter Reading</button>
        </form>
    </div>
</div>

<!-- js -->

<script>
  // Function to meter the meter popup
  function meterreadingopenPopup() {
    document.getElementById("meterPopup").style.display = "flex";
  }

  // Function to close the meter popup
  function meterreadingclosePopup() {
    document.getElementById("meterPopup").style.display = "none";
  }
</script>

<script>
function checkPreviousReading() {
    const unitNumber = document.getElementById("units").value;
    const meterType = document.getElementById("meter_type").value;

    if (unitNumber && meterType) {
        fetch("get_unit_price.php?unit=" + unitNumber + "&type=" + meterType)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.unit_price = parseFloat(data.price); // Store globally
                } else {
                    alert("Price not found.");
                    window.unit_price = 0;
                }
            });
    }
}

document.getElementById("current_reading").addEventListener("input", () => {
    const prev = parseFloat(document.getElementById("previous_reading").value);
    const current = parseFloat(document.getElementById("current_reading").value);
    const consumption = current - prev;

    if (!isNaN(consumption) && window.unit_price) {
        document.getElementById("consumption_preview").innerText =
            `Units: ${consumption} | Total Cost: Ksh ${consumption * window.unit_price}`;
    }
});

</script>

<script>
let buildingWaterPrice = 0;
let buildingElectricityPrice = 0;

document.addEventListener("DOMContentLoaded", function () {
  const buildingIdEl = document.getElementById("building_id");
  if (!buildingIdEl) return;
  const buildingId = buildingIdEl.value;

  // ✅ Fetch prices
  fetch(`actions/get_building_prices.php?building_id=${buildingId}`)
    .then(response => {
      if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
      return response.json();
    })
    .then(data => {
      if (data.success) {
        buildingWaterPrice = parseFloat(data.water_price);
        buildingElectricityPrice = parseFloat(data.electricity_price);
        console.log("✅ Prices loaded:", buildingWaterPrice, buildingElectricityPrice);
        calculateConsumptionAndCost();
      } else {
        console.error("⚠️ Failed to load prices:", data.error);
        alert("⚠️ Unable to fetch building prices.");
      }
    })
    .catch(error => {
      console.error("❌ Fetch error:", error);
      alert("❌ Error loading building prices.");
    });

  // Add listeners
  const meterTypeEl = document.getElementById("meter_type");
  const prevReadingEl = document.getElementById("previous_reading");
  const currReadingEl = document.getElementById("current_reading");

  if (meterTypeEl) meterTypeEl.addEventListener("change", calculateConsumptionAndCost);
  if (prevReadingEl) prevReadingEl.addEventListener("input", calculateConsumptionAndCost);
  if (currReadingEl) currReadingEl.addEventListener("input", calculateConsumptionAndCost);
});

function calculateConsumptionAndCost() {
  const meterType = document.getElementById("meter_type")?.value;
  const prevReading = parseFloat(document.getElementById("previous_reading")?.value || 0);
  const currReading = parseFloat(document.getElementById("current_reading")?.value || 0);

  const consumptionPreview = document.getElementById("consumption_preview");
  const costDisplay = document.getElementById("consumption_cost");
  const costValueInput = document.getElementById("consumption_cost_value");

  if (!meterType || isNaN(prevReading) || isNaN(currReading)) return;

  if (currReading <= prevReading) {
    consumptionPreview.textContent = "Invalid input";
    costDisplay.textContent = "Ksh 0.00";
    costValueInput.value = "";
    return;
  }

  const consumptionUnits = currReading - prevReading;
  consumptionPreview.textContent = `${consumptionUnits} units`;

  let unitPrice = 0;
  if (meterType === "Water") {
    unitPrice = buildingWaterPrice;
  } else if (meterType === "Electrical") {
    unitPrice = buildingElectricityPrice;
  }

  const totalCost = consumptionUnits * unitPrice;
  costDisplay.textContent = "Ksh " + totalCost.toFixed(2);
  costValueInput.value = totalCost.toFixed(2);
}
</script>