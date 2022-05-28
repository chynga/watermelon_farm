<?php
class Square
{
    // DB Stuff
    private $conn;
    private $table = 'field';

    // Properties
    public $row;
    public $col;
    public $watermelon_id;

    // Constructor with DB
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Get field
    public function getAll()
    {
        // Create query
        $query =
            'SELECT
                row,
                col,
                watermelon_id
            FROM
                ' . $this->table . '
            ORDER BY
                row, col';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Execute query
        $stmt->execute();

        return $stmt;
    }

    // Get Square
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
