<?php
// Read the variables sent via POST from our API
$sessionId   = $_POST["sessionId"] ?? null;
$serviceCode = $_POST["serviceCode"] ?? null;
$phoneNumber = $_POST["phoneNumber"] ?? null;
$text        = $_POST["text"] ?? null;

// Modify this variable with your Ngrok URL
$ngrokUrl = "https://ccc7-41-216-73-20.ngrok-free.app";

// Initialize $response
$response = "";

if ($text == "") {
    // This is the first request. Note how we start the response with CON
    $response  = "CON What would you want to check \n";
    $response .= "1. My Account \n";
    $response .= "2. My phone number";

} else if ($text == "1") {
    // Business logic for the first level response
    $response = "CON Choose account information you want to view \n";
    $response .= "1. Account number \n";

} else if ($text == "2") {
    // Business logic for the first level response
    // This is a terminal request. Note how we start the response with END
    $response = "END Your phone number is " . $phoneNumber;

} else if ($text == "1*1") { 
    // This is a second level response where the user selected 1 in the first instance
    $accountNumber  = "ACC1001";

    // This is a terminal request. Note how we start the response with END
    $response = "END Your account number is " . $accountNumber;
}

// Debugging output
error_log("Response: " . $response);
error_log("Ngrok URL: " . $ngrokUrl);

// Echo the response back to the API
header('Content-type: text/plain');
echo $response;

// Send the response to Africa's Talking with the Ngrok URL
sendResponse($response, $ngrokUrl);

// Function to send a response to Africa's Talking
function sendResponse($response, $callbackUrl) {
    $postData = "phoneNumber=" . urlencode($_POST["phoneNumber"]) . "&text=" . urlencode($response);

    // Debugging output
    error_log("Sending POST request to: " . $callbackUrl);
    error_log("POST data: " . $postData);

    $ch = curl_init($callbackUrl);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/x-www-form-urlencoded',
        'Content-Length: ' . strlen($postData))
    );

    $result = curl_exec($ch);
    curl_close($ch);

    // Debugging output
    error_log("POST request result: " . $result);
}
?>
