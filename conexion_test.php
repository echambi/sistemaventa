<?php
$servername = "striped-symbol-459118-d9:us-central1:root";
$username = "root";
$password = "Seguridad01";

// Create connection
$conn = mysqli_connect($servername, $username, $password);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully";
?>




