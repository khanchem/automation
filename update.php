<?php
$conn->query("ALTER TABLE matching ADD UNIQUE KEY (sku)");


$sql = "SELECT sku, stock FROM matching";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $batch = array();
    $counter = 0;

    // Loop through each row in the result set
    while ($row = $result->fetch_assoc()) {
        $sku = $row['sku'];
        $stock = $row['stock'];

        // Create an array for each SKU and stock
        $productData = array(
            'sku' => $sku,
            'stock' => $stock,
        );

        // Add the array to the batch
        $batch[] = $productData;
        $counter++;

        // If the batch size reaches 10, send the request and reset the batch
        if ($counter == 100) {
           sendBatchToApi($batch);
            $batch = array();
            $counter = 0;
        }
    }

    // If there are remaining products in the batch, send the last request
    if (!empty($batch)) {
        sendBatchToApi($batch);
    }

} else {
    echo "No matching records found in the 'matching' table.\n";
}
function sendBatchToApi($batch) {
    // Convert the batch array to JSON
    $jsonData = json_encode($batch);
    $apiKey = 'api_key';
    $apiUrl = "https://bi.agora.place/api/v2/products";

    // Initialize cURL session
    $ch = curl_init($apiUrl);

    // Set cURL options
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'apikey: ' . $apiKey,
    ]);

    // Execute cURL session and get the response
    $response = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
        // Handle curl error
    }
    // Close cURL session
    curl_close($ch);
    $updateData = json_decode($response, true);
    // Check if the response code is 200
    if (isset($updateData['code']) && $updateData['code'] !== 400) {
        echo "Products updated at Dipli successfully. Count: " . count($batch) . "\n .<br>";
        return $updateData['msg']; // Return the update result
    } else {
        // Handle API response with an error code
        echo "Error updating products at Dipli. Response: " . json_encode($updateData) . "\n";
    }
}


?>