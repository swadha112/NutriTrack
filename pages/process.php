<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $firstname = $_POST['firstName'];
    $lastname = $_POST['lastName'];
    $age = $_POST['age'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $gender = $_POST['gender'];
    $errors = [];

    if ($height < 0 || $height > 300) {
        $errors[] = "Height must be between 0 and 300 cm.";
    }

    if ($weight < 0) {
        $errors[] = "Weight must be a positive value.";
    }

    if (!empty($errors)) {

        foreach ($errors as $error) {
            echo "<p>Error: $error</p>";
        }
    } else {
        
        $db = new PDO("pgsql:host=localhost; dbname=nutritrack", 'postgres', 'swadhak');
        $sql = "UPDATE users SET age = :age, height = :height, weight = :weight, firstname = :firstname, lastname = :lastname, gender = :gender WHERE username = :username";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':age', $age);
        $stmt->bindParam(':height', $height);
        $stmt->bindParam(':weight', $weight);
        $stmt->bindParam(':gender', $gender);
        $stmt->execute();
       
        
        header("Location: dashboard.html");
        exit();
    }
}
?>