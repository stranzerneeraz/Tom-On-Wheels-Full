<?php
session_start();
require_once 'config.php';

if (isset($_SESSION["user_id"]) && isset($_POST["item_id"])) {
    $user_id = $_SESSION["user_id"];
    $item_id = $_POST["item_id"];

    $delete_query = "DELETE FROM order_bag WHERE item_id = $item_id AND user_id = $user_id";

    if (mysqli_query($conn, $delete_query)) {
        // Successfully deleted the item
        http_response_code(200);
        $response = "Item removed successfully.";
    } else {
        // Error deleting the item
        http_response_code(500);
        $response = "Error removing item.";
    }
} else {
    // Invalid request
    http_response_code(400);
    $response = "Invalid request.";
}

echo $response;
?>