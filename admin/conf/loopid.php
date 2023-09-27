<?php
// Generate a unique client number
$timestamp = time(); // Get current timestamp
$client_number = "iBank-CLIENT-" . $timestamp;

// ...

// Dalam loop, tambahkan nomor santri ke dalam data
for ($i = 0; $i < 10; $i++) {
    $data[] = $client_number;
}

// ...
?>
