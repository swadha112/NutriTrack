<?php
$host = 'localhost';
$dbname = 'nutritrack';
$user = 'postgres';
$password = 'swadhak';

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['userId'], $_POST['mealType'], $_POST['meal'], $_POST['quantity'])) {
            throw new Exception('One or more required parameters are missing.');
        }

        $userId = $_POST['userId'];
        $mealType = $_POST['mealType'];
        $meal = $_POST['meal'];
        $quantity = $_POST['quantity'];

        $stmt = $pdo->prepare("SELECT * FROM user_meals WHERE user_id = ? AND meal_type = ?");
        $stmt->execute([$userId, $mealType]);
        $existingMeal = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingMeal) {
            $meals = json_decode($existingMeal['meals'], true);
            $quantities = json_decode($existingMeal['quantities'], true);
            $meals[] = $meal;
            $quantities[] = $quantity;

            $stmt = $pdo->prepare("UPDATE user_meals SET meals = ?, quantities = ? WHERE user_id = ? AND meal_type = ?");
            $stmt->execute([json_encode($meals), json_encode($quantities), $userId, $mealType]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO user_meals (user_id, meal_type, meals, quantities) VALUES (?, ?, ?, ?)");
            $stmt->execute([$userId, $mealType, json_encode([$meal]), json_encode([$quantity])]);
        }

        // Send success response
        echo json_encode(['success' => true]);
    }
} catch (PDOException $e) {
    // Log database-related errors
    error_log('Database connection error: ' . $e->getMessage());
    // Send error response
    echo json_encode(['error' => 'Database connection error']);
} catch (Exception $e) {
    // Log other errors
    error_log('Error: ' . $e->getMessage());
    // Send error response
    echo json_encode(['error' => 'Error: ' . $e->getMessage()]);
}
?>
