<?php
include '../../db/connect.php';

// Get all counties for the initial dropdown
$counties = $pdo->query("SELECT * FROM counties ORDER BY county_name")->fetchAll(PDO::FETCH_ASSOC);

// Check if a county was selected
$selectedCounty = $_POST['county_id'] ?? null;
$selectedConstituency = $_POST['constituency_id'] ?? null;

$constituencies = [];
$wards = [];

if ($selectedCounty) {
    $stmt = $pdo->prepare("SELECT * FROM constituencies WHERE county_id = ? ORDER BY constituency_name");
    $stmt->execute([$selectedCounty]);
    $constituencies = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if ($selectedConstituency) {
    $stmt = $pdo->prepare("SELECT * FROM wards WHERE constituency_id = ? ORDER BY ward_name");
    $stmt->execute([$selectedConstituency]);
    $wards = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kenya Administrative Units Selector</title>
</head>
<body>
    <h1>Select Administrative Units</h1>

    <form method="post">
        <div>
            <label for="county_id">County:</label>
            <select name="county_id" id="county_id" onchange="this.form.submit()">
                <option value="">-- Select County --</option>
                <?php foreach ($counties as $county): ?>
                    <option value="<?= $county['id'] ?>" <?= $selectedCounty == $county['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($county['county_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <?php if ($selectedCounty): ?>
        <div>
            <label for="constituency_id">Constituency:</label>
            <select name="constituency_id" id="constituency_id" onchange="this.form.submit()">
                <option value="">-- Select Constituency --</option>
                <?php foreach ($constituencies as $constituency): ?>
                    <option value="<?= $constituency['id'] ?>" <?= $selectedConstituency == $constituency['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($constituency['constituency_name']) ?> (<?= $constituency['constituency_code'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php endif; ?>
    </form>

    <?php if ($selectedConstituency): ?>
    <div>
        <h2>Wards in Selected Constituency</h2>
        <ul>
            <?php foreach ($wards as $ward): ?>
                <li><?= htmlspecialchars($ward['ward_name']) ?> (<?= $ward['ward_code'] ?>)</li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
</body>
</html>