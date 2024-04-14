error_reporting(E_ALL); ini_set('display_errors', 1);
<?php
// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are present in the request
    if (isset($_POST['userId'], $_POST['mealType'], $_POST['meal'], $_POST['quantity'])) {
        // Assign the values from the POST request to variables
        $userId = $_POST['userId'];
        $mealType = $_POST['mealType'];
        $meal = $_POST['meal'];
        $quantity = $_POST['quantity'];

        // Connect to your PostgreSQL database
        $conn = pg_connect("host=localhost dbname=nutritrack user=postgres password=swadhak");
        if (!$conn) {
            // If connection fails, return an error response
            echo json_encode(array("success" => false, "message" => "Database connection failed"));
            exit; // Terminate script execution
        }

        // Check if the user already has meals recorded for the given meal type
        $result = pg_query_params($conn, "SELECT meals, quantities FROM user_meals WHERE user_id = $1 AND meal_type = $2", array($userId, $mealType));
        if (!$result) {
            // If query fails, return an error response
            echo json_encode(array("success" => false, "message" => "Error querying database"));
            exit; // Terminate script execution
        }

        // Fetch the row if exists
        $row = pg_fetch_assoc($result);
        if ($row) {
            // If user already has meals recorded for the given meal type, update the arrays
            $meals = json_decode($row['meals']);
            $quantities = json_decode($row['quantities']);

            // Append the new meal and quantity to the arrays
            array_push($meals, $meal);
            array_push($quantities, $quantity);

            // Update the database with the new arrays
            $updateResult = pg_query_params($conn, "UPDATE user_meals SET meals = $1, quantities = $2 WHERE user_id = $3 AND meal_type = $4", array(json_encode($meals), json_encode($quantities), $userId, $mealType));
            if (!$updateResult) {
                // If update fails, return an error response
                echo json_encode(array("success" => false, "message" => "Error updating database"));
                exit; // Terminate script execution
            }
        } else {
            // If user doesn't have meals recorded for the given meal type, insert a new row
            $insertResult = pg_query_params($conn, "INSERT INTO user_meals (user_id, meal_type, meals, quantities) VALUES ($1, $2, $3, $4)", array($userId, $mealType, json_encode(array($meal)), json_encode(array($quantity))));
            if (!$insertResult) {
                // If insertion fails, return an error response
                echo json_encode(array("success" => false, "message" => "Error inserting into database"));
                exit; // Terminate script execution
            }
        }

        // Close the database connection
        pg_close($conn);

        // Return a success response
        echo json_encode(array("success" => true, "message" => "Meal added successfully"));
        exit; // Terminate script execution
    } else {
        // If any required field is missing, return an error response
        echo json_encode(array("success" => false, "message" => "Missing required fields"));
        exit; // Terminate script execution
    }
} else {
    // If request method is not POST, return an error response
    echo json_encode(array("success" => false, "message" => "Invalid request method"));
    exit; // Terminate script execution
}
?>
