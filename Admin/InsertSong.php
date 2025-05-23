<!DOCTYPE html>
<html>
    <body>
        <h1>Insert Song</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <label for="title">Title:</label><br>
            <input type="text" id="title" name="title" required><br><br>

            <label for="artist">Artist:</label><br>
            <input type="text" id="artist" name="artist" required><br><br>

            <label for="album">Album:</label><br>
            <input type="text" id="album" name="album"><br><br>

            <label for="genre">Genre:</label><br>
            <input type="text" id="genre" name="genre"><br><br>

            <label for="release_year">Release Year:</label><br>
            <input type="number" id="release_year" name="release_year" min="1900" max="2099"><br><br>

            <label for="duration">Duration (seconds):</label><br>
            <input type="number" id="duration" name="duration" min="0"><br><br>

            <label for="file_path">Song File:</label><br>
            <input type="file" id="file_path" name="file_path" accept="audio/*" required><br><br>

            <label for="cover_path">Cover Image:</label><br>
            <input type="file" id="cover_path" name="cover_path" accept="image/*"><br><br>


            <input type="submit" value="Insert Song">
        </form>

        <?php
            include '../connection.php';

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $title = $_POST["title"];
                $artist = $_POST["artist"];
                $album = $_POST["album"];
                $genre = $_POST["genre"];
                $release_year = $_POST["release_year"];
                $duration = $_POST["duration"];

                $folder = "uploads/";
                if (!is_dir($folder)) {
                    mkdir($folder, 0777, true);
                }

                if (isset($_FILES["file_path"]) && $_FILES["file_path"]["error"] == 0) {
                    $fileName = basename($_FILES["file_path"]["name"]);
                    $tempName = $_FILES["file_path"]["tmp_name"];
                    $filePath = $folder . $fileName;

                    if (move_uploaded_file($tempName, $filePath)) {
                        $coverPath = "NULL";
                        if (isset($_FILES["cover_path"]) && $_FILES["cover_path"]["error"] == 0) {
                            $coverFolder = $folder . "covers/";
                            if (!is_dir($coverFolder)) {
                                mkdir($coverFolder, 0777, true);
                            }
                            $coverName = basename($_FILES["cover_path"]["name"]);
                            $coverTemp = $_FILES["cover_path"]["tmp_name"];
                            $coverPathFile = $coverFolder . $coverName;
                            if (move_uploaded_file($coverTemp, $coverPathFile)) {
                                $coverPath = "'$coverPathFile'";
                            }
                        }           

                        $release_year = !empty($release_year) ? $release_year : "NULL";
                        $duration = !empty($duration) ? $duration : "NULL";

                        $query = "INSERT INTO songs (title, artist, album, genre, release_year, duration, file_path, cover_path)
                                VALUES ('$title', '$artist', '$album', '$genre', $release_year, $duration, '$filePath', $coverPath)";

                        if (mysqli_query($connect, $query)) {
                            echo "<script>alert('Lagu berhasil dimasukkan!');</script>";
                        } else {
                            echo "<script>alert('Gagal memasukkan lagu: " . mysqli_error($connect) . "');</script>";
                        }
                    } else {
                        echo "<script>alert('Gagal upload file lagu.');</script>";
                    }
                } else {
                    echo "<script>alert('Tidak ada file lagu yang diupload.');</script>";
                }
                mysqli_close($connect);
            }
        ?>

    </body>
</html>
