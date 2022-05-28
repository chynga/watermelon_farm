<?php
class Cart
{
    // DB Stuff
    private $conn;
    private $table = 'cart';

    // Properties
    public $user_id;
    public $watermelon_id;

    // Constructor with DB
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Get carts
    public function get_all()
    {
        // Create query
        $query = 
            'SELECT
                user_id,
                watermelon_id
            FROM `' . $this->table . '`
            ORDER BY
                user_id, watermelon_id';

        // Prepare statement
        $stmt = $this->conn->prepare($query);
        // Execute query
        $stmt->execute();

        return $stmt;
    }

    // Create Cart
    public function create()
    {
        // Create Query
        $query = 
            'INSERT INTO `' .
                $this->table . '`
            SET
                user_id = :user_id,
                watermelon_id = :watermelon_id';

        // Prepare Statement
        $stmt = $this->conn->prepare($query);

        // Bind data
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':watermelon_id', $this->watermelon_id);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }

        // Print error if something goes wrong
        printf("Error: $s.\n", $stmt->error);

        return false;
    }
}
