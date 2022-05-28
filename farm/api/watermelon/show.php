<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Watermelon.php';
// Instantiate DB & connect
$database = new Database();
$db = $database->connect();
// Instantiate blog category object
$watermelon = new Watermelon($db);

// Get ID
$watermelon->id = isset($_GET['id']) ? $_GET['id'] : die();

// Get watermelon
$watermelon->get();

// Create array
$watermelon_arr = array(
    'id' => $watermelon->id,
    'status' => $watermelon->status,
    'weight' => $watermelon->weight
);

// Make JSON
print_r(json_encode($watermelon_arr));
