<?php
$pdo = new PDO("mysql:host=localhost;dbname=bt_jengopay", "root", ""); // update if your DB name is different
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// Step 1: Read JSON file
$json = file_get_contents("kenya_locations.json");
if (!$json) {
    die("❌ Could not read JSON file");
}

// Step 2: Decode JSON
$data = json_decode($json, true);
if (is_null($data)) {
    echo "<pre>$json</pre>"; // Show raw JSON
    die("❌ JSON decode failed: " . json_last_error_msg());
}


// Step 3: Find the "counties" table data
$countiesData = null;
foreach ($data as $entry) {
    if (isset($entry["type"]) && $entry["type"] === "table" && $entry["name"] === "counties") {
        $countiesData = $entry["data"];
        break;
    }
}

if (!$countiesData) {
    die("❌ Counties data not found in JSON.");
}

// Step 4: Insert into your DB
foreach ($countiesData as $row) {
  $stmt = $pdo->prepare("INSERT INTO counties (county_name) VALUES (?)");
  $stmt->execute([$county['county_name']]);

}

echo "✅ Counties imported successfully!";
?>
