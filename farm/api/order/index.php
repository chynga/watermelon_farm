<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Order.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate Order object
$order = new Order($db);

// Gets all Orders 
$result = $order->get_all();

// Get row count
$num = $result->rowCount();

// Check if any order
if ($num > 0) {
    // Order array
    $order_arr = array();
    $order_arr['data'] = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $order_item = array(
            'id' => $id,
            'city' => $city,
            'street' => $street,
            'home_number' => $home_number,
            'phone' => $phone,
            'status' => $status,
            'time' => $time,
            'in_slices' => $in_slices,
            'user_id' => $user_id
        );

        // Push to "data"
        array_push($order_arr['data'], $order_item);
    }

    // Turn to JSON & output
    echo json_encode($order_arr);
} else {
    // No Orders
    echo json_encode(
        array('message' => 'No Orders Found')
    );
}
