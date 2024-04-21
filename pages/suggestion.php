<?php
// PostgreSQL database connection parameters
$host = 'localhost'; // e.g., localhost
$dbname = 'Nutritrack';
$user = 'postgres';
$password = 'apurvaneel*01';

try {
    // Connect to PostgreSQL
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    
    // Prepare and execute query to fetch food names and serving information
    $stmt = $pdo->prepare("SELECT foodname, serving FROM food");
    $stmt->execute();
    
    // Fetch all rows
    $foods = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Return food names and serving information as JSON
    echo json_encode($foods);
} catch (PDOException $e) {
    // Handle database connection error
    echo "Connection failed: " . $e->getMessage();
}
?>
