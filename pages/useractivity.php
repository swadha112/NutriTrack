<?php
var_dump($_POST);
$host = 'localhost';
$dbname = 'nutritrack';
$user = 'postgres';
$password = 'swadhak';

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve data from POST parameters
    $user_id = $_POST['user_id'];
    $activity_name = $_POST['activity_name'];
    $activity_time = $_POST['activity_time']; 
    $total_calories_burnt = $_POST['total_calories_burnt']; 

    // Check if a row with the given user ID and meal type already exists
    $stmt = $pdo->prepare("SELECT activity_name, activity_time, total_calories_burnt FROM user_activity WHERE user_id = ? ");
    $stmt->execute([$user_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        // Row exists, update meals and quantities arrays
        $stmt = $pdo->prepare("UPDATE user_activity SET activity_name = activity_name || ARRAY[?], activity_time = activity_time || ARRAY[?::integer] , total_calories_burnt= total_calories_burnt +? WHERE user_id = ? ");
        $stmt->execute([$activity_name, $activity_time,$total_calories_burnt, $user_id]);
        echo "Data appended successfully.";
    } else {
        // Row does not exist, insert a new row
        $stmt = $pdo->prepare("INSERT INTO user_activity (user_id, activity_name, activity_time, total_calories_burnt) VALUES (?, ARRAY[?], ARRAY[?::integer],?)");
        $stmt->execute([$user_id, $activity_name,$activity_time,$total_calories_burnt]);
        echo "Data inserted successfully.";
    }
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
} catch (Exception $ex) {
    echo "Error: " . $ex->getMessage();
}
?>
