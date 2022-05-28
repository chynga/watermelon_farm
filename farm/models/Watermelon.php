<?php
class Watermelon
{
    // DB Stuff
    private $conn;
    private $table = 'watermelon';

    // Properties
    public $id;
    public $status;
    public $weight;

    // Constructor with DB
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Get watermelons
    public function get_all()
    {
        // Create query
        $query = 
            'SELECT
                id,
                status,
                weight
            FROM
                ' . $this->table . '
            ORDER BY
                id ASC';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Execute query
        $stmt->execute();

        return $stmt;
    }

    // Get Single Watermelon
    public function get()
    {
        // Create query
        $query = 
            'SELECT
                id,
                status,
                weight
            FROM
                ' . $this->table . '
            WHERE id = ?
            LIMIT 0,1';

        //Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind ID
        $stmt->bindParam(1, $this->id);

        // Execute query
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties
        $this->id = $row['id'];
        $this->status = $row['status'];
        $this->weight = number_format((float)$row['weight'], 2, '.', '');
    }
}
