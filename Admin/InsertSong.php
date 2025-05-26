<!DOCTYPE html>
<html>
    <body>
        <link rel="stylesheet" href="../CSS/InsertSong.css">
        <div class="form-container">
            <div class="form-header">
            <a href="../Pages/AdminMenu.php" class="back-button">Back</a>
            <h1>Insert Song</h1>
        </div>
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
    </div>
        
        <?php
            include '../connection.php';

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $title = $_POST["title"];
                $artist = $_POST["artist"];
                $album = $_POST["album"];
                $genre = $_POST["genre"];
                $release_year = $_POST["release_year"];
                $duration = $_POST["duration"];

                $audioFolder = "../Assets/song/";
                $imageFolder = "../Assets/image/";

                if (!is_dir($audioFolder)) {
                    mkdir($audioFolder, 0777, true);
                }
                if (!is_dir($imageFolder)) {
                    mkdir($imageFolder, 0777, true);
                }

                if (isset($_FILES["file_path"]) && $_FILES["file_path"]["error"] == 0) {
                    $fileName = basename($_FILES["file_path"]["name"]);
                    $tempName = $_FILES["file_path"]["tmp_name"];
                    $filePath = '../Assets/song/' . $fileName;

                    if (move_uploaded_file($tempName, $filePath)) {
                        $filePath = '../Assets/song/' . $fileName; // for DB path
                        $coverPath = "NULL";
                        if (isset($_FILES["cover_path"]) && $_FILES["cover_path"]["error"] == 0) {
                            $coverName = basename($_FILES["cover_path"]["name"]);
                            $coverTemp = $_FILES["cover_path"]["tmp_name"];
                            $coverPathFile = $imageFolder . $coverName;
                            if (move_uploaded_file($coverTemp, $coverPathFile)) {
                                $coverPath = "'../Assets/image/$coverName'";
                            }
                        }

                        $release_year = !empty($release_year) ? $release_year : "NULL";
                        $duration = !empty($duration) ? $duration : "NULL";

                        $query = "INSERT INTO songs (title, artist, album, genre, release_year, duration, file_path, cover_path)
                                VALUES ('$title', '$artist', '$album', '$genre', $release_year, $duration, '$fileName', '$coverName')";

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