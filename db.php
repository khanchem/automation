<?php

$tableExists = $conn->query("SHOW TABLES LIKE 'shopify'")->num_rows > 0;
if (!$tableExists) {
    $sql = "CREATE TABLE shopify (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        sku VARCHAR(300) NOT NULL,
        stock VARCHAR(30) NOT NULL,
        updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );";

    if ($conn->query($sql) === TRUE) {
        echo "Table 'shopify' created successfully .<br>";
    } else {
        echo "Error creating table 'shopify': " . $conn->error;
    }
} 
// Check if the 'dipli' table exists
$tableExists = $conn->query("SHOW TABLES LIKE 'dipli'")->num_rows > 0;
if (!$tableExists) {
    $sql = "CREATE TABLE dipli (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        sku VARCHAR(300) NOT NULL,
        name VARCHAR(300) NOT NULL,
        updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );";

    if ($conn->query($sql) === TRUE) {
        echo "Table 'dipli' created successfully .<br>";
    } else {
        echo "Error creating table 'dipli': " . $conn->error;
    }
} 
// Check if the 'matching' table exists
$tableExists = $conn->query("SHOW TABLES LIKE 'matching'")->num_rows > 0;
if (!$tableExists) {
    $sql = "CREATE TABLE matching (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        sku VARCHAR(300) NOT NULL,
        stock VARCHAR(30) NOT NULL,
        updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );";

    if ($conn->query($sql) === TRUE) {
        echo "Table 'matching' created successfully .<br>";
    } else {
        echo "Error creating table 'matching': " . $conn->error;
    }
} 
?>