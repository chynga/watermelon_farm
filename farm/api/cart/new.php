<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization,X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Cart.php';
// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate cart object
$cart = new Cart($db);

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));

// Clean and set data
$cart->user_id = htmlspecialchars(strip_tags($data->user_id));
$cart->watermelon_id = htmlspecialchars(strip_tags($data->watermelon_id));

// Create Cart
try {
    if ($cart->create()) {
        echo json_encode(
            array('message' => 'Cart Created')
        );
    } else {
        echo json_encode(
            array('message' => 'Cart Not Created')
        );
    }
} catch (Exception $e) {
    echo 'Connection Error: ' . $e->getMessage();
}

