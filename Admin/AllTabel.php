<!DOCTYPE html>
<html>

<body>

    <h1>All Table</h1>
    <a href="../Pages/AdminMenu.php" style="display:block; width:fit-content; margin:25px auto 10px auto; background-color:#232323; color:#cbae6d; border:2px solid #cbae6d; padding:10px 28px; border-radius:8px; font-size:1.05em; font-weight:bold; text-align:center; transition:background 0.3s, color 0.3s, border 0.3s; box-shadow:0 2px 8px rgba(0,0,0,0.18); letter-spacing:0.5px;" class="back-button">Back</a>

    <h3>Tabel Songs</h3>
    <link rel="stylesheet" href="../CSS/AllTabel.css">
    <link rel="stylesheet" href="../CSS/InsertSong.css">

    <?php

    include '../connection.php';
    $result = mysqli_query($connect, "SELECT * FROM songs");

    if (mysqli_num_rows($result) > 0) {
        echo "<table border='1'>";
        echo "<tr>
                        <th>Id Song</th>
                        <th>Title</th>
                        <th>Artist</th>
                        <th>Album</th>
                        <th>Genre</th>
                        <th>Release Year</th>
                        <th>Duration</th>
                        <th>File Path</th>
                        <th>Cover Path</th>
                        <th>Plays</th>
                        <th>Edit</th>
                        <th>Hapus</th>
                    </tr>";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row["id_song"] . "</td>";
            echo "<td data-label='Title'>" . $row["title"] . "</td>";
            echo "<td>" . $row["artist"] . "</td>";
            echo "<td>" . $row["album"] . "</td>";
            echo "<td>" . $row["genre"] . "</td>";
            echo "<td>" . $row["release_year"] . "</td>";
            echo "<td>" . $row["duration"] . "</td>";
            echo "<td>" . $row["file_path"] . "</td>";
            echo "<td>" . $row["cover_path"] . "</td>";
            echo "<td>" . $row["plays"] . "</td>";
            echo "<td><a href='editSong.php?id=" . $row["id_song"] . "'>Edit</a></td>";
            echo "<td><a href='deleteSong.php?id=" . $row["id_song"] . "' onclick=\"return confirm('Yakin ingin hapus lagu ini?')\">Hapus</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "Data tidak ada";
    }
    echo "<div style='text-align:center;'>Total data: " . mysqli_num_rows($result) . "</div><br>";

    ?>

    <h3>Tabel Playlist</h3>

    <?php
    $resultPlaylist = mysqli_query($connect, "SELECT * FROM playlists");

    if (mysqli_num_rows($resultPlaylist) > 0) {
        echo "<table border='1'>";
        echo "<tr>
                <th>Id Playlist</th>
                <th>User ID</th>
                <th>Playlist Name</th>
                <th>Description</th>
                <th>Cover URL</th>
                <th>Created At</th>
                <th>Edit</th>
                <th>Hapus</th>
              </tr>";

        while ($row = mysqli_fetch_assoc($resultPlaylist)) {
            echo "<tr>";
            echo "<td data-label='Id Playlist'>" . $row["id_playlist"] . "</td>";
            echo "<td data-label='User ID'>" . $row["id_user"] . "</td>";
            echo "<td data-label='Playlist Name'>" . $row["playlist_name"] . "</td>";
            echo "<td data-label='Description'>" . $row["description"] . "</td>";
            echo "<td data-label='Cover URL'>" . $row["cover_url"] . "</td>";
            echo "<td data-label='Created At'>" . $row["created_at"] . "</td>";
            echo "<td><a href='editPlaylist.php?id=" . $row["id_playlist"] . "'>Edit</a></td>";
            echo "<td><a href='deletePlaylist.php?id=" . $row["id_playlist"] . "' onclick=\"return confirm('Yakin ingin hapus playlist ini?')\">Hapus</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "Data tidak ada";
    }
    echo "<div style='text-align:center;'>Total data: " . mysqli_num_rows($resultPlaylist) . "</div><br>";
    ?>

    <h3>Tabel Users</h3>
    <?php
    $resultUsers = mysqli_query($connect, "SELECT * FROM users");

    if (mysqli_num_rows($resultUsers) > 0) {
        echo "<table border='1'>";
        echo "<tr>
                <th>Id User</th>
                <th>Username</th>
                <th>Email</th>
                <th>Password</th>
                <th>Created At</th>
                <th>User Type</th>
                <th>Edit</th>
                <th>Hapus</th>
              </tr>";

        while ($row = mysqli_fetch_assoc($resultUsers)) {
            echo "<tr>";
            echo "<td data-label='Id User'>" . $row["id_user"] . "</td>";
            echo "<td data-label='Username'>" . $row["username"] . "</td>";
            echo "<td data-label='Email'>" . $row["email"] . "</td>";
            echo "<td data-label='Password'>" . $row["password"] . "</td>";
            echo "<td data-label='Created At'>" . $row["created_at"] . "</td>";
            echo "<td data-label='User Type'>" . $row["user_type"] . "</td>";
            echo "<td><a href='editUser.php?id=" . $row["id_user"] . "'>Edit</a></td>";
            echo "<td><a href='deleteUser.php?id=" . $row["id_user"] . "' onclick=\"return confirm('Yakin ingin hapus user ini?')\">Hapus</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "Data tidak ada";
    }

    echo "<div style='text-align:center;'>Total data: " . mysqli_num_rows($resultUsers) . "</div><br>";
    ?>


</body>

</html>