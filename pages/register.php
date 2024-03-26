<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['newUsername'];
    $password = $_POST['newPassword'];
   
    // Validate and sanitize input if necessary
   
    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
   
    // Insert username and hashed password into database
    // Replace 'your_host', 'your_database_name', 'your_username', and 'your_password' with your actual database credentials
    $db = new PDO("pgsql:host=localhost;dbname=nutritrack", 'postgres', 'swadhak');
    $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->execute();
   
    // Redirect to profile setup page
    header("Location: profile.html?username=$username"); // Pass username as a parameter to profile page
    exit();
}
?>
