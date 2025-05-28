
<!DOCTYPE html>
<html>
<head>
    <title>Insert Album</title>
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