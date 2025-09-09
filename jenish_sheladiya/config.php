<?php
$servername = "localhost"; // don't add port or URL here
$username = "root";         // your DB username
$password = "";             // your DB password
$dbname = "jenish_sheladiya";    // your DB name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
