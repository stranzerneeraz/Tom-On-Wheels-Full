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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
                  session_start();
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
  </body>
</html>