<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once "config.php";

$response = array(); // Initialize the response array

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    $response["success"] = false;
    $response["message"] = "User is not logged in.";
} else {
    // Handle adding item to the bag
    if (isset($_POST["add_to_bag"]) && $_POST["add_to_bag"] === "true") {
        $user_id = $_SESSION["user_id"];
        $item_id = $_POST["item_id"]; // Fetch the item_id from POST data
        $item_price = $_POST["item_price"]; // Get the item price from the POST data

        // Fetch item details from the database based on item_id
        $query = "SELECT * FROM menu_items WHERE item_id = $item_id";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $item = mysqli_fetch_assoc($result);
            $name = mysqli_real_escape_string($conn, $item["name"]);
            $image_url = mysqli_real_escape_string($conn, $item["image_url"]);
            $price = $item_price; // Use the item price from the POST data

            // Check if the item already exists in the bag for the user
            $check_query = "SELECT * FROM order_bag WHERE item_id = $item_id AND user_id = $user_id";
            $check_result = mysqli_query($conn, $check_query);

            if (mysqli_num_rows($check_result) > 0) {
                // Item already exists, update quantity and order_price
                $update_query = "UPDATE order_bag SET quantity = quantity + 1, order_price = order_price + $price WHERE item_id = $item_id AND user_id = $user_id";
                if (mysqli_query($conn, $update_query)) {
                    $operation_successful = true;
                } else {
                    $operation_successful = false;
                }
            } else {
                // Item does not exist, insert into the bag
                $insert_query = "INSERT INTO order_bag (item_id, user_id, name, image_url, quantity, order_price, order_date) VALUES ($item_id, $user_id, '$name', '$image_url', 1, $price, CURDATE())";
                if (mysqli_query($conn, $insert_query)) {
                    $operation_successful = true;
                } else {
                    $operation_successful = false;
                }
            }

            // After performing the database operation, set the response variables
            if ($operation_successful) {
                $response["success"] = true;
                $response["message"] = "Item added/updated in the bag.";
            } else {
                $response["success"] = false;
                $response["message"] = "Failed to add/update item in the bag.";
            }
        } else {
            $response["success"] = false;
            $response["message"] = "Item not found in the database.";
        }
    } else {
        $operation_successful = false;
    }
}

header("Content-Type: application/json");
echo json_encode($response);
exit();
?>
