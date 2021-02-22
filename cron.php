<?php

require __DIR__ . './vendor/autoload.php';
$africaT = require 'AfricasTalking\SDK\AfricasTalking';

$servername = "combine";
$username = "combine";
$password = "Combin3@254@2020";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";


    $username   = "Combinesms";
    $apiKey     = "cf56a93a37982301267fd00af0554c068a4efeb005213e568278c9492152ca28";

    $AT  = new $africaT($username, $apiKey);

    // Get the SMS service
    $sms        = $AT->sms();

    // Set the numbers you want to send to in international format
    $reciepients = '254713302589';

    // Set your message
    $message    = "This is a test Cron message";

    // Set your shortCode or senderId
    $from = "COMBINE";

    try {
        // Thats it, hit send and we'll take care of the rest
        $result = $sms->send([
            'to'      => $reciepients,
            'from'=>$from,
            'message' => $message,
        ]);

        // return array($result);

    } catch (Exception $e) {
        echo "Error: ".$e->getMessage();
    }
$conn->close();

?>
