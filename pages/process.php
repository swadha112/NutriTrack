<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password']; // Note: This is the plaintext password
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
        try {
            // Hash the password before storing it
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $db = new PDO("pgsql:host=localhost; dbname=nutritrack", 'postgres', 'swadhak');
            $sql = "INSERT INTO users (username, password, firstname, lastname, age, height, weight, gender) 
                    VALUES (:username, :password, :firstname, :lastname, :age, :height, :weight, :gender)";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashedPassword); // Store hashed password
            $stmt->bindParam(':firstname', $firstname);
            $stmt->bindParam(':lastname', $lastname);
            $stmt->bindParam(':age', $age);
            $stmt->bindParam(':height', $height);
            $stmt->bindParam(':weight', $weight);
            $stmt->bindParam(':gender', $gender);
            $stmt->execute();

            // Redirect to dashboard.html after successful insertion
            header("Location: dashboard.html");
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>
