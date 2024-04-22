<?php
$host = 'localhost';
$dbname = 'Nutritrack';
$user = 'postgres';
$password = 'apurvaneel*01';

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$userId = $_POST['userId'];
$activityName= $_POST['activityName'];
$activityTime = $_POST['activityTime'];
$totalCaloriesBurnt = $_POST['totalCaloriesBurnt'];

//check if a row with given userId alreaaddy exists
$stmt = $pdo->prepare("SELECT activity_name, activity_time total_calories_burnt FROM user_activity  WHERE user_id = ?");
$stmt->execute([$userId]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if($result){
    //row exists, update activity and hours
    $stmt = $pdo->prepare("UPDATE user_activity SET activity_name= activity_name || ARRAY[?], activity_time = activity_time || ARRAY[?::integer] , total_calories_burnt = total_calories_burnt + ? WHERE user_id = ?");
    $stmt->execute([$activityName, $activityTime, $totalCaloriesBurnt, $userId]);
    echo "data appended successfully";
}else{
    //no such row, insert new data
    $stmt = $pdo->prepare("INSERT INTO user_activity(user_id, activity_name, activity_time, total_calories_burnt) VALUES (?, ARRAY[?], ARRAY[?::integer], ?)");
    $stmt->execute($userId, $activityName, $activityTime, $totalCaloriesBurnt);
    echo "data inserted succesfully";
}
} catch(PDOException $e) {
    echo "Database error: " . $e->getMessage();
} catch(Exception $ex) {
    echo "Error: " . $ex->getMessage();
}
?>
// if (isset($_GET['activity'])) {
//     $activityName = $_GET['activity'];
//     $query = "SELECT activityname, caloriesburnt FROM activity WHERE activityname = :activityName";

//     try {
//         $statement = $pdo->prepare($query);
//         $statement->bindParam(':activityName', $activityName);
//         $statement->execute();
//         $data = $statement->fetch(PDO::FETCH_ASSOC);
//         echo json_encode($data);
//     } catch (PDOException $e) {
//         die("Error: Could not execute query: " . $e->getMessage());
//     }
// } else {
//     $query = "SELECT activityname, caloriesburnt FROM activity";

//     try {
//         $statement = $pdo->query($query);
//         $data = $statement->fetchAll(PDO::FETCH_ASSOC);
//         echo json_encode($data);
//     } catch (PDOException $e) {
//         die("Error: Could not execute query: " . $e->getMessage());
//     }
// }



