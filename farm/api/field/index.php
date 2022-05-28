<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Square.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate square object
$square = new Square($db);

// Square get all query
$result = $square->getAll();

// Get row count
$num = $result->rowCount();

// Check if any square
if ($num > 0) {
    // Square array
    $square_arr = array();
    $square_arr['data'] = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $square_item = array(
            'row' => $row,
            'col' => $col,
            'watermelon_id' => $watermelon_id
        );

        // Push to "data"
        array_push($square_arr['data'], $square_item);
    }

    // Turn to JSON & output
    echo json_encode($square_arr);
} else {
    // No Squares
    echo json_encode(
        array('message' => 'No Squares Found')
    );
}
