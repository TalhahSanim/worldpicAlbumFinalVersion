<?php
// update.php

session_start();
include("dbconn.php");
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["new_email"]) && !empty($_POST["new_password"])) {
    $user_id = $_SESSION["user_id"];
    $new_email = $_POST["new_email"];
    $new_password = $_POST["new_password"];

    // Write a query to update user details in the database
    $sql = "UPDATE login_credentials SET email=?, password=? WHERE user_id=?";
    if ($stmt = $conn->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("sss", $new_email, $new_password, $user_id);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            $message = "Details updated successfully!";
        } else {
            $message = "Error: " . $stmt->error;
        }
    }
}

// Display update form
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Details</title>
</head>
<body>
    <h1>Change Details</h1>

    <?php if (!empty($message)): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <form action="updatedetails.php" method="post">
        <label for="new_email">New Email:</label><br>
        <input type="email" id="new_email" name="new_email" required><br>
        <label for="new_password">New Password:</label><br>
        <input type="password" id="new_password" name="new_password" required><br>
        <button type="submit">UPDATE DETAILS</button>
    </form>

    <form action="dashboard.php" method="post">
        <button type="submit">RETURN TO DASHBOARD</button>
    </form>
</body>
</html>
