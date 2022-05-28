<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Cart.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate Cart object
$cart = new Cart($db);

// Cart get all query
$result = $cart->get_all();

// Get row count
$num = $result->rowCount();

// Check if any cart
if ($num > 0) {
    // Cart array
    $cart_arr = array();
    $cart_arr['data'] = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $cart_item = array(
            'user_id' => $user_id,
            'watermelon_id' => $watermelon_id
        );

        // Push to "data"
        array_push($cart_arr['data'], $cart_item);
    }

    // Turn to JSON & output
    echo json_encode($cart_arr);
} else {
    // No Carts
    echo json_encode(
        array('message' => 'No Carts Found')
    );
}
