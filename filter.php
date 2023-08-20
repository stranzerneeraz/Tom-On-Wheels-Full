<?php
require_once "config.php"; // Include your config file

$vegetarianChecked = isset($_GET['vegetarian']) ? $_GET['vegetarian'] : false;
$nonVegetarianChecked = isset($_GET['nonVegetarian']) ? $_GET['nonVegetarian'] : false;
$minPrice = isset($_GET['minPrice']) ? (float)$_GET['minPrice'] : 0;
$maxPrice = isset($_GET['maxPrice']) ? (float)$_GET['maxPrice'] : 100;

$sqlFilters = [];
if ($vegetarianChecked) {
    $sqlFilters[] = "category = 'vegetarian'";
}
if ($nonVegetarianChecked) {
    $sqlFilters[] = "category = 'non-vegetarian'";
}
$sqlFilters[] = "price >= $minPrice AND price <= $maxPrice";

$sql = "SELECT * FROM menu_items";
if (!empty($sqlFilters)) {
    $sql .= " WHERE " . implode(" AND ", $sqlFilters);
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Display filtered items
        echo '<div class="col-md-4 col-12">';
        // Rest of your item display code here
        echo '</div>';
    }
} else {
    echo 'No results found.';
}
?>
