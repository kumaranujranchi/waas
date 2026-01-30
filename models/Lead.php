<?php
require_once __DIR__ . '/../classes/Database.php';

class Lead
{
    private $conn;
    private $table = 'leads';

    public function __construct()
    {
        $this->conn = Database::getInstance();
    }

    // Create new lead
    public function create($name, $email, $phone, $source = 'chatbot')
    {
        $data = [
            'name' => htmlspecialchars(strip_tags($name)),
            'email' => htmlspecialchars(strip_tags($email)),
            'phone' => htmlspecialchars(strip_tags($phone)),
            'source' => htmlspecialchars(strip_tags($source))
        ];

        return $this->conn->insert($this->table, $data);
    }
}
