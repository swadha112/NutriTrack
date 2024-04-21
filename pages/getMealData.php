<?php
// Connect to your PostgreSQL database
$host = 'localhost';
$dbname = 'Nutritrack';
$user = 'postgres';
$password = 'apurvaneel*01';


try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Execute a query to fetch exercise data
    $stmt = $pdo->query("SELECT meals, quantities FROM user_meals WHERE user_id = ? AND meal_type = ?");

    // Fetch exercise data as an associative array
    $exerciseData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return exercise data as JSON
    header('Content-Type: application/json');
    echo json_encode($mealData);
} catch (PDOException $e) {
    // Handle database connection errors
    echo "Database Error: " . $e->getMessage();
}
?>
