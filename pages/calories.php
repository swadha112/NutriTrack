<?php
$host = 'localhost';
$dbname = 'Nutritrack';
$user = 'postgres';
$password = 'apurvaneel*01';

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: Could not connect to the database: " . $e->getMessage());
}

if (isset($_GET['activity'])) {
    $activityName = $_GET['activity'];
    $query = "SELECT activityname, caloriesburnt FROM activity WHERE activityname = :activityName";

    try {
        $statement = $pdo->prepare($query);
        $statement->bindParam(':activityName', $activityName);
        $statement->execute();
        $data = $statement->fetch(PDO::FETCH_ASSOC);
        echo json_encode($data);
    } catch (PDOException $e) {
        die("Error: Could not execute query: " . $e->getMessage());
    }
} else {
    $query = "SELECT activityname, caloriesburnt FROM activity";

    try {
        $statement = $pdo->query($query);
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data);
    } catch (PDOException $e) {
        die("Error: Could not execute query: " . $e->getMessage());
    }
}
?>
