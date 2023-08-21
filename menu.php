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

    // Add item to bag
    if (isset($_POST["add_to_bag"])) {
        $item_id = $_POST["item_id"];
        $user_id = $_SESSION["user_id"];
        
        $item_query = "SELECT * FROM menu_items WHERE item_id = $item_id";
        $item_result = $conn->query($item_query);
    
    if ($item_result->num_rows > 0) {
        $item_data = $item_result->fetch_assoc();
        $name = $item_data["name"];
        $image_url = $item_data["image_url"];
        $price = $item_data["price"];
        
        // Check if the item already exists in the bag for the user
        $check_query = "SELECT * FROM order_bag WHERE item_id = $item_id AND user_id = $user_id";
        $check_result = $conn->query($check_query);

        if ($check_result->num_rows > 0) {
            // Item already exists, update quantity
            $update_query = "UPDATE order_bag SET quantity = quantity + 1, order_price = order_price + $price WHERE item_id = $item_id AND user_id = $user_id";
            if ($conn->query($update_query)) {
                $response = array("success" => true, "message" => "Item quantity updated in the bag.");
            } else {
                $response = array("success" => false, "message" => "Failed to update item quantity in the bag.");
            }
        } else {
            // Item does not exist, insert into the bag
            $insert_query = "INSERT INTO order_bag (item_id, user_id, name, image_url, quantity, order_date, order_price) VALUES ($item_id, $user_id, '$name', '$image_url', 1, CURDATE(), $price)";
            if ($conn->query($insert_query)) {
                $response = array("success" => true, "message" => "Item added to the bag.");
            } else {
                $response = array("success" => false, "message" => "Error adding item to the bag.");
            }
        }

        // Send JSON response
        header("Content-Type: application/json");
        echo json_encode($response);
        exit(); // Terminate script execution
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Tom On Wheels - Menu</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" type="text/css" href="css/style.css" /> 
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/14.6.3/nouislider.min.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    </head>

    <body>
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
                    <form class="d-flex ms-auto" role="search" id="searchForm">
                        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="searchQuery">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </form>
                </div>
            </div> 
        </nav>

    <div class="content ">
        <div class="container pt-5 pb-5">
            <div class="card">
                <h1 class="header">Explore Menu</h1>
                <div class="card-body p-5">
                    <div class="row">
                        <!-- Filter section -->
                        <div class="col-lg-2 col-md-3 col-sm-12 col-12">
                            <div class="menu-options">
                                <div class="menu-controls">
                                    <div class="filters">
                                        <h4>Filters:</h4>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="vegetarianCheckbox">
                                            <label class="form-check-label" for="vegetarianCheckbox">Vegetarian</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="nonVegetarianCheckbox">
                                            <label class="form-check-label" for="nonVegetarianCheckbox">Non-Vegetarian</label>
                                        </div>
                                    </div>
                                </div>
                
                                <div class="price-range">
                                    <h4 class="form-label">Price Range:</h4>
                                    
                                    <div id="priceSlider"></div>
                                    
                                    <div class="d-flex align-items-center">
                                        <label for="minPrice" class="form-label me-2">Min Price:</label>
                                        <input type="number" class="form-control" id="minPrice" value="0">
                                    </div>
                                    
                                    <div class="d-flex align-items-center">
                                        <label for="maxPrice" class="form-label me-2">Max Price:</label>
                                        <input type="number" class="form-control" id="maxPrice" value="10">
                                    </div>
                                    
                                    <button class="btn btn-primary" id="applyPriceRange">Apply</button>
                                </div>
                        
                            </div>
                        </div>
                        <!-- Results section -->
                        <div class="col-lg-10 col-12">
                            <div class="row justify-content-start" id="filteredResults">
                                <?php
                                    // Check if there's a search query
                                    if (isset($_GET['searchQuery'])) {
                                        // Include the search logic from search.php
                                        require_once "search.php";
                                    } else {
                                        $sql = "SELECT * FROM menu_items";
                                        $result = $conn->query($sql);

                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
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
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Pagination section -->
                <ul class="pagination">
                    <li class="disabled"><a href="#">Previous</a></li>
                    <li class="active"><a href="#">1</a></li>
                    <li><a href="#">2</a></li>
                    <li><a href="#">3</a></li>
                    <li><a href="#">Next</a></li>
                </ul>
            </div>
        </div>
    
        <!-- Footer section -->
        <?php include 'footer.php'; ?>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/14.6.3/nouislider.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>

        <script src="js/script.js"></script>
        <script>
            var isLoggedIn = <?php echo isset($_SESSION["user_id"]) ? "true" : "false"; ?>;
            updateNavbar(isLoggedIn);

            $(document).ready(function () {
    $("#searchForm").submit(function (event) {
        event.preventDefault(); // Prevent form submission

        var searchQuery = $("input[name='searchQuery']").val();

        // Update browser history
        var newUrl = updateQueryStringParameter(window.location.href, 'searchQuery', searchQuery);
        console.log(newUrl);
        history.pushState({}, '', newUrl);

            // Fetch and display search results on the same page
            fetchSearchResults(searchQuery);
        
    });

    // ...

    function fetchSearchResults(searchQuery) {
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
    }

    // Function to update a URL parameter
    function updateQueryStringParameter(uri, key, value) {
        var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
        var separator = uri.indexOf('?') !== -1 ? "&" : "?";
        if (uri.match(re)) {
            return uri.replace(re, '$1' + key + "=" + value + '$2');
        }
        return uri + separator + key + "=" + value;
    }

    // ...
});


                var priceSlider = document.getElementById('priceSlider');
                var minPriceInput = document.getElementById('minPrice');
                var maxPriceInput = document.getElementById('maxPrice');
        
                noUiSlider.create(priceSlider, {
                    start: [0, 10], // Initial values
                    connect: true, // Connect the two thumbs
                    range: {
                        'min': 0,
                        'max': 10
                    }
                });
        
                // Link slider values to input fields
                priceSlider.noUiSlider.on('update', function(values, handle) {
                    if (handle === 0) {
                        minPriceInput.value = Math.round(values[handle]);
                    } else {
                        maxPriceInput.value = Math.round(values[handle]);
                    }
                });

                // Apply button click event
        $('#applyPriceRange').click(function() {
            updateFilteredItems();
        });

        function updateFilteredItems() {
            var vegetarianChecked = $('#vegetarianCheckbox').prop('checked');
            var nonVegetarianChecked = $('#nonVegetarianCheckbox').prop('checked');
            var minPrice = $('#minPrice').val();
            var maxPrice = $('#maxPrice').val();

            console.log("vegetarian:", vegetarianChecked,
                    "nonVegetarian:", nonVegetarianChecked,
                    "minPrice:", minPrice,
                    "maxPrice:", maxPrice)

                    $.ajax({
        url: 'filter.php',
        method: 'GET',
        data: {
            vegetarian: vegetarianChecked,
            nonVegetarian: nonVegetarianChecked,
            minPrice: minPrice,
            maxPrice: maxPrice
        },
                success: function(response) {
                    $('#filteredResults').html(response);
                }
            }).then(function(response) {
                console.log(response);
            })
        }


                $(".add-to-bag").on("click", function () {
                    var itemId = $(this).data("item-id");
                
                    $.ajax({
                        url: "menu.php",
                        method: "POST",
                        data: { add_to_bag: true, item_id: itemId }, // Include add_to_bag flag
                        dataType: "json",
                        success: function (response) {
                            if (response.success) {
                                // Item added successfully, update UI if needed
                            } else {
                                // Error adding item, handle accordingly
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error(error); // For debugging
                        }
                    });
                });
            
        </script>
    </body>
</html>
 