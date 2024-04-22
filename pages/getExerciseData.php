<?php
// Connect to your PostgreSQL database
$host = 'localhost';
$dbname = 'Nutritrack';
$user = 'postgres';
$password = 'apurvaneel*01';

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $userId = $_GET['user_id'];

    // Execute a query to fetch exercise data
    $stmt = $pdo->prepare("SELECT activity_name, activity_time, total_calories_burnt FROM user_activity WHERE user_id = ? ");

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
