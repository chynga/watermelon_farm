<?php
class Order
{
    // DB Stuff
    private $conn;
    private $table = 'order';

    // Properties
    public $id;
    public $city;
    public $street;
    public $home_number;
    public $phone;
    public $status;
    public $time;
    public $in_slices;
    public $user_id;

    // Constructor with DB
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Get orders
    public function get_all()
    {
        // Create query
        $query = 'SELECT
                id,
                city,
                street,
                home_number,
                phone,
                `status`,
                `time`,
                in_slices,
                user_id
            FROM `' . $this->table . '`
            ORDER BY
                `time`';

        // Prepare statement
        $stmt = $this->conn->prepare($query);
        // Execute query
        $stmt->execute();

        return $stmt;
    }

    // Create Order
    public function create()
    {
        // Create Query
        $query = 
            'INSERT INTO `' .
                $this->table . '`
            SET
                `city` = :city,
                `street` = :street,
                `home_number` = :home_number,
                `phone` = :phone,
                `status` = :status,
                `time` = :time,
                `in_slices` = :in_slices,
                `user_id` = :user_id';

        // Prepare Statement
        $stmt = $this->conn->prepare($query);

        // Bind data
        $stmt->bindParam(':city', $this->city);
        $stmt->bindParam(':street', $this->street);
        $stmt->bindParam(':home_number', $this->home_number);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':time', $this->time);
        $stmt->bindParam(':in_slices', $this->in_slices);
        $stmt->bindParam(':user_id', $this->user_id);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }

        // Print error if something goes wrong
        printf("Error: $s.\n", $stmt->error);

        return false;
    }
}
