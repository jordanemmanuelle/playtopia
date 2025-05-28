<?php
session_start();
include '../connection.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: ../LoginRegister/FormLogin.html");
    exit();
}

$currentUser = $_SESSION['id_user'];
$message = "";

function cleanInput($data)
{
    return trim(strip_tags($data));
}

if (isset($_POST['add_friend'])) {
    $friendUsername = cleanInput($_POST['friend_username']);
    $friendUsername = mysqli_real_escape_string($connect, $friendUsername);

    $query = "SELECT id_user FROM users WHERE username = '$friendUsername'";
    $result = mysqli_query($connect, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        $friendId = $row['id_user'];

        if ($friendId == $currentUser) {
            $message = "You cannot add yourself as a friend.";
        } else {
            $check = "SELECT * FROM friends WHERE 
                (id_user1 = $currentUser AND id_user2 = $friendId) OR (id_user1 = $friendId AND id_user2 = $currentUser)";
            $resCheck = mysqli_query($connect, $check);
            if (!$resCheck) {
                $message = "Error checking friend status: " . mysqli_error($connect);
            } elseif (mysqli_num_rows($resCheck) > 0) {
                $message = "Friend request already sent or you are already friends.";
            } else {
                $insert = "INSERT INTO friends (id_user1, id_user2, status) VALUES ($currentUser, $friendId, 'pending')";
                if (mysqli_query($connect, $insert)) {
                    $message = "Friend request sent to " . htmlspecialchars($friendUsername) . ".";
                } else {
                    $message = "Failed to send friend request.";
                }
            }
        }
    } else {
        $message = "User '" . htmlspecialchars($friendUsername) . "' not found.";
    }
}

if (isset($_POST['action']) && isset($_POST['request_from'])) {
    $action = $_POST['action'];
    $requestFrom = (int) $_POST['request_from'];

    if ($action == 'accept') {
        $update = "UPDATE friends SET status = 'accepted' WHERE id_user1 = $requestFrom AND id_user2 = $currentUser AND status = 'pending'";
        mysqli_query($connect, $update);
        $message = "Friend request accepted.";
    } elseif ($action == 'decline') {
        $delete = "DELETE FROM friends WHERE id_user1 = $requestFrom AND id_user2 = $currentUser AND status = 'pending'";
        mysqli_query($connect, $delete);
        $message = "Friend request declined.";
    }
}


$requests = [];
$sqlReq = "SELECT f.id_user1, u.username FROM friends f JOIN users u ON f.id_user1 = u.id_user WHERE f.id_user2 = $currentUser AND f.status = 'pending'";
$resReq = mysqli_query($connect, $sqlReq);
while ($row = mysqli_fetch_assoc($resReq)) {
    $requests[] = $row;
}

$friends = [];
$sqlFriends = "SELECT u.id_user, u.username FROM users u JOIN friends f ON 
    ((f.id_user1 = u.id_user AND f.id_user2 = $currentUser) OR (f.id_user2 = u.id_user AND f.id_user1 = $currentUser))
    WHERE f.status = 'accepted' AND u.id_user != $currentUser";
$resFriends = mysqli_query($connect, $sqlFriends);
while ($row = mysqli_fetch_assoc($resFriends)) {
    $friends[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Friends</title>
    <link rel="stylesheet" href="../CSS/Friends.css">

</head>

<body>
    <button class="btn-back" onclick="window.history.back()">← Back</button>

    <h1>Friends</h1>

    <?php if ($message != "")
        echo "<p><b>$message</b></p>"; ?>


    <h2>Add a Friend</h2>
    <form method="POST" action="">
        <input type="text" name="friend_username" placeholder="Enter username" required />
        <button type="submit" name="add_friend">Send Request</button>
    </form>



    <h2>Friend Requests</h2>
    <?php if (count($requests) > 0): ?>
        <?php foreach ($requests as $req): ?>
            <div>
                <strong><?php echo htmlspecialchars($req['username']); ?></strong> wants to be your friend.
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="request_from" value="<?php echo (int) $req['id_user1']; ?>" />
                    <button type="submit" name="action" value="accept">Accept</button>
                    <button type="submit" name="action" value="decline">Decline</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No friend requests.</p>
    <?php endif; ?>



    <h2>Your Friends</h2>
    <?php if (count($friends) > 0): ?>
        <ul>
            <?php foreach ($friends as $f): ?>
                <li><?php echo htmlspecialchars($f['username']); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>You have no friends yet.</p>
    <?php endif; ?>

</body>

</html>