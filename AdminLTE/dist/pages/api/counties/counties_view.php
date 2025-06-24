
<?php
include '../../db/connect.php';

// Function to get all counties
function getCounties($pdo) {
    $stmt = $pdo->query("SELECT * FROM counties ORDER BY county_id");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to get constituencies by county
function getConstituenciesByCounty($pdo, $county_id) {
    $stmt = $pdo->prepare("SELECT * FROM constituencies WHERE county_id = ? ORDER BY name");
    $stmt->execute([$county_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to get wards by constituency
function getWardsByConstituency($pdo, $constituency_id) {
    $stmt = $pdo->prepare("SELECT * FROM wards WHERE constituency_id = ? ORDER BY name");
    $stmt->execute([$constituency_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get all counties
$counties = getCounties($pdo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kenya Administrative Units</title>
    <style>
        .county { background-color: #f0f0f0; margin-bottom: 20px; padding: 10px; }
        .constituency { background-color: #e0e0e0; margin: 10px 0 10px 20px; padding: 8px; }
        .ward { background-color: #d0d0d0; margin: 5px 0 5px 40px; padding: 5px; }
    </style>
</head>
<body>
    <h1>Kenya Administrative Units</h1>

    <?php foreach ($counties as $county): ?>
        <div class="county">
            <h2><?= htmlspecialchars($county['county_name']) ?></h2>

            <?php
            // Get constituencies for this county
            $constituencies = getConstituenciesByCounty($pdo, $county['county_id']);
            ?>

            <?php foreach ($constituencies as $constituency): ?>
                <div class="constituency">
                    <h3><?= htmlspecialchars($constituency['name']) ?> (<?= $constituency['id'] ?>)</h3>

                    <?php
                    // Get wards for this constituency
                    $wards = getWardsByConstituency($pdo, $constituency['id']);
                    ?>

                    <ul>
                        <?php foreach ($wards as $ward): ?>
                            <li class="ward">
                                <?= htmlspecialchars($ward['name']) ?> (<?= $ward['name'] ?>)
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</body>
</html>