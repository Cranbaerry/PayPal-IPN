<?php
spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});

$ipn = new PayPal\IPN();

// Use the sandbox endpoint during testing.
$ipn->useSandbox();
$verified = $ipn->verifyIPN();
if ($verified) {
    /*
     * Process IPN
     * A list of variables is available here:
     * https://developer.paypal.com/webapps/developer/docs/classic/ipn/integration-guide/IPNandPDTVariables/
     */

    $client = new WebSocket\Client("ws://localhost:8887");
    $client->text(json_encode($_POST));
    echo $client->receive();
    $client->close();
}

// Reply with an empty 200 response to indicate to paypal the IPN was received correctly.
header("HTTP/1.1 200 OK");
