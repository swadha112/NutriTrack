<?php
// Connect to your PostgreSQL database
$host = 'localhost';
$dbname = 'your_database_name';
$user = 'your_database_user';
$password = 'your_database_password';

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Execute a query to fetch exercise data
    $stmt = $pdo->query("SELECT exercise, calories_burnt FROM exercise_data");

    // Fetch exercise data as an associative array
    $exerciseData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return exercise data as JSON
    header('Content-Type: application/json');
    echo json_encode($exerciseData);
} catch (PDOException $e) {
    // Handle database connection errors
    echo "Database Error: " . $e->getMessage();
}
?>
