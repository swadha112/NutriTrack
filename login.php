<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['userName'];
  $password = $_POST['password'];

  try {
    // Create a PDO connection
    $db = new PDO("pgsql:host=localhost; dbname=nutritrack", 'postgres', 'swadhak');

    // Prepare a query to fetch user data based on username
    $sql = "SELECT * FROM users WHERE username = :username";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();

    // Check if user exists
    if ($stmt->rowCount() > 0) {
      $user = $stmt->fetch(PDO::FETCH_ASSOC);
      echo "Provided password: $password\n";
      echo "Stored hash: " . $user['password'] . "\n";
      echo "Hashed input password: " . password_hash($password, PASSWORD_DEFAULT) . "\n";
      // Verify password using password_verify
      if (password_verify($password, $user['password'])) {
        // Successful login
        $_SESSION['user'] = [
          'username' => $user['username'],
          'firstName' => $user['firstname'],
          'lastName' => $user['lastname'],
          // Add more information as needed
        ];

        // Redirect to dashboard
        header("Location: /pages/dashboard.html");
        exit();
      } else {
        // Invalid password
        echo "Invalid password. Please try again.";
      }
    } else {
      // Invalid username
      echo "Invalid username. Please try again.";
    }
  } catch (PDOException $e) {
    // Handle database errors
    echo "An error occurred while trying to log in. Please try again later.";
    error_log($e->getMessage()); // Log the error for debugging
  }
}
?>
