<?php
// Handle form submission
$successMsg = $errorMsg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include '../connection.php';

    // Album info
    $album_name = mysqli_real_escape_string($connect, $_POST['album']);
    $artist = mysqli_real_escape_string($connect, $_POST['artist']);
    $genre = mysqli_real_escape_string($connect, $_POST['genre']);
    $release_year = intval($_POST['release_year']);

    // Handle cover upload
    $cover_path = null;
    if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
        $coverTmp = $_FILES['cover']['tmp_name'];
        $coverName = uniqid('cover_') . '_' . basename($_FILES['cover']['name']);
        $coverDir = '../uploads/covers/';
        if (!is_dir($coverDir)) mkdir($coverDir, 0777, true);
        $cover_path = $coverDir . $coverName;
        move_uploaded_file($coverTmp, $cover_path);
    }

    // Insert album
    $stmt = $connect->prepare("INSERT INTO album (album_name, artist, genre, release_year, cover_path) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssis", $album_name, $artist, $genre, $release_year, $cover_path);
    if ($stmt->execute()) {
        $album_id = $stmt->insert_id;

        // Insert new songs
        if (!empty($_POST['titles']) && isset($_FILES['files'])) {
            $titles = $_POST['titles'];
            $files = $_FILES['files'];
            $songDir = '../uploads/songs/';
            if (!is_dir($songDir)) mkdir($songDir, 0777, true);

            for ($i = 0; $i < count($titles); $i++) {
                $title = mysqli_real_escape_string($connect, $titles[$i]);
                if ($files['error'][$i] === UPLOAD_ERR_OK) {
                    $songTmp = $files['tmp_name'][$i];
                    $songName = uniqid('song_') . '_' . basename($files['name'][$i]);
                    $songPath = $songDir . $songName;
                    move_uploaded_file($songTmp, $songPath);

                    // Insert song
                    $stmtSong = $connect->prepare("INSERT INTO songs (title, artist, album, genre, release_year, file_path, cover_path) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmtSong->bind_param("ssssiss", $title, $artist, $album_name, $genre, $release_year, $songPath, $cover_path);
                    $stmtSong->execute();

                    // Link song to album (if you have a linking table, add here)
                }
            }
        }

        // Add existing songs to album (update their album field)
        if (!empty($_POST['existing_songs'])) {
            $existing = $_POST['existing_songs'];
            foreach ($existing as $song_id) {
                $song_id = intval($song_id);
                $stmtUpdate = $connect->prepare("UPDATE songs SET album = ? WHERE id_song = ?");
                $stmtUpdate->bind_param("si", $album_name, $song_id);
                $stmtUpdate->execute();
            }
        }

        $successMsg = "Album and songs successfully inserted!";
    } else {
        $errorMsg = "Failed to insert album: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Insert Album</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f7fafc;
            margin: 0;
            padding: 0;
        }
        h1 {
            color: #1e293b;
            text-align: center;
            margin-top: 30px;
        }
        form {
            background: #fff;
            max-width: 700px;
            margin: 30px auto;
            padding: 30px 40px 20px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
        }
        label {
            font-weight: 500;
            margin-top: 8px;
        }
        input[type="text"], input[type="number"], input[type="file"] {
            width: 95%;
            padding: 7px;
            margin-bottom: 10px;
            border: 1px solid #bbb;
            border-radius: 5px;
            font-size: 15px;
        }
        .song-item {
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            padding: 12px 18px 8px 18px;
            margin-bottom: 12px;
            position: relative;
            transition: box-shadow 0.2s;
            animation: fadeIn 0.4s;
        }
        .song-item:hover {
            box-shadow: 0 2px 8px rgba(30,64,175,0.07);
        }
        button[type="button"] {
            background: #e11d48;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 6px 14px;
            font-size: 14px;
            cursor: pointer;
            margin-top: 6px;
            transition: background 0.2s;
        }
        button[type="button"]:hover {
            background: #be123c;
        }
        #song-fields {
            margin-bottom: 10px;
        }
        .existing-songs {
            background: #fef9c3;
            border: 1px solid #fde047;
            border-radius: 7px;
            padding: 18px;
            margin-bottom: 18px;
        }
        #existing-songs-list {
            margin-top: 10px;
            min-height: 40px;
            max-height: 300px;
            overflow-y: auto;
        }
        .song-checkbox label {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 4px 0;
            cursor: pointer;
            transition: background 0.2s;
        }
        .song-checkbox label:hover {
            background: #fef08a;
            border-radius: 4px;
        }
        input[type="submit"] {
            background: #22c55e;
            color: #fff;
            border: none;
            border-radius: 7px;
            padding: 15px 30px;
            font-size: 18px;
            cursor: pointer;
            margin-top: 10px;
            transition: background 0.2s;
        }
        input[type="submit"]:hover {
            background: #16a34a;
        }
        #existing-songs-list.loading::before {
            content: '';
            display: inline-block;
            width: 22px;
            height: 22px;
            border: 3px solid #fde047;
            border-top: 3px solid #facc15;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-right: 8px;
            vertical-align: middle;
        }
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #10b981;
            border-radius: 7px;
            padding: 12px 18px;
            margin: 18px auto;
            max-width: 700px;
            text-align: center;
        }
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #ef4444;
            border-radius: 7px;
            padding: 12px 18px;
            margin: 18px auto;
            max-width: 700px;
            text-align: center;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes spin {
            100% { transform: rotate(360deg); }
        }
    </style>
    <script>
        function addSongField() {
            const container = document.getElementById("song-fields");
            const count = container.getElementsByClassName("song-item").length;

            if (count >= 35) {
                alert("Maksimal 35 lagu.");
                return;
            }

            const div = document.createElement("div");
            div.className = "song-item";
            div.innerHTML = `
                <label><strong>Song ${count + 1}:</strong></label><br>
                <label>Title:</label><br>
                <input type="text" name="titles[]" required><br>
                <label>Audio File:</label><br>
                <input type="file" name="files[]" accept="audio/*" required><br>
                <button type="button" onclick="removeSongField(this)">🗑️ Remove Song</button>
                <hr>
            `;
            container.appendChild(div);
        }

        function removeSongField(button) {
            const container = document.getElementById("song-fields");
            const count = container.getElementsByClassName("song-item").length;
            if (count > 2) {
                container.removeChild(button.parentElement);
                updateSongNumbers();
            } else {
                alert("Minimal 2 lagu.");
            }
        }

        function updateSongNumbers() {
            const songItems = document.getElementsByClassName("song-item");
            for (let i = 0; i < songItems.length; i++) {
                const label = songItems[i].getElementsByTagName("label")[0];
                label.innerHTML = `<strong>Song ${i + 1}:</strong>`;
            }
        }

        function toggleAllSongs() {
            const checkboxes = document.querySelectorAll('input[name="existing_songs[]"]');
            const selectAll = document.getElementById('select-all');
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
        }

        window.onload = () => {
            // Add initial 2 song fields
            for (let i = 0; i < 2; i++) {
                addSongField();
            }
            // Load existing songs
            loadExistingSongs();
        };

        function loadExistingSongs() {
            const list = document.getElementById('existing-songs-list');
            list.classList.add('loading');
            list.innerHTML = '';
            fetch('../Pages/get_existing_songs.php')
                .then(response => response.text())
                .then(data => {
                    list.classList.remove('loading');
                    list.innerHTML = data;
                })
                .catch(error => {
                    list.classList.remove('loading');
                    list.innerHTML = "<span style='color:red;'>Failed to load songs.</span>";
                    console.error('Error loading existing songs:', error);
                });
        }
    </script>
</head>
<body>
    <h1>🎵 Insert New Album</h1>
    <?php if ($successMsg): ?>
        <div class="alert-success"><?= htmlspecialchars($successMsg) ?></div>
    <?php elseif ($errorMsg): ?>
        <div class="alert-error"><?= htmlspecialchars($errorMsg) ?></div>
    <?php endif; ?>
    <form action="InsertAlbum.php" method="post" enctype="multipart/form-data">
        <h2>Album Information</h2>
        <label><strong>Album Name:</strong></label><br>
        <input type="text" name="album" required><br><br>

        <label><strong>Artist:</strong></label><br>
        <input type="text" name="artist" required><br><br>

        <label><strong>Genre:</strong></label><br>
        <input type="text" name="genre" placeholder="e.g., Pop, Rock, Jazz"><br><br>

        <label><strong>Release Year:</strong></label><br>
        <input type="number" name="release_year" min="1900" max="2030" required><br><br>

        <label><strong>Album Cover Image:</strong></label><br>
        <input type="file" name="cover" accept="image/*" required><br><br>

        <hr>
        <h2>🎶 New Songs (min 2 - max 35):</h2>
        <p><em>All songs will inherit the album's artist, genre, release year, and cover image.</em></p>
        <div id="song-fields"></div>
        <button type="button" onclick="addSongField()">➕ Add Another Song</button><br><br>
        <hr>
        <h2>📂 Add Existing Songs to This Album</h2>
        <div class="existing-songs">
            <p><em>Select existing songs in your database to add them to this album:</em></p>
            <label>
                <input type="checkbox" id="select-all" onchange="toggleAllSongs()">
                <strong>Select All</strong>
            </label>
            <div id="existing-songs-list">
                Loading existing songs...
            </div>
        </div>
        <hr>
        <input type="submit" value="🚀 Create Album">
    </form>
</body>
</html>