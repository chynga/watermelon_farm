<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization,X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Order.php';
// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate cart object
$order = new Order($db);

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));

// Clean and set data
$order->id = htmlspecialchars(strip_tags($data->id));
$order->city = htmlspecialchars(strip_tags($data->city));
$order->street = htmlspecialchars(strip_tags($data->street));
$order->home_number = htmlspecialchars(strip_tags($data->home_number));
$order->phone = htmlspecialchars(strip_tags($data->phone));
$order->status = htmlspecialchars(strip_tags($data->status));
$order->time = htmlspecialchars(strip_tags($data->time));
$order->in_slices = htmlspecialchars(strip_tags($data->in_slices));
$order->user_id = htmlspecialchars(strip_tags($data->user_id));

// Create Order
try {
    if ($order->create()) {
        echo json_encode(
            array('message' => 'Order Created')
        );
    } else {
        echo json_encode(
            array('message' => 'Order Not Created')
        );
    }
} catch (Exception $e) {
    echo 'Connection Error: ' . $e->getMessage();
}

