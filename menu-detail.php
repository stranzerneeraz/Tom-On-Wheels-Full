<?php
    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    require_once "config.php";

    // Check if user is logged in
    if (!isset($_SESSION["user_id"])) {
        header("Location: login.php");
        exit();
    }

    // The item_id in the URL parameter
    $item_id = $_GET['item_id'];

    // Fetch the item details from the database based on the item_id
    $query = "SELECT * FROM menu_items WHERE item_id = $item_id";
    $result = mysqli_query($conn, $query);
    $item = mysqli_fetch_assoc($result);

    // Fetch 4 random popular items excluding the current item
    $popular_query = "SELECT * FROM menu_items WHERE item_id != $item_id ORDER BY RAND() LIMIT 4";
    $popular_result = mysqli_query($conn, $popular_query);
    $popular_items = mysqli_fetch_all($popular_result, MYSQLI_ASSOC);
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

                              echo '<li class="nav-item">
                                      <a class="nav-link" href="profile.php"> Hi, ' . $_SESSION['name'] . '</a>
                                    </li>';
                            } else {
                              echo '<li class="nav-item">
                                      <a class="nav-link" href="login.php">Login</a>
                                    </li>';
                            }
                        ?>
                    </ul>
                    <form class="d-flex ms-auto" role="search" id="searchForm" action="menu.php" method="GET">
                        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="searchQuery">
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
                            <h1 class="title-header"><?php echo $item['name']; ?></h2>
                            <div class = "product-rating">
                                <i class = "fas fa-star"></i>
                                <i class = "fas fa-star"></i>
                                <i class = "fas fa-star"></i>
                                <i class = "fas fa-star"></i>
                                <i class = "fas fa-star-half-alt"></i>
                                <span>4.7(21)</span>
                            </div>
                            <div class="product-price">
                                <?php if ($item['price'] == $item['deal_price']) { ?>
                                    <p class="price">Price: <span>£<?php echo $item['price']; ?></span></p>
                                <?php } else { ?>
                                    <p class="last-price">Old Price: <span>£<?php echo $item['price']; ?></span></p>
                                    <p class="new-price">Offer Price: <span>£<?php echo $item['deal_price']; ?></span></p>
                                <?php } ?>
                            </div>
                            <div class="product-detail">
                                <h3 class="header">About This Item:</h2>
                                <p class="attractive-text-justify"><?php echo $item['description']; ?></p>
                            </div>
                            <div class="item-quantity">
                                <!-- Quantity input code as before -->
                            </div>
                            <button id="add-to-bag-button" class="btn btn-primary" data-item-id="<?php echo $item_id; ?>" data-item-price="<?php echo $item['deal_price']; ?>">
                                Add to Bag <i class="fas fa-shopping-cart"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="popular-items-container" class="container">
                <div class="row">
                    <div class="col-lg-12 col-12">
                        <!-- Popular Items Section -->
                        <h2 class="header">Popular Items</h2>
                        <div class="row">
                            <?php foreach ($popular_items as $popular_item) { ?>
                                <div class="col-md-3 col-12">
                                    <div class="card m-3 border">
                                        <div class="card-body">
                                            <div class="cardimage">
                                                <a href="menu-detail.php?item_id=<?php echo $popular_item['item_id']; ?>" style="object-fit: cover;">
                                                    <img src="<?php echo $popular_item['image_url']; ?>" class="" alt="...">
                                                </a>
                                                <h5 class="card-title header"><?php echo $popular_item['name']; ?></h5>
                                                <h6 class="card-title header">£<?php echo $popular_item['price']; ?></h6>
                                                <button class="btn btn-primary add-to-bag add-to-bag-popular"
                                                        data-item-id="<?php echo $popular_item['item_id']; ?>"
                                                        data-item-price="<?php echo $popular_item['deal_price']; ?>">
                                                    Add to Bag <i class="fas fa-shopping-cart"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
                
        <!-- Footer container -->
        <?php include 'footer.php'; ?>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>
        <script src="js/script.js"></script>
        <script>
            $(document).ready(function() {
            $("#searchForm").submit(function (event) {
        event.preventDefault(); // Prevent form submission

        var searchQuery = $("input[name='searchQuery']").val();

        $.ajax({
            url: "search.php",
            method: "GET",
            data: { searchQuery: searchQuery },
            success: function (response) {
                $("#filteredResults").html(response);
            },
            error: function (xhr, status, error) {
                console.error(error); // For debugging
            }
        });
    });
               

                const addToBagButton = $("#add-to-bag-button");
                const addToBagPopularButtons = $(".add-to-bag-popular");

                if (addToBagButton.length) {
                    addToBagButton.click(function() {
                        console.log("Add to Bag button clicked!");
                        const itemId = $(this).data("item-id");
                        const itemPrice = $(this).data("item-price");
                        console.log("Item ID:", itemId);

                        $.post("add-to-bag.php", { add_to_bag: true, item_id: itemId, item_price: itemPrice }, function(data) {
                            if (data.success) {
                                alert(data.message); // Display a success message
                            } else {
                                alert(data.message); // Display an error message
                            }
                        }, "json")
                        .fail(function(xhr, status, error) {
                            console.error("Error adding item to bag:", error);
                            console.log("XHR object:", xhr.responseText);
                        });
                    });
                }
                if (addToBagPopularButtons.length) {
                    addToBagPopularButtons.click(function() {
                        console.log("Add to Bag Popular button clicked!");
                        const itemId = $(this).data("item-id");
                        const itemPrice = $(this).data("item-price");
                        console.log("Item ID:", itemId);

                        // Your AJAX code for the "Popular Items" section
                        $.post("add-to-bag.php", { add_to_bag: true, item_id: itemId, item_price: itemPrice }, function(data) {
                            if (data.success) {
                                alert(data.message); // Display a success message
                            } else {
                                alert(data.message); // Display an error message
                            }
                        }, "json")
                        .fail(function(xhr, status, error) {
                            console.error("Error adding item to bag:", error);
                            console.log("XHR object:", xhr.responseText);
                        });
                    });
                }
            });
        </script>
    </body>
</html>
