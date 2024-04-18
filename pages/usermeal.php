<?php
$host = 'localhost';
$dbname = 'nutritrack';
$user = 'postgres';
$password = 'swadhak';

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve data from POST parameters
    $userId = $_POST['userId'];
    $mealType = $_POST['mealType'];
    $meal = $_POST['meal']; // Individual meal element
    $quantity = $_POST['quantity']; // Individual quantity element

    // Check if a row with the given user ID and meal type already exists
    $stmt = $pdo->prepare("SELECT meals, quantities FROM user_meals WHERE user_id = ? AND meal_type = ?");
    $stmt->execute([$userId, $mealType]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        // Row exists, update meals and quantities arrays
        $stmt = $pdo->prepare("UPDATE user_meals SET meals = meals || ARRAY[?], quantities = quantities || ARRAY[?::integer] WHERE user_id = ? AND meal_type = ?");
        $stmt->execute([$meal, $quantity, $userId, $mealType]);
        echo "Data appended successfully.";
    } else {
        // Row does not exist, insert a new row
        $stmt = $pdo->prepare("INSERT INTO user_meals (user_id, meal_type, meals, quantities) VALUES (?, ?, ARRAY[?], ARRAY[?::integer])");
        $stmt->execute([$userId, $mealType, $meal, $quantity]);
        echo "Data inserted successfully.";
    }
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
} catch (Exception $ex) {
    echo "Error: " . $ex->getMessage();
}
?>
