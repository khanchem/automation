<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// DATABASE CREATE 
include('db.php');
// Get Product SKu and Stock from shopify and store it in table
include('shopify.php');

// Get Product SKu  from dipli and store it in table
include('dipli.php');
//matching sku of both shopify and dipli
include('matching.php');
//update stock dipli
include('update.php');

$conn->close();
?>