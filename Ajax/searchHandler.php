<?php
include '../connection.php'; // your DB connection file

$query = $_GET['query'] ?? '';



$sql = "SELECT * FROM songs WHERE title LIKE ? OR artist LIKE ?";
$stmt = $connect->prepare($sql);
$likeQuery = '%' . $query . '%';
$stmt->bind_param("ss", $likeQuery, $likeQuery);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()): ?>
    <div class="card">
        <img src="../Assets/image/<?php echo htmlspecialchars($row['cover_path']); ?>" alt="Song Image">
        <div class="title"><?php echo htmlspecialchars($row['title']); ?></div>
        <div class="artist"><?php echo htmlspecialchars($row['artist']); ?></div>
    </div>
<?php endwhile; ?>
