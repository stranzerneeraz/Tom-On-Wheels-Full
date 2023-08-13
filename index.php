<?php
session_start(); // Start the session
require_once "config.php"; // Include your config file

// Check if the user is logged in
$isLoggedIn = isset($_SESSION["user_id"]);

if ($isLoggedIn) {
    // User is logged in, you can perform any necessary actions here
    $userId = $_SESSION["user_id"];
    // ... (perform additional logic if needed)
}
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Tom On Wheels - Home</title>
    <!-- Meta characters -->
    <meta name="Order Delicious Food Online" content="Order delicious food online and have it delivered to your doorstep. Discover a wide range of mouth-watering dishes.">
    <meta name="keywords" content="Tom On Wheels, food delivery, food at doorstep, restaurant, gathering, ordering, menu, orders, visiting, contact, login">
    <!-- Css, bootstrap and fonts imports -->
    <link rel="stylesheet" type="text/css" href="css/style.css" /> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  </head>

  <body>
    <!-- Navbar container -->
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
      <!-- Homepage background banner content -->
      <div id="carouselExampleCaptions" class="carousel slide position-relative">
        <div class="carousel-indicators">
          <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
          <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
          <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
          <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="3" aria-label="Slide 4"></button>
        </div>
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img src="images/banner5.jpg" class="d-block w-100 banner-image" alt="Food Delivery">
            <div class="carousel-caption d-none d-md-block">
              <input type="text" class="form-control mb-3" placeholder="Enter your location" />
              <button class="btn btn-primary">Check Location</button>
              <h3 class="header">Order Delicious Food Online</h5>
              <p class="attractive-text-center">Discover a wide range of mouth-watering dishes<br>and have them delivered to your doorstep.</p>
            </div>
          </div>
          <div class="carousel-item">
            <img src="images/banner6.jpg" class="d-block w-100" alt="Food Delivery">
            <div class="carousel-caption d-none d-md-block">
              <input type="text" class="form-control mb-3" placeholder="Enter your location" />
              <button class="btn btn-primary">Check Location</button>
              <h3 class="header">Order Delicious Food Online</h5>
              <p class="attractive-text-center">Discover a wide range of mouth-watering dishes<br>and have them delivered to your doorstep.</p>
            </div>
          </div>
          <div class="carousel-item">
            <img src="images/banner3.jpg" class="d-block w-100" alt="Food Delivery">
            <div class="carousel-caption d-none d-md-block">
              <input type="text" class="form-control mb-3" placeholder="Enter your location" />
              <button class="btn btn-primary">Check Location</button>
              <h3 class="header">Order Delicious Food Online</h5>
              <p class="attractive-text-center">Discover a wide range of mouth-watering dishes<br>and have them delivered to your doorstep.</p>
            </div>
          </div>
          <div class="carousel-item">
            <img src="images/banner4.jpg" class="d-block w-100" alt="Food Delivery">
            <div class="carousel-caption d-none d-md-block">
              <input type="text" class="form-control mb-3" placeholder="Enter your location" />
              <button class="btn btn-primary">Check Location</button>
              <h3 class="header">Order Delicious Food Online</h5>
              <p class="attractive-text-center">Discover a wide range of mouth-watering dishes<br>and have them delivered to your doorstep.</p>
            </div>
          </div>
        </div>
        <div class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </div>
        <div class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </div>
      </div>
    
      <div class="container">
        <!-- Our application speciality -->
        <section class="speciality-section" id="speciality">
          <h1 class="header"> Our Speciality</h1>
          <div class="box-container">
            <div class="box" onclick="menuPage()">
              <img class="image" src="images/tasty-burger.jpg" alt="burger">
              <div class="item">
                <h3 class="small-header">Tasty Burger</h3>
                <p class="attractive-text-center">A delicious and flavorful burger made with high-quality ingredients.</p>
              </div>
            </div>
            <div class="box" onclick="menuPage()">
              <img class="image" src="images/tasty-pizza.jpg" alt="pizza">
              <div class="item">
                <h3 class="small-header">Tasty Pizza</h3>
                <p class="attractive-text-center">Authentic Italian pizza topped with fresh ingredients and a perfectly crispy crust.</p>
              </div>
            </div>
            <div class="box" onclick="menuPage()">
            <img class="image" src="images/tasty-momo.jpg" alt="momo">
              <div class="item">
                <h3 class="small-header">Tasty Momo</h3>
                <p class="attractive-text-center">Delicious momos filled with a savory and flavorful stuffing, served with a tasty dipping sauce.</p>
              </div>
            </div>
            <div class="box" onclick="menuPage()">
              <img class="image" src="images/tasty-curry-naan.jpg" alt="curry naan">
              <div class="item">
                <h3 class="small-header">Tasty Curry Naan</h3>
                <p class="attractive-text-center">Soft and fluffy naan bread paired with a delectable curry, bursting with aromatic flavors.</p>
              </div>
            </div>
            <div class="box" onclick="menuPage()">
              <img class="image" src="images/tasty-noodles.jpg" alt="noodles">
              <div class="item">
                <h3 class="small-header">Tasty Noodles</h3>
                <p class="attractive-text-center">Mouthwatering noodles cooked to perfection and tossed with a flavorful sauce and fresh vegetables.</p>
              </div>
            </div>
            <div class="box" onclick="menuPage()">
              <img class="image" src="images/cold-drinks.jpg" alt="cold drinks">
              <div class="item">
                <h3 class="small-header">Chilled Cold Drinks</h3>
                <p class="attractive-text-center">Refreshing and chilled beverages to quench your thirst and complement your meal.</p>
              </div>
            </div>
          </div>
        </section>

        <!-- Special features of the store -->
        <section class="features-section mb-0">
        <h1 class="header"> Our Services</h1>
          <div class="row">
            <div class="col-md-4">
              <div class="feature">
                <i class="fa fa-clock"></i>
                <h3 class="small-header">24/7 Availability</h3>
                <p class="attractive-text-center">Order your favorite food anytime, anywhere. We're here to serve you 24/7.</p>
              </div>
            </div>
            <div class="col-md-4">
              <div class="feature">
                <i class="fa fa-truck"></i>
                <h3 class="small-header">Fast Delivery</h3>
                <p class="attractive-text-center">Experience quick and efficient delivery service. Your food will arrive fresh and hot.</p>
              </div>
            </div>
            <div class="col-md-4">
              <div class="feature">
                <i class="fa fa-star"></i>
                <h3 class="small-header">Quality Food</h3>
                <p class="attractive-text-center">We ensure that only the finest ingredients are used in preparing your meals.</p>
              </div>
            </div>
          </div>
        </section> 
      </div>
    </div>

    <!-- Footer Container -->
    <?php include 'footer.php'; ?>

    <script src="js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>
  </body>
</html>
