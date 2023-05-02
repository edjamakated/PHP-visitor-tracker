<?php

// Set database file path
$db_file = 'visitors.sqlite3';

// Check if database exists, and if not, create it
if (!file_exists($db_file)) {
    createDatabase($db_file);
}

// Get user data
$user_agent = $_SERVER['HTTP_USER_AGENT'];
$ip_address = $_SERVER['REMOTE_ADDR'];
$timestamp = date('Y-m-d H:i:s');

// Insert visitor data into the database
insertVisitorData($db_file, $user_agent, $ip_address, $timestamp);

function createDatabase($db_file) {
    // Create a new SQLite3 database file
    $db = new SQLite3($db_file);

    // Create a table for storing visitor data
    $db->exec('CREATE TABLE visitors (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_agent TEXT,
        ip_address TEXT,
        timestamp TEXT
    )');
    
    // Close the database connection
    $db->close();
}

function insertVisitorData($db_file, $user_agent, $ip_address, $timestamp) {
    // Open the SQLite3 database file
    $db = new SQLite3($db_file);

    // Prepare the insert statement
    $stmt = $db->prepare('INSERT INTO visitors (user_agent, ip_address, timestamp) VALUES (:user_agent, :ip_address, :timestamp)');
    $stmt->bindValue(':user_agent', $user_agent, SQLITE3_TEXT);
    $stmt->bindValue(':ip_address', $ip_address, SQLITE3_TEXT);
    $stmt->bindValue(':timestamp', $timestamp, SQLITE3_TEXT);

    // Execute the insert statement
    $stmt->execute();

    // Close the database connection
    $db->close();
}
