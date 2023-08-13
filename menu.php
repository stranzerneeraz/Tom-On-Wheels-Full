<?php
    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    require_once "config.php";

    echo "Session user_id: " . $_SESSION["user_id"];

echo "<script>";
echo "console.log('Session ID: " . session_id() ."')";
echo "</script>";
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Tom On Wheels - Menu</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" type="text/css" href="css/style.css" /> 
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
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

    <div class="content ">
      <div class="container pt-5 pb-5">
        <div class="card">
          <h1 class="header">Explore Menu</h1>
          <div class="card-body p-5">
            <div class="row">
              <div class="col-lg-2 col-md-3 col-sm-12 col-12">
                <div class="menu-options">
                  <div class="menu-option">
                    <h4>Browse By:</h4>
                    <ul>
                      <li><a href="#">Restaurant</a></li>
                      <li><a href="#">Food</a></li>
                      <li><a href="#">Country</a></li>
                    </ul>
                  </div>
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
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="dealsCheckbox">
                        <label class="form-check-label" for="dealsCheckbox">Deals Only</label>
                      </div>
                    </div>
                  </div>
    
                  <div class="price-range">
                    <h4 for="price" class="form-label">Price Range:</h4>
                    <label for="price" class="form-label">Price Range:</label>
                    <input type="range" class="form-range" min="0" max="5" id="priceRange" value="0" step="10">
                    <!-- <input type="range" id="priceRange" min="0" max="100" value="0" step="10"> -->
                    <span id="priceRangeValue">£0</span>
                  </div>
              
                </div>
            </div>
            <div class="col-lg-10 col-12">
                <div class="row">
                    <?php
                    $sql = "SELECT * FROM menu_items";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                        echo '
                        <div class="col-md-4 col-12">
                            <div class="card m-3 border">
                            <a href="menu-detail.php?item_id=' . $row["item_id"] . '">
                                <img src="' . $row["image_url"] . '" class="card-img-top" alt="...">
                            </a>
                            <div class="card-body">
                                <h5 class="card-title header">' . $row["name"] . '</h5>
                                <h6 class="card-title header">£' . $row["price"] . '</h6>
                                <button class="btn btn-primary add-to-bag">Add to Bag<i class="fas fa-shopping-cart"></i></button>
                            </div>
                            </div>
                        </div>';
                        }
                    } else {
                        echo "0 results";
                    }
                    ?>
                </div>
                </div>
            </div>
          </div>
        </div>
        <ul class="pagination">
          <li class="disabled"><a href="#">Previous</a></li>
          <li class="active"><a href="#">1</a></li>
          <li><a href="#">2</a></li>
          <li><a href="#">3</a></li>
          <li><a href="#">Next</a></li>
        </ul>
      </div>
    </div>
      
    <?php include 'footer.php'; ?>

    <script src="js/script.js"></script>
    <script>
      var isLoggedIn = <?php echo isset($_SESSION["user_id"]) ? "true" : "false"; ?>;
      updateNavbar(isLoggedIn);
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>
  </body>
</html>
 