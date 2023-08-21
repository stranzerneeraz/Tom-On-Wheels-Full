<?php
require_once "config.php"; // Include your config file

$vegetarianChecked = isset($_GET['vegetarian']) ? $_GET['vegetarian'] : false;
$nonVegetarianChecked = isset($_GET['nonVegetarian']) ? $_GET['nonVegetarian'] : false;
$minPrice = isset($_GET['minPrice']) ? (float)$_GET['minPrice'] : 0;
$maxPrice = isset($_GET['maxPrice']) ? (float)$_GET['maxPrice'] : 10;

if ($vegetarianChecked == "true" && $nonVegetarianChecked == "true") {
    $sql = "SELECT * FROM menu_items WHERE price >= $minPrice AND price <= $maxPrice";
} elseif ($vegetarianChecked == "true") {
    $sql = "SELECT * FROM menu_items WHERE category = 'Vegetarian' AND price >= $minPrice AND price <= $maxPrice";
} elseif ($nonVegetarianChecked == "true") {
    $sql = "SELECT * FROM menu_items WHERE category = 'Non-Vegetarian' AND price >= $minPrice AND price <= $maxPrice";
} else {
    $sql = "SELECT * FROM menu_items WHERE price >= $minPrice AND price <= $maxPrice";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Display filtered items
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
    echo 'No results found.';
}
?>
