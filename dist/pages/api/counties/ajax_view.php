<?php
include '../../db/connect.php';

// API endpoint for AJAX calls
if (isset($_GET['action'])) {
    header('Content-Type: application/json');

    try {
        switch ($_GET['action']) {
            case 'getConstituencies':
                if (empty($_GET['county_id'])) {
                    throw new Exception('County ID is required');
                }
                $stmt = $pdo->prepare("SELECT * FROM constituencies WHERE county_id = ? ORDER BY constituency_name");
                $stmt->execute([$_GET['county_id']]);
                echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
                break;

            case 'getWards':
                if (empty($_GET['constituency_id'])) {
                    throw new Exception('Constituency ID is required');
                }
                $stmt = $pdo->prepare("SELECT * FROM wards WHERE constituency_id = ? ORDER BY ward_name");
                $stmt->execute([$_GET['constituency_id']]);
                echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
                break;

            default:
                throw new Exception('Invalid action');
        }
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}

// Get all counties for the initial dropdown
$counties = $pdo->query("SELECT * FROM counties ORDER BY county_id")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kenya Administrative Units (AJAX)</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>Kenya Administrative Units (AJAX Version)</h1>

    <div>
        <label for="county_id">County:</label>
        <select name="county_id" id="county_id">
            <option value="">-- Select County --</option>
            <?php foreach ($counties as $county): ?>
                <option value="<?= $county['county
                _id'] ?>"><?= htmlspecialchars($county['county_name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div>
        <label for="constituency_id">Constituency:</label>
        <select name="constituency_id" id="constituency_id" disabled>
            <option value="">-- Select Constituency --</option>
        </select>
    </div>

    <div>
        <label for="ward_id">Ward:</label>
        <select name="ward_id" id="ward_id" disabled>
            <option value="">-- Select Ward --</option>
        </select>
    </div>

    <div id="selected-info" style="margin-top: 20px;"></div>

    <script>
    $(document).ready(function() {
        // County changed
        $('#county_id').change(function() {
            const countyId = $(this).val();

            // Reset constituency and ward dropdowns
            $('#constituency_id').html('<option value="">-- Select Constituency --</option>').prop('disabled', true);
            $('#ward_id').html('<option value="">-- Select Ward --</option>').prop('disabled', true);
            $('#selected-info').html('');

            if (countyId) {
                // Fetch constituencies for selected county
                $.get('ajax_view.php', {action: 'getConstituencies', county_id: countyId}, function(data) {
                    if (data.length > 0) {
                        data.forEach(function(constituency) {
                            $('#constituency_id').append(
                                $('<option>').val(constituency.id).text(
                                    constituency.constituency_name + ' (' + constituency.constituency_code + ')'
                                )
                            );
                        });
                        $('#constituency_id').prop('disabled', false);
                    } else {
                        $('#selected-info').html('<p>No constituencies found for selected county</p>');
                    }
                }).fail(function() {
                    alert('Error loading constituencies');
                });
            }
        });

        // Constituency changed
        $('#constituency_id').change(function() {
            const constituencyId = $(this).val();

            // Reset ward dropdown
            $('#ward_id').html('<option value="">-- Select Ward --</option>').prop('disabled', true);
            $('#selected-info').html('');

            if (constituencyId) {
                // Fetch wards for selected constituency
                $.get('ajax_view.php', {action: 'getWards', constituency_id: constituencyId}, function(data) {
                    if (data.length > 0) {
                        data.forEach(function(ward) {
                            $('#ward_id').append(
                                $('<option>').val(ward.id).text(
                                    ward.ward_name + ' (' + ward.ward_code + ')'
                                )
                            );
                        });
                        $('#ward_id').prop('disabled', false);
                    } else {
                        $('#selected-info').html('<p>No wards found for selected constituency</p>');
                    }
                }).fail(function() {
                    alert('Error loading wards');
                });
            }
        });

        // Ward changed
        $('#ward_id').change(function() {
            const wardId = $(this).val();
            if (wardId) {
                const county = $('#county_id option:selected').text();
                const constituency = $('#constituency_id option:selected').text();
                const ward = $('#ward_id option:selected').text();

                $('#selected-info').html(`
                    <h3>Selected Administrative Units</h3>
                    <p><strong>County:</strong> ${county}</p>
                    <p><strong>Constituency:</strong> ${constituency}</p>
                    <p><strong>Ward:</strong> ${ward}</p>
                `);
            } else {
                $('#selected-info').html('');
            }
        });
    });
    </script>
</body>
</html>