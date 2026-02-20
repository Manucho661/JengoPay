<?php
if (isset($_POST['update_maintenance_status'])) {
                    try {
                        // Fetch current status of the unit
                        $check = $pdo->prepare("SELECT occupancy_status FROM building_units WHERE id = :id");
                        $check->execute([
                            ':id' => $_POST['id']
                        ]);
                        $current_status = $check->fetchColumn();
                        if ($current_status === $_POST['occupancy_status']) {
                            // No change made
                            echo "
                                <script>
                                    Swal.fire({
                                    title: 'Warning!',
                                    text: 'Update failed. You did not change the status.',
                                    icon: 'warning',
                                    confirmButtonText: 'OK'
                                    }).then(() => {
                                    window.history.back();
                                    });
                                </script>";
                        } else {
                            // Update with the new status
                            $update = "UPDATE building_units SET occupancy_status = :occupancy_status WHERE id = :id";
                            $stmt = $pdo->prepare($update);
                            $stmt->bindParam(':occupancy_status', $_POST['occupancy_status'], PDO::PARAM_STR);
                            $stmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
                            $stmt->execute();
                            // Success message
                            echo "
                                <script>
                                    Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: 'Occupancy status updated successfully!',
                                    width: '600px',
                                    padding: '0.6em',
                                    customClass: {
                                    popup: 'compact-swal'
                                    },
                                    confirmButtonText: 'OK'
                                    }).then((result) => {
                                    if (result.isConfirmed) {
                                    window.location.href = 'single_units.php';
                                    }
                                    });
                                </script>";
                        }
                    } catch (PDOException $e) {
                        echo "
                                <script>
                                Swal.fire({
                                icon: 'error',
                                title: 'Database Error',
                                text: '" . addslashes($e->getMessage()) . "',
                                confirmButtonText: 'Close'
                                });
                                </script>";
                    }
                }

                //Change the Status to Vacant if the Unit is Occupied
                if (isset($_POST['update_vacant_status'])) {
                    try {
                        // Fetch current status of the unit
                        $check = $pdo->prepare("SELECT occupancy_status FROM building_units WHERE id = :id");
                        $check->execute([
                            ':id' => $_POST['id']
                        ]);
                        $current_status = $check->fetchColumn();
                        if ($current_status === $_POST['occupancy_status']) {
                            // No change made
                            echo "
                                    <script>
                                    Swal.fire({
                                    title: 'Warning!',
                                    text: 'Update failed. You did not change the status.',
                                    icon: 'warning',
                                    confirmButtonText: 'OK'
                                    }).then(() => {
                                    window.history.back();
                                    });
                                    </script>";
                        } else {
                            // Update with the new status
                            $update = "UPDATE building_units SET occupancy_status = :occupancy_status WHERE id = :id";
                            $stmt = $pdo->prepare($update);
                            $stmt->bindParam(':occupancy_status', $_POST['occupancy_status'], PDO::PARAM_STR);
                            $stmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
                            $stmt->execute();
                            // Success message
                            echo "
                                    <script>
                                        Swal.fire({
                                        icon: 'success',
                                        title: 'Success!',
                                        text: 'Occupancy status updated successfully!',
                                        width: '600px',
                                        padding: '0.6em',
                                        customClass: {
                                        popup: 'compact-swal'
                                        },
                                        confirmButtonText: 'OK'
                                        }).then((result) => {
                                        if (result.isConfirmed) {
                                        window.location.href = 'single_units.php';
                                        }
                                        });
                                    </script>";
                        }
                    } catch (PDOException $e) {
                        echo "
                                <script>
                                Swal.fire({
                                icon: 'error',
                                title: 'Database Error',
                                text: '" . addslashes($e->getMessage()) . "',
                                confirmButtonText: 'Close'
                                });
                                </script>";
                    }
                }