<?php 

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "World_Pic";

try {
    //Sets up connection to the database
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // This is a functiion to upload a file into database
    function photoRetrieval() {
        global $conn;


        //get resulkt of photo froom sql query
        $stmt = $conn->prepare("SELECT * FROM Photo_Details WHERE photo_id");
         // Fetch the result as an associative array
         $result = $stmt->fetch(PDO::FETCH_ASSOC);

         // Check if a file was found in the database
         if ($result) {
             $fileName = $result['file_name'];
             $fileData = $result['file_data'];
 

             if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo "<tr>";
                    echo "<td>".$row["file_name"]."</td>";
                    echo "<td>".$row["file_data"]."</td>";
                 
        
                    }
            }else{
                echo "<tr><td colspan='4'>NO RESULT FOUND</td></tr>";
            }
            echo "</table>"; 
             // Set the appropriate headers for file download
             header("Content-Type: application/octet-stream");
             header("Content-Disposition: attachment; filename=$fileName");
 
             // Output the file data
             echo $fileData;
         } else {
             throw new Exception('No file found in the database.');
         }
     }
 
     // Check if a file upload form is submitted
     if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['uploaded_file'])) {
         uploadPhoto($_FILES['uploaded_file']);
     }
     photoRetrieval();
 } catch (PDOException $e) {
     // Handle database connection errors
     echo "Connection failed: " . $e->getMessage();
 } catch (Exception $e) {
     // Handle other exceptions and display an error message
     echo "Error: " . $e->getMessage();
 }
 ?>
 

