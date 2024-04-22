<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['userName'];
  $password = $_POST['password'];

  try {
    $db = new PDO("pgsql:host=localhost; dbname=Nutritrack", 'postgres', 'apurvaneel*01');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable error reporting
    
    $sql = "SELECT * FROM users WHERE username = :username";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      if (password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
          'username' => $user['username'],
          'firstName' => $user['firstname'],
          'lastName' => $user['lastname'],
        ];

        header("Location: /pages/dashboard.html");
        exit();
      } else {
        echo "Invalid password. Please try again.";
      }
    } else {
      echo "Invalid username. Please try again.";
    }
  } catch (PDOException $e) {
    echo "An error occurred while trying to log in. Please try again later.";
    error_log($e->getMessage()); 
  }
}
?>
