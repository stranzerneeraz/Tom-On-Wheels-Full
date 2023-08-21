<?php
require_once "config.php";

$searchQuery = $_GET["searchQuery"]; // Get the search query from the form

$sql = "SELECT * FROM menu_items
        WHERE name LIKE '%$searchQuery%'
        OR description LIKE '%$searchQuery%'
        OR category LIKE '%$searchQuery%'
        OR restaurant_name LIKE '%$searchQuery%'
        OR country LIKE '%$searchQuery%'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Display search results using the same card structure as in menu.php
        echo '<div class="col-md-4 col-12">';
        echo '<div class="card m-3 border">';
        echo '<a href="menu-detail.php?item_id=' . $row["item_id"] . '">';
        echo '<img src="' . $row["image_url"] . '" class="card-img-top" alt="...">';
        echo '</a>';
        echo '<div class="card-body">';
        echo '<h5 class="card-title header">' . $row["name"] . '</h5>';
        echo '<h6 class="card-title header">£' . $row["price"] . '</h6>';
        
        // Add to Bag button with AJAX functionality
        echo '<button class="btn btn-primary add-to-bag" data-item-id="' . $row["item_id"] . '">Add to Bag <i class="fas fa-shopping-cart"></i></button>';
        
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
} else {
    echo "0 results";
}
?>
