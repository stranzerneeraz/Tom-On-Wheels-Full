<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"> -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css"> -->
    <!-- <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" /> -->

<!-- PHP Code to Fetch Item Details -->
<?php
session_start();
include 'config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in
$isLoggedIn = isset($_SESSION["user_id"]);

try {
    if (!isset($_GET['item_id'])) {
        throw new Exception("Invalid item ID.");
    }
    $item_id = $_GET['item_id'];

    // Fetch the item details from the database based on the item_id
    $query = "SELECT * FROM menu_items WHERE item_id = $item_id";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        throw new Exception("Error fetching item details: " . mysqli_error($conn));
    }

    $item = mysqli_fetch_assoc($result);

    if (isset($_POST['add_to_bag'])) {
        if ($isLoggedIn) {
            $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
            $user_id = $_SESSION["user_id"];
            $name = mysqli_real_escape_string($conn, $item['name']);
            $image_url = mysqli_real_escape_string($conn, $item['image_url']);
    
            $insertQuery = "INSERT INTO order_bag (user_id, item_id, name, image_url, quantity) 
                            VALUES ('$user_id', '$item_id', '$name', '$image_url', '$quantity')";
            
            if (mysqli_query($conn, $insertQuery)) {
                echo "Item added to bag successfully!";
            } else {
                echo "Error adding item to bag: " . mysqli_error($conn);
            }
        } else {
            echo "You must be logged in to add items to the bag.";
        }
    }
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();
    // You can also log the error for debugging purposes
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
                                echo "<script>";
                                echo "console.log('Logged in navbar')";
                                echo "</script>";
                                echo '<li class="nav-item">
                                        <a class="nav-link" href="javascript:void(0);" onclick="confirmLogout()">Logout</a>
                                    </li>';
                            } else {
                                echo "<script>";
                                echo "console.log('Logged out navbar')";
                                echo "</script>";
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
                      <div class="product-detail">
                          <h2 class="header">About This Item:</h2>
                          <p class="attractive-text-justify"><?php echo $item['description']; ?></p>
                      </div>
                      <form method="post">
    <input type="hidden" name="item_id" value="<?php echo $item_id; ?>">
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
                echo '<form method="post">';
                echo '<input type="hidden" name="item_id" value="' . $randomItem_id . '">';
                echo '<input type="hidden" name="name" value="' . $randomItem['name'] . '">';
                echo '<input type="hidden" name="price" value="' . $randomItem['price'] . '">';
                echo '<input type="hidden" name="image_url" value="' . $randomItem['image_url'] . '">';
                echo '<div class="item-quantity">';
                echo '<button class="minus-btn">-</button>';
                echo '<input type="number" name="quantity" value="1" min="1" />';
                echo '<button class="plus-btn">+</button>';
                echo '</div>';
                echo '<button type="submit" class="btn btn-primary add-to-bag" name="add_to_bag">Add to Bag <i class="fas fa-shopping-cart"></i></button>';
                echo '</form>';
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
</body>
</html>
