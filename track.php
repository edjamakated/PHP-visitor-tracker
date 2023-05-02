<?php

class VisitorTracker
{
    private $dbFile;
    private $pdo;

    public function __construct($dbFile = 'visitors.sqlite3')
    {
        $this->dbFile = $dbFile;

        // Check if the database exists, and if not, create it
        if (!file_exists($this->dbFile)) {
            $this->createDatabase();
        }

        // Connect to the database
        $this->connectToDatabase();
    }

    public function __destruct()
    {
        // Close the database connection
        $this->pdo = null;
    }

    private function connectToDatabase()
    {
        try {
            $this->pdo = new PDO('sqlite:' . $this->dbFile);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Failed to connect to the database: " . $e->getMessage());
        }
    }

    private function createDatabase()
    {
        try {
            // Create a new SQLite3 database file
            $this->connectToDatabase();

            // Create a table for storing visitor data
            $this->pdo->exec('CREATE TABLE visitors (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                user_agent TEXT,
                ip_address TEXT,
                timestamp TEXT
            )');
        } catch (PDOException $e) {
            die("Failed to create the database: " . $e->getMessage());
        }
    }

    public function insertVisitorData($userAgent, $ipAddress, $timestamp)
    {
        try {
            // Prepare the insert statement
            $stmt = $this->pdo->prepare('INSERT INTO visitors (user_agent, ip_address, timestamp) VALUES (:user_agent, :ip_address, :timestamp)');
            $stmt->bindParam(':user_agent', $userAgent, PDO::PARAM_STR);
            $stmt->bindParam(':ip_address', $ipAddress, PDO::PARAM_STR);
            $stmt->bindParam(':timestamp', $timestamp, PDO::PARAM_STR);

            // Execute the insert statement
            $stmt->execute();
        } catch (PDOException $e) {
            die("Failed to insert visitor data: " . $e->getMessage());
        }
    }
}

// Usage:

$visitorTracker = new VisitorTracker();

// Get user data
$userAgent = $_SERVER['HTTP_USER_AGENT'];
$ipAddress = $_SERVER['REMOTE_ADDR'];
$timestamp = date('Y-m-d H:i:s');

// Insert visitor data into the database
$visitorTracker->insertVisitorData($userAgent, $ipAddress, $timestamp);
