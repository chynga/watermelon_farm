<?php
class OrderedItem
{
    // DB Stuff
    private $conn;
    private $table = 'ordered_items';

    // Properties
    public $order_id;
    public $watermelon_id;

    // Constructor with DB
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Get Ordered Items
    public function get_all()
    {
        // Create query
        $query = 
            'SELECT
                order_id,
                watermelon_id
            FROM `' . $this->table . '`
            ORDER BY
                `order_id`, watermelon_id';

        // Prepare statement
        $stmt = $this->conn->prepare($query);
        // Execute query
        $stmt->execute();

        return $stmt;
    }
}
