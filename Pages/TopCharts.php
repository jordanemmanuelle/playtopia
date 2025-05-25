<?php
include '../connection.php';

$query = "SELECT * FROM songs ORDER BY plays DESC LIMIT 10";
$result = mysqli_query($connect, $query);

$topCharts = [];

while ($row = mysqli_fetch_assoc($result)) {
    $topCharts[] = $row;
}

echo json_encode($topCharts);
?>
