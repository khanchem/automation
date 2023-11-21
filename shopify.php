<?php
// getthing product from shopify 
$shopifyDomain = 'domain.myshopify.com';
$apiVersion = 'api_version';
$accessToken = 'token'; // Replace with your actual access token
$limit = 250;

$apiUrl = "https://$shopifyDomain/admin/api/$apiVersion/products.json?limit=$limit";

$allProducts = [];

do {
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'X-Shopify-Access-Token: ' . $accessToken
    ));

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($httpCode !== 200) {
        echo "Shopify API Error: HTTP Code $httpCode\n";
        break;
    } else {
        $data = json_decode($response, true);

        if (isset($data['errors'])) {
            // echo 'Shopify API Error: ' . $data['errors'];
            break; 
        } else {
            if (isset($data['products'])) {
                foreach ($data['products'] as $product) {
                    foreach ($product['variants'] as $variant) {
                        $sku = $variant['sku'];
                        $stock = $variant['inventory_quantity'];
                        $result = $conn->query("SELECT * FROM shopify WHERE sku = '$sku'");

                        if ($result->num_rows > 0) {
                            // Update the existing record if the SKU already exists
                            $updateSql = "UPDATE shopify SET stock = '$stock' WHERE sku = '$sku'";
                            if ($conn->query($updateSql) === TRUE) {
                               // echo "Record updated successfully for SKU: $sku\n";
                            } else {
                                echo "Error updating record: " . $conn->error . "\n";
                            }
                        } else {
                            // Insert a new record if the SKU doesn't exist
                            $insertSql = "INSERT INTO shopify (sku, stock) VALUES ('$sku', '$stock')";
                            if ($conn->query($insertSql) === TRUE) {
                              //  echo "Record inserted successfully for SKU: $sku\n";
                            } else {
                                echo "Error inserting record: " . $conn->error . "\n";
                            }
                        }
                    }
                }
            }
            //Check if there is a 'Link' header in the response for pagination
            $linkHeader = curl_getinfo($ch, CURLINFO_HEADER_OUT);
            $nextPageUrl = extractNextPageUrl($linkHeader);

            if ($nextPageUrl) {
                $apiUrl = $nextPageUrl;
            } else {
                break; // Exit the loop if there is no next page
            }
        }
    }

    curl_close($ch);

} while ($nextPageUrl);
echo "Shopify Products updated successfully.<br>";
function extractNextPageUrl($headers)
{
    $matches = [];
    if (preg_match('/Link: <([^>]*)>; rel="next"/', $headers, $matches)) {
        return isset($matches[1]) ? $matches[1] : null;
    }
    return null;
}


?>