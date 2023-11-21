<?php
// Ensure that 'sku' in the 'matching' table is a unique key
$conn->query("ALTER TABLE matching ADD UNIQUE KEY (sku)");

// Use a single SQL query to insert/update records for matching SKUs
$sql = "INSERT INTO matching (sku, stock)
        SELECT d.sku, s.stock
        FROM dipli d
        JOIN shopify s ON d.sku = s.sku
        ON DUPLICATE KEY UPDATE stock = s.stock";

if ($conn->query($sql) === TRUE) {
    echo "Matching records inserted/updated successfully\n .<br>";
} else {
    echo "Error inserting/updating records: " . $conn->error . "\n .<br>";
}

?>