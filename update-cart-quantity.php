<?php
session_start();
require_once 'config.php'; // Include your database connection configuration

if (isset($_SESSION["user_id"]) && isset($_POST["item_id"]) && isset($_POST["new_quantity"])) {
    $user_id = $_SESSION["user_id"];
    $item_id = $_POST["item_id"];
    $new_quantity = $_POST["new_quantity"];

    // Make sure the new quantity is at least 1
    if ($new_quantity < 1) {
        exit("Invalid quantity");
    }

    // Get the item's price from the database
    $get_price_query = "SELECT price FROM menu_items WHERE item_id = $item_id";
    $price_result = mysqli_query($conn, $get_price_query);
    $price_row = mysqli_fetch_assoc($price_result);
    $item_price = $price_row['price'];

    // Calculate the new order price based on the new quantity
    $new_order_price = $item_price * $new_quantity;

    $update_query = "UPDATE order_bag SET quantity = $new_quantity, order_price = $new_order_price WHERE item_id = $item_id AND user_id = $user_id";

    if (mysqli_query($conn, $update_query)) {
        // Successfully updated the quantity and order price
        http_response_code(200);
        $response = "Quantity and price updated successfully.";
    } else {
        // Error updating the quantity and order price
        http_response_code(500);
        $response = "Error updating quantity and price: " . mysqli_error($conn);
    }
} else {
    // Invalid request
    http_response_code(400);
    $response = "Invalid request.";
}

echo $response;
?>
