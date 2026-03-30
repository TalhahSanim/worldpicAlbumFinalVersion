<?php
// deletephoto.php

session_start();
include("dbconn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["photo_id"])) {
    $photo_id = $_POST["photo_id"];
    $user_id = $_SESSION["user_id"];

    // Write a query to check if the photo belongs to the logged-in user
    $sql = "SELECT file_name FROM Photo_Details WHERE photo_id=? AND user_id=?";
    if ($stmt = $conn->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("ss", $photo_id, $user_id);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            $result = $stmt->get_result();
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    if ($result->num_rows == 1) {
        // Delete photo file
        $file_name = $result->fetch_assoc()["file_name"];
        unlink("photos/" . $file_name);

        // Write a query to delete record from the database
        $sql = "DELETE FROM Photo_Details WHERE photo_id=? AND user_id=?";
        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ss", $photo_id, $user_id);

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                header("Location: dashboard.php");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    } else {
        $error = "Invalid photo ID";
    }
}

// Display error or confirmation message and all form details here
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Photo</title>
</head>
<body>
    <h1>DELETE PHOTOS</h1>

    <?php if (isset($error)): ?>
        <p><?php echo $error; ?></p>
    <?php endif; ?>

    <form action="dashboard.php" method="post">
        <button type="submit">RETURN TO DASHBOARD</button>
    </form>
</body>
</html>
