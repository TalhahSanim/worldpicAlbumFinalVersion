<?php
session_start(); // to start the session
include("dbconn.php");

//redirect to login page if not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// Fetch all images uploaded by the user
$sql = "SELECT * FROM Photo_Details WHERE user_id=?";
if ($stmt = $conn->prepare($sql)) {
    // Bind variables to the prepared statement as parameters
    $stmt->bind_param("s", $user_id);

    // Attempt to execute the prepared statement
    if ($stmt->execute()) {
        $result = $stmt->get_result();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <!-- Display photos -->
    <?php while ($photo = $result->fetch_assoc()): ?>
        <div>
            <h2>Photo ID: <?php echo $photo['photo_id']; ?></h2>
            <form action="deletephoto.php" method="post">
                <input type="hidden" name="photo_id" value="<?php echo $photo['photo_id']; ?>">
                <button type="submit">
                    <img src="photos/<?php echo $photo['file_name']; ?>" alt="Uploaded photo">
                </button>
            </form>
            <p><?php echo $photo['comment']; ?></p>
        </div>
    <?php endwhile; ?>

    <!-- File Upload Form -->
    <form action="uploadphoto.php" method="post">
    <button type="submit">ENTER UPLOADS</button>
</form>
     

<form action="updatedetails.php" method="post">
    <button type="submit">CHANGE ACCOUNT DETAILS</button>
</form>

<form action="login.php" method="post">
    <button type="submit">RETURN TO LOGIN</button>
</form>
  

</body>
</html>
