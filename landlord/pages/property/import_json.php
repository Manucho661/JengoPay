<?php

set_time_limit(600); // Allow the script to run for 10 minutes

$pdo = new PDO("mysql:host=localhost;dbname=bt_jengopay", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Step 1: Read JSON file
$json = file_get_contents("kenya_locations.json");
if (!$json) {
    die("❌ Could not read JSON file");
}

// Step 2: Decode JSON
$data = json_decode($json, true);
if (is_null($data)) {
    echo "<pre>$json</pre>";
    die("❌ JSON decode failed: " . json_last_error_msg());
}

// Step 3: Ensure that the data is an array
if (!is_array($data)) {
    die("❌ Expected an array but got a " . gettype($data));
}

// Step 4: Insert into DB
foreach ($data as $county_name => $constituencies) {
  
    // Insert county (Skip if exists)
    $stmt = $pdo->prepare("INSERT IGNORE INTO counties (name) VALUES (?)");
    $stmt->execute([$county_name]);

    // Get county_id
    $county_id = $pdo->lastInsertId();
    if (!$county_id) {
        $stmt = $pdo->prepare("SELECT county_id FROM counties WHERE name = ?");
        $stmt->execute([$county_name]);
        $county_id = $stmt->fetchColumn();
    }

    foreach ($constituencies as $constituency_name => $wards) {
        // Insert constituency (Skip if exists)
        $stmt = $pdo->prepare("INSERT IGNORE INTO constituencies (name, county_id) VALUES (?, ?)");
        $stmt->execute([$constituency_name, $county_id]);

        // Get constituency_id
        $constituency_id = $pdo->lastInsertId();
        if (!$constituency_id) {
            $stmt = $pdo->prepare("SELECT constituency_id FROM constituencies WHERE name = ?");
            $stmt->execute([$constituency_name]);
            $constituency_id = $stmt->fetchColumn();
        }

        // Ensure wards is treated as an array
        if (is_array($wards)) {
            // If wards is a nested array (array of arrays)
            foreach ($wards as $ward_group) {
                if (is_array($ward_group)) {
                    // Handle case where $ward_group contains an array of ward names
                    foreach ($ward_group as $ward_name) {
                        if (is_string($ward_name)) {
                            $stmt = $pdo->prepare("INSERT IGNORE INTO wards (name, constituency_id) VALUES (?, ?)");
                            $stmt->execute([$ward_name, $constituency_id]);
                        } else {
                            die("❌ Invalid ward name. Expected a string, found " . gettype($ward_name));
                        }
                    }
                } else {
                    // If wards is an array of strings
                    if (is_string($ward_group)) {
                        $stmt = $pdo->prepare("INSERT IGNORE INTO wards (name, constituency_id) VALUES (?, ?)");
                        $stmt->execute([$ward_group, $constituency_id]);
                    } else {
                        die("❌ Invalid ward data. Expected a string or array of strings.");
                    }
                }
            }
        } elseif (is_string($wards)) {
            // If wards is a single string, insert it directly
            $stmt = $pdo->prepare("INSERT IGNORE INTO wards (name, constituency_id) VALUES (?, ?)");
            $stmt->execute([$wards, $constituency_id]);
        } else {
            die("❌ Invalid data type for wards. Expected a string or an array of strings.");
        }
    }
}

echo "✅ Import successful!";
?>
