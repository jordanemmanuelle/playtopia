<?php
include '../connection.php';
session_start();

$query = $_GET['query'] ?? '';

$sql = "SELECT * FROM songs WHERE title LIKE ? OR artist LIKE ?";
$stmt = $connect->prepare($sql);
$likeQuery = '%' . $query . '%';
$stmt->bind_param("ss", $likeQuery, $likeQuery);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()): ?>
    <div class="card" data-song-id="<?php echo $row['id_song']; ?>">
        <img src="../Assets/image/<?php echo htmlspecialchars($row['cover_path']); ?>" alt="Song">
        <div class="title"><?php echo htmlspecialchars($row['title']); ?></div>
        <div class="artist"><?php echo htmlspecialchars($row['artist']); ?></div>

        <audio class="audio-player" src="../Assets/song/<?php echo htmlspecialchars($row['file_path']); ?>" preload="none"></audio>
        <button class="play-pause-btn">Play</button>

        <div class="plays-like-row">
            <p class="plays">Plays: <span class="plays-count"><?php echo isset($row['plays']) ? $row['plays'] : 0; ?></span></p>

            <label class="container">
                <?php
                $checked = '';
                $disabled = '';
                $titleAttr = '';

                if (isset($_SESSION['id_user'])) {
                    $userId = $_SESSION['id_user'];
                    $songId = $row['id_song'];
                    $checkLikeQuery = "SELECT * FROM song_likes WHERE id_user = $userId AND id_song = $songId";
                    $likeResult = mysqli_query($connect, $checkLikeQuery);
                    if ($likeResult && mysqli_num_rows($likeResult) > 0) {
                        $checked = 'checked';
                    }
                } else {
                    $disabled = 'disabled';
                    $titleAttr = "title='Login to like songs'";
                }
                ?>
                <input type="checkbox" class="like-checkbox" data-song-id="<?php echo $row['id_song']; ?>"
                       <?php echo "$checked $disabled $titleAttr"; ?>>
                <svg id="Layer_1" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16.4,4C14.6,4,13,4.9,12,6.3C11,4.9,9.4,4,7.6,4C4.5,4,2,6.5,2,9.6C2,14,12,22,12,22s10-8,10-12.4C22,6.5,19.5,4,16.4,4z"/>
                </svg>
            </label>
        </div>

        <input type="range" class="progress-bar" value="0" min="0" step="1">
        <input type="range" class="volume-slider" min="0" max="1" step="0.01" value="0.5" title="Volume">
    </div>
<?php endwhile; ?>
