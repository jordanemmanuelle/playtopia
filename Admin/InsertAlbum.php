<!DOCTYPE html>
<html>
    <body>
        <h1>Insert Album</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <label>Album Name:</label><br>
            <input type="text" name="album_name" required><br><br>

            <label>Artist:</label><br>
            <input type="text" name="artist" required><br><br>

            <label>Release Year:</label><br>
            <input type="number" name="release_year" required><br><br>

            <label>Cover Image:</label><br>
            <input type="file" name="cover_image" accept="image/*" required><br><br>

            <input type="submit" value="Insert Album">
        </form>
    </body>
</html>