<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/OrderedItem.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate Ordered Item object
$ordered_item = new OrderedItem($db);

// Gets all Ordered Items query
$result = $ordered_item->get_all();

// Get row count
$num = $result->rowCount();

// Check if any Item
if ($num > 0) {
    // Ordered Item array
    $ordered_arr = array();
    $ordered_arr['data'] = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $ordered_item = array(
            'order_id' => $order_id,
            'watermelon_id' => $watermelon_id
        );

        // Push to "data"
        array_push($ordered_arr['data'], $ordered_item);
    }

    // Turn to JSON & output
    echo json_encode($ordered_arr);
} else {
    // No Ordered Items
    echo json_encode( 
        array('message' => 'No Ordered Items Found')
    );
}
