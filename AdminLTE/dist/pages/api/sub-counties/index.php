<?php
// Include the database connection file
include '../../db/connect.php';

// Function to fetch all counties
function getCounties($pdo) {
    $stmt = $pdo->query("SELECT id, county_name FROM counties ORDER BY county_name");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to fetch sub-counties by county ID
function getSubCounties($pdo, $countyId) {
    $stmt = $pdo->prepare("SELECT id, sub_county_name FROM sub_counties WHERE county_id = :county_id ORDER BY sub_county_name");
    $stmt->bindParam(':county_id', $countyId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to fetch wards by sub-county ID
function getWards($pdo, $subCountyId) {
    $stmt = $pdo->prepare("SELECT id, ward_name FROM wards WHERE sub_county_id = :sub_county_id ORDER BY ward_name");
    $stmt->bindParam(':sub_county_id', $subCountyId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    header('Content-Type: application/json');

    try {
        if (isset($_GET['county_id'])) {
            // Get sub-counties for a county
            $countyId = filter_input(INPUT_GET, 'county_id', FILTER_VALIDATE_INT);
            if ($countyId === false || $countyId === null) {
                throw new InvalidArgumentException('Invalid county ID');
            }
            echo json_encode(getSubCounties($pdo, $countyId));
        } elseif (isset($_GET['sub_county_id'])) {
            // Get wards for a sub-county
            $subCountyId = filter_input(INPUT_GET, 'sub_county_id', FILTER_VALIDATE_INT);
            if ($subCountyId === false || $subCountyId === null) {
                throw new InvalidArgumentException('Invalid sub-county ID');
            }
            echo json_encode(getWards($pdo, $subCountyId));
        }
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}

// Get all counties for the initial page load
$counties = getCounties($pdo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kenya Administrative Units</title>
    <style>
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        select {
            padding: 10px;
            margin: 10px 0;
            width: 100%;
            font-size: 16px;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        .loading {
            color: #666;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Kenya Administrative Units</h1>

        <form>
            <label for="county">County:</label>
            <select id="county" name="county">
                <option value="">Select a County</option>
                <?php foreach ($counties as $county): ?>
                    <option value="<?= htmlspecialchars($county['id']) ?>">
                        <?= htmlspecialchars($county['county_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="sub_county">Sub-County:</label>
            <select id="sub_county" name="sub_county" disabled>
                <option value="">Select a County first</option>
            </select>

            <label for="ward">Ward:</label>
            <select id="ward" name="ward" disabled>
                <option value="">Select a Sub-County first</option>
            </select>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const countySelect = document.getElementById('county');
        const subCountySelect = document.getElementById('sub_county');
        const wardSelect = document.getElementById('ward');

        // County change handler
        countySelect.addEventListener('change', function() {
            const countyId = this.value;

            // Reset sub-county and ward selects
            subCountySelect.innerHTML = '<option value="">Loading sub-counties...</option>';
            subCountySelect.disabled = true;
            wardSelect.innerHTML = '<option value="">Select a Sub-County first</option>';
            wardSelect.disabled = true;

            if (!countyId) {
                subCountySelect.innerHTML = '<option value="">Select a County first</option>';
                return;
            }

            // Fetch sub-counties for the selected county
            fetch(`?county_id=${countyId}`)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    subCountySelect.innerHTML = '<option value="">Select a Sub-County</option>';
                    data.forEach(subCounty => {
                        const option = document.createElement('option');
                        option.value = subCounty.id;
                        option.textContent = subCounty.sub_county_name;
                        subCountySelect.appendChild(option);
                    });
                    subCountySelect.disabled = false;
                })
                .catch(error => {
                    console.error('Error:', error);
                    subCountySelect.innerHTML = '<option value="">Error loading sub-counties</option>';
                });
        });

        // Sub-county change handler
        subCountySelect.addEventListener('change', function() {
            const subCountyId = this.value;

            // Reset ward select
            wardSelect.innerHTML = '<option value="">Loading wards...</option>';
            wardSelect.disabled = true;

            if (!subCountyId) {
                wardSelect.innerHTML = '<option value="">Select a Sub-County first</option>';
                return;
            }

            // Fetch wards for the selected sub-county
            fetch(`?sub_county_id=${subCountyId}`)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    wardSelect.innerHTML = '<option value="">Select a Ward</option>';
                    data.forEach(ward => {
                        const option = document.createElement('option');
                        option.value = ward.id;
                        option.textContent = ward.ward_name;
                        wardSelect.appendChild(option);
                    });
                    wardSelect.disabled = false;
                })
                .catch(error => {
                    console.error('Error:', error);
                    wardSelect.innerHTML = '<option value="">Error loading wards</option>';
                });
        });
    });
    </script>
</body>
</html>