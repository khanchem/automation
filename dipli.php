<?php

$api_key = "your_api_key"; // Replace with your actual API key
$page = 1;

while (true) {
    // Create the API URL with the current page number
    $url = "https://bi.agora.place/api/v2/products?pageSize=100&page=$page";

    // Initialize cURL session
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "apikey: " . $api_key
    ));
    $response = curl_exec($ch);


    $json = json_decode($response, true);


    if (is_array($json) && isset($json['result']) && is_array($json['result'])) {
  
        foreach ($json['result'] as $product) {
   
            $sku = $product['sku'];
              $name = $product['name'];

            // Check if the SKU already exists in 'dipli' table
            $result = $conn->query("SELECT * FROM dipli WHERE sku = '$sku'");

            if ($result->num_rows === 0) {
                // Insert a new record if the SKU doesn't exist
                $insertSql = "INSERT INTO dipli (sku, name) VALUES ('$sku', '$name')";
                if ($conn->query($insertSql) === TRUE) {
                   // echo "Record inserted successfully for SKU: $sku\n";
                } else {
                    echo "Error inserting record: " . $conn->error . "\n";
                }
            } else {
                //echo "SKU already exists in 'dipli' table: $sku\n";
            }
        }

        // Check if there are more pages
        if (isset($json['count'], $json['pageSize'], $json['page']) && $json['count'] > $json['pageSize'] * $json['page']) {
            $page++;
        } else {
          
            break;
        }
    } else {
        // Handle invalid or empty JSON response
        echo "Error: Invalid or empty JSON response.\n";
        break;
    }

 
    curl_close($ch);

    // sleep(1); 
}
echo "Dipli Products updated successfully.<br>";
?>