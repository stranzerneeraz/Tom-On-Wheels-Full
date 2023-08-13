<?php
session_start();
require_once "config.php";

// Check if the user is logged in
$isLoggedIn = isset($_SESSION["user_id"]);

if ($isLoggedIn) {
    $userId = $_SESSION["user_id"];
}

// Form submission handling
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $subject = $_POST["subject"];
    $description = $_POST["description"];

    if ($isLoggedIn) {
        // User is logged in, insert the data along with user_id
        $query = "INSERT INTO contact_us (email, user_id, subject, description) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("siss", $email, $userId, $subject, $description);
        $stmt->execute();
        $stmt->close();
    } else {
        // User is not logged in, show an alert and redirect to login page
        echo "<script>alert('Please login before submitting your message.'); window.location.href='login.php';</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Tom On Wheels - Contact</title>
    <!-- Css, bootstrap and fonts imports -->
    <link rel="stylesheet" type="text/css" href="css/style.css" />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM"
      crossorigin="anonymous"
    />
    <link
      href="https://api.mapbox.com/mapbox-gl-js/v2.6.1/mapbox-gl.css"
      rel="stylesheet"
    />
    <!-- <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    /> -->
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

    <div class="content">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <!-- About us -->
            <h1 class="header">About Us</h1>
            <div class="attractive-layout">
              <p class="attractive-text-justify">
                <b>Tom On Wheels</b> App is a leading provider of on-demand food
                delivery services. We connect hungry customers with their
                favorite restaurants and ensure that delicious food is delivered
                right to their doorsteps. Our mission is to make food delivery
                convenient and hassle-free for everyone. We are a leading food
                delivery app dedicated to providing quality meals and
                exceptional service. With our easy-to-use platform, you can
                browse through various cuisines, place orders, and enjoy a
                convenient dining experience from the comfort of your home.
              </p>
            </div>
            <div class="address">
              <address class="attractive-text-center">
                Visit our office at the following address:<br />
                West Bromwich,<br />
                United Kingdom, <br />
                B71 2RJ
              </address>
            </div>
          </div>
          <div class="col-md-6">
            <div class="contact-form">
              <!-- Contact Us -->
              <h1 class="header">Contact Us</h1>
              <form action="" method="POST">
                <label for="email" class="form-label">Email:</label>
                <input
                  type="email"
                  id="email"
                  name="email"
                  class="form-control"
                  required
                />

                <label for="subject" class="form-label">Subject:</label>
                <input
                  type="text"
                  id="subject"
                  name="subject"
                  class="form-control"
                  required
                />

                <label for="description" class="form-label">Description:</label>
                <textarea
                  id="description"
                  name="description"
                  class="form-control"
                  rows="4"
                  required
                ></textarea>
                <button type="submit" class="btn">Submit</button>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- Map location -->
      <div class="container">
        <div id="map"></div>
      </div>
    </div>

    <!-- Footer container -->
    <?php include 'footer.php'; ?>

    <script src="js/script.js"></script>
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"
      integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS"
      crossorigin="anonymous"
    ></script>
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.6.1/mapbox-gl.js"></script>
    <!-- Load map function called -->
    <script>
      loadMap();
    </script>
  </body>
</html>
