<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Watermelon.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate watermelon object
$watermelon = new Watermelon($db);

// Watermelon get all query
$result = $watermelon->get_all();

// Get row count
$num = $result->rowCount();

// Check if any watermelons
if ($num > 0) {
    // Watermelon array
    $watermelon_arr = array();
    $watermelon_arr['data'] = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $watermelon_item = array(
            'id' => $id,
            'status' => $status,
            'weight' => number_format((float)$weight, 2, '.', '')
        );

        // Push to "data"
        array_push($watermelon_arr['data'], $watermelon_item);
    }

    // Turn to JSON & output
    echo json_encode($watermelon_arr);
} else {
    // No Watermelons
    echo json_encode(
        array('message' => 'No Watermelons Found')
    );
}
