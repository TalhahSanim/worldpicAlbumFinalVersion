<?php 
session_start();
include("dbconn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION["user_id"];
    $comment = $_POST["comment"];
    $file_name = $_FILES["photo"]["name"];
    $file_tmp = $_FILES["photo"]["tmp_name"];

    // Check file type 
    $file_type = pathinfo($file_name, PATHINFO_EXTENSION);
    if ($file_type != "jpg" && $file_type != "png" && $file_type != "jpeg") {
        echo "Invalid file type. Only JPG, JPEG, and PNG are allowed.";
    } else {
        // Specify the path where you want to save the image
        $target_dir = "photos/";
        $target_file = $target_dir . basename($file_name);

        // Move the uploaded file to your target directory
        if (move_uploaded_file($file_tmp, $target_file)) {
            // Insert data into the database
            $sql = "INSERT INTO Photo_Details (photo_id, user_id, comment, file_name) 
                    VALUES (?, ?, ?, ?)";
            if ($stmt = $conn->prepare($sql)) {
                // Bind variables to the prepared statement as parameters
                $stmt->bind_param("ssss", $user_id, $user_id, $comment, $file_name);

                // Attempt to execute the prepared statement
                if ($stmt->execute()) {
                    echo "New record created successfully";
                } else {
                    echo "Error: " . $stmt->error;
                }
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }

        header("Location: dashboard.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload A PHOTO</title>
</head>
<body>
    <h1>UPLOADS</h1>

    <!-- File Upload Form -->
    <form action="uploadphoto.php" method="post" enctype="multipart/form-data">
        <label for="photo">Choose a file:</label>
        <input type="file" name="photo" id="photo" required>
        <br>
        <label for='comment'>Enter a comment :</label>
        <input type='text' id='comment' name='comment' />
        <input type="submit" value="Upload">

</form>

<form action="dashboard.php" method="post">
    <button type="submit">RETURN TO DASHBOARD</button>
</form>
     

  

</body>
</html>
