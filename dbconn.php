<?php 
// Sets the names of server of which the data base will connect
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "World_Pic";

$conn = new mysqli($servername, $username,$password,$dbname);

if ($conn->connect_error) {
    die("CONNECTION FAILED: ". $conn->connect_error);
}
// echo "SUCCESS <br>";

?>