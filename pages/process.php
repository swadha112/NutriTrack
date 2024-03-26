<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST['username'];
    $firstname = $_POST['firstName'];
    $lastname = $_POST['lastName'];
    $age = $_POST['age'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $gender = $_POST['gender'];
   
    // Validate and sanitize input if necessary

    // Insert additional information into the database
    // Replace 'your_host', 'your_database_name', 'your_username', and 'your_password' with your actual database credentials
    $db = new PDO("pgsql:host=localhost;dbname=nutritrack", 'postgres', 'swadhak');
    $sql = "UPDATE users SET  age = :age, height = :height, weight = :weight, firstname = :firstname, lastname = :lastname, gender = :gender WHERE username = :username";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':firstname', $firstname);
    $stmt->bindParam(':lastname', $lastname);
    $stmt->bindParam(':age', $age);
    $stmt->bindParam(':height', $height);
    $stmt->bindParam(':weight', $weight);
    $stmt->bindParam(':gender', $gender);
    $stmt->execute();
   
    // Redirect to dashboard or another appropriate page after successful submission
    header("Location: dashboard.html");
    exit();
}
?>
