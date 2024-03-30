<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['newUsername'];
    $password = $_POST['newPassword'];
    $db = new PDO("pgsql:host=localhost;dbname=nutritrack", 'postgres', 'swadhak');
    $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->execute();
    header("Location: profile.html?username=$username");
    exit();
}
?>
