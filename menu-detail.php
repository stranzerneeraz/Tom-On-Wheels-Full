<!-- PHP Code to Fetch Item Details -->
<?php
session_start();
include 'config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$isLoggedIn = isset($_SESSION["user_id"]);

$item = null;
$item_id = isset($_GET['item_id']) ? $_GET['item_id'] : null;

try {
    if (isset($_GET['item_id'])) {
        $item_id = $_GET['item_id'];
        $query = "SELECT * FROM menu_items WHERE item_id = $item_id";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $item = mysqli_fetch_assoc($result);
        } else {
            $response = array("error" => "Item not found.");
            header("Content-Type: application/json");
            echo json_encode($response);
            exit;
        }
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_to_bag"])) {
        if (!$isLoggedIn) {
            $response = array("error" => "You must be logged in to add items to the bag.");
        } else {
            $item_id = isset($_POST['item_id']) ? $_POST['item_id'] : null;

            if (!$item_id) {
                $response = array("error" => "Invalid item ID.");
                header("Content-Type: application/json");
                echo json_encode($response);
                exit;
            }

            $quantity = isset($_POST["quantity"]) ? intval($_POST["quantity"]) : 1;
            $user_id = $_SESSION["user_id"];

            // Define the variables related to the selected item
            $name = mysqli_real_escape_string($conn, $item['name']);
            $image_url = mysqli_real_escape_string($conn, $item['image_url']);

            $insertQuery = "INSERT INTO order_bag (user_id, item_id, name, image_url, quantity) 
                            VALUES ('$user_id', '$item_id', '$name', '$image_url', '$quantity')";

            if (mysqli_query($conn, $insertQuery)) {
                $response = array("success" => "Item added to bag successfully!");
            } else {
                $response = array("error" => "Error adding item to bag: " . mysqli_error($conn));
            }

            header("Content-Type: application/json");
            echo json_encode($response);
            exit;
        }
    }

    if (isset($_POST['add_random_to_bag']) && isset($_POST['random_item_id'])) {
        $randomItem_id = $_POST['random_item_id']; // Define the random item ID
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        $user_id = $_SESSION["user_id"];

        // Define the variables related to the random item
        $name = mysqli_real_escape_string($conn, $randomItem['name']);
        $image_url = mysqli_real_escape_string($conn, $randomItem['image_url']);

        $insertQuery = "INSERT INTO order_bag (user_id, item_id, name, image_url, quantity) 
                        VALUES ('$user_id', '$randomItem_id', '$name', '$image_url', '$quantity')";

        $response = array(); // Initialize the response array

        if (mysqli_query($conn, $insertQuery)) {
            $response['success'] = "Item added to bag successfully!";
        } else {
            $response['error'] = "Error adding item to bag: " . mysqli_error($conn);
        }

        // Send the response as JSON
        header("Content-Type: application/json");
        error_log(json_encode($response));
        echo json_encode($response);
        exit;
    }
} catch (Exception $e) {
    $response = array("error" => "An error occurred: " . $e->getMessage());
    header("Content-Type: application/json");
    echo json_encode($response);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Tom On Wheels - Menu</title>

        <!-- Css, bootstrap and fonts imports -->
        <link rel="stylesheet" type="text/css" href="css/style.css" /> 
        <link rel="stylesheet" type="text/css" href="css/menu-detail-style.css" /> 
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    </head>
    <body>
        <!-- Navbar Container -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom border-bottom-dark fixed-top" data-bs-theme="dark">
                <div class="container">
                    <a class="navbar-brand" href="index.php">
                        <img src="images/logo.jpeg" alt="Logo" width="40" height="32" class="d-inline-block align-text-top">
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="index.php">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="menu.php">Menu</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="orders.php">Orders</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="contact.php">Contact Us</a>
                            </li>
                            <?php
                                if (isset($_SESSION["user_id"])) {
                                    echo '<li class="nav-item">
                                            <a class="nav-link" href="javascript:void(0);" onclick="confirmLogout()">Logout</a>
                                        </li>';
                                } else {
                                    echo '<li class="nav-item">
                                            <a class="nav-link" href="login.php">Login</a>
                                        </li>';
                                }
                                ?>
                        </ul>
                        <form class="d-flex ms-auto" role="search">
                            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                            <button class="btn btn-primary" type="submit">Search</button>
                        </form>
                    </div>
                </div> 
            </nav>

        <div class="content">
            <div class="container">
                <!-- Product detail container -->
                <div class="product-detail">
                    <div class="card">
                        <div class="product-imgs">
                            <div class="img-display">
                                <img src="<?php echo $item['image_url']; ?>" alt="<?php echo $item['name']; ?>">
                            </div>
                        </div>
                        <div class="product-content">
                            <h2 class="title-header"><?php echo $item['name']; ?></h2>
                            <div class="product-rating">
                                <i class="fas fa-star"></i>
                                <i class = "fas fa-star"></i>
                                <i class = "fas fa-star"></i>
                                <i class = "fas fa-star"></i>
                                <i class = "fas fa-star-half-alt"></i>
                                <span>4.7(21)</span>
                            </div>
                            <div class="product-price">
                                <p class="last-price">Old Price: <span><?php echo '£' . $item['price']; ?></span></p>
                                <p class = "new-price">Offer Price: <span>£4.99 (16%)</span></p>
                            </div>
                            <div class="menu-detail">
                                <h2 class="header">About This Item:</h2>
                                <p class="attractive-text-justify"><?php echo isset($item['description']) ? $item['description'] : ''; ?></p>
                            </div>
                            <form class="product-form" method="post">
                                <input type="hidden" name="item_id" value="<?php echo isset($item_id) ? $item_id : ''; ?>">
                                <div class="item-quantity">
                                    <button class="minus-btn">-</button>
                                    <input type="number" name="quantity" value="1" min="1" />
                                    <button class="plus-btn">+</button>
                                </div>
                                <?php if ($isLoggedIn) { ?>
                                    <button type="submit" class="btn btn-primary" name="add_to_bag">
                                        Add to Bag <i class="fas fa-shopping-cart"></i>
                                    </button>
                                <?php } else { ?>
                                    <p class="text-danger">You must be logged in to add items to the bag.</p>
                                <?php } ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="container">
    <div class="row">
        <div class="col-lg-12 col-12">
            <!-- Popular Items -->
            <h2 class="header">Popular Items</h2>
            <div class="row">
                <?php
                // Fetch four random items from the database
                $randomItemsQuery = "SELECT * FROM menu_items ORDER BY RAND() LIMIT 4";
                $randomItemsResult = mysqli_query($conn, $randomItemsQuery);

                if ($randomItemsResult) {
                    while ($randomItem = mysqli_fetch_assoc($randomItemsResult)) {
                        $randomItem_id = $randomItem['item_id']; // Renamed variable
                        echo '<div class="col-md-3 col-12">';
                        echo '<div class="card m-3 border">';
                        echo '<div class="card-body">';
                        echo '<div class="cardimage">';
                        echo '<a href="menu-detail.php?item_id=' . $randomItem_id . '" style="object-fit: cover;">';
                        echo '<img src="' . $randomItem['image_url'] . '" class="" alt="...">';
                        echo '</a>';
                        echo '<h5 class="card-title header">' . $randomItem['name'] . '</h5>';
                        echo '<h6 class="card-title header">£' . $randomItem['price'] . '</h6>';

                        // Check if the user is logged in
                        if ($isLoggedIn) {
                            echo '<div class="add-to-bag-form">';
                            echo '<input type="hidden" name="random_item_id" value="' . $randomItem_id . '">';
                            echo '<div class="item-quantity">';
                            echo '<button class="minus-btn">-</button>';
                            echo '<input type="number" name="quantity" value="1" min="1" />';
                            echo '<button class="plus-btn">+</button>';
                            echo '</div>';
                            echo '<button class="btn btn-primary add-to-bag" data-itemid="' . $randomItem_id . '">';
                            echo 'Add to Bag <i class="fas fa-shopping-cart"></i>';
                            echo '</button>';
                            echo '</div>';
                        } else {
                            echo '<p class="text-danger">You must be logged in to add items to the bag.</p>';
                        }

                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No popular items available.</p>';
                }
                ?>
            </div>
        </div>
    </div>
</div>
        </div>
                
        <!-- Footer container -->
        <?php include 'footer.php'; ?>

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>
        <script src="js/script.js"></script>
        <script>
    document.addEventListener("DOMContentLoaded", function() {
    const productForm = document.querySelector(".product-form");
    const addToBagButtons = document.querySelectorAll(".add-to-bag");

    productForm.addEventListener("submit", function(event) {
        event.preventDefault(); // Prevent default form submission

        const formData = new FormData(productForm);

        fetch("menu-detail.php", {
            method: "POST",
            body: formData,
        })
        .then(response => {
            console.log("AJAX request complete:", response);
            if (response.ok) {
                const contentType = response.headers.get("content-type");
                if (contentType && contentType.indexOf("application/json") !== -1) {
                    return response.json();
                } else {
                    return response.text(); // Handle non-JSON response as text
                }
            } else {
                throw new Error("Network response was not ok.");
            }
        })
        .then(data => {
            console.log("Data received:", data);
            if (typeof data === "object" && data.success) {
                console.log(data.success);
            } else {
                console.error("Error adding item to bag:", data);
            }
        })
        .catch(error => {
            console.error("Error adding item to bag:", error);
        });
    });

    // Handle "Add to Bag" button click inside popular items
    addToBagButtons.forEach(button => {
        button.addEventListener("click", function(event) {
            const itemID = event.target.getAttribute("data-itemid");
            const quantityInput = event.target.parentElement.querySelector("input[name='quantity']").value;
            
            const formData = new FormData();
            formData.append("add_random_to_bag", true);
            formData.append("random_item_id", itemID);
            formData.append("quantity", quantityInput);

            fetch("menu-detail.php", {
                method: "POST",
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log(data.success);
                } else if (data.error) {
                    console.error("Error adding item to bag:", data.error);
                }
            })
            .catch(error => {
                console.error("Error adding item to bag:", error);
            });
        });
    });
});

</script>
    </body>
</html>
