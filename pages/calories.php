<?php
// Database connection parameters
$host = 'localhost';
$dbname = 'Nutritrack';
$user = 'postgres';
$password = 'apurvaneel*01';

// Establish a connection to the PostgreSQL database using PDO
try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: Could not connect to the database: " . $e->getMessage());
}

// Define the SQL query to fetch data
$query = "SELECT activityname FROM activity";

// Execute the query
try {
    $statement = $pdo->query($query);
    $data = $statement->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($data);
} catch (PDOException $e) {
    die("Error: Could not execute query: " . $e->getMessage());
}
?>
