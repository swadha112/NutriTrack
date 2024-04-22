<?php
// Connect to your PostgreSQL database
$host = 'localhost';
$dbname = 'Nutritrack';
$user = 'postgres';
$password = 'apurvaneel*01';

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare a query to fetch meals data for the specified user
    $stmt = $pdo->prepare("SELECT meal_type, meals FROM user_meals WHERE user_id = ?");
    $stmt->execute([$_GET['user_id']]);
    $mealsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Count the number of items in each meal type and calculate the total count
    $mealCounts = [];
    $totalCount = 0;
    foreach ($mealsData as $meal) {
        $mealType = $meal['meal_type'];
        $meals = json_decode($meal['meals'], true); // Decode JSON array
        $count = count($meals);
        $mealCounts[$mealType] = $count;
        $totalCount += $count;
    }

    // Calculate the ratio of each meal type
    $mealData = [];
    foreach ($mealCounts as $mealType => $count) {
        $ratio = $count / $totalCount;
        $mealData[] = [
            'meal_type' => $mealType,
            'meal_count' => $count,
            'ratio' => $ratio
        ];
    }

    // Return meal data with ratios as JSON
    header('Content-Type: application/json');
    echo json_encode($mealData);
} catch (PDOException $e) {
    // Handle database connection errors
    echo "Database Error: " . $e->getMessage();
}
?>
