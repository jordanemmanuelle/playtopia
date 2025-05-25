<!DOCTYPE html>
<html>
    <body>
        <h1>All Table</h1>
        <h3>Tabel Songs</h3>
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
                    </tr>";

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row["id_song"] . "</td>";
                    echo "<td>" . $row["title"] . "</td>";
                    echo "<td>" . $row["artist"] . "</td>";
                    echo "<td>" . $row["album"] . "</td>";
                    echo "<td>" . $row["genre"] . "</td>";
                    echo "<td>" . $row["release_year"] . "</td>";
                    echo "<td>" . $row["duration"] . "</td>";
                    echo "<td>" . $row["file_path"] . "</td>";
                    echo "<td>" . $row["cover_path"] . "</td>";
                    echo "<td>" . $row["plays"] . "</td>";
                    echo "<td><a href='editSong.php?id=" . $row["id_song"] . "'>Edit</a></td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "Data tidak ada";
            }
            echo "Total data: " . mysqli_num_rows($result) . "<br>";

            mysqli_close($connect);
        ?>

        <h3>Tabel Playlist</h3>
    </body>
</html>