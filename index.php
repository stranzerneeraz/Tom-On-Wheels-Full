<?php
  session_start(); // Start the session
  require_once "config.php"; // Include your config file

  // Check if the user is logged in
  $isLoggedIn = isset($_SESSION["user_id"]);

  if ($isLoggedIn) {
    // User is logged in, you can perform any necessary actions here
    $userId = $_SESSION["user_id"];
  }

  $carouselQuery = "SELECT * FROM carousel_slide";
  $carouselResult = mysqli_query($conn, $carouselQuery);

  $carouselSpecialities = [];
  while ($carouselRow = mysqli_fetch_assoc($carouselResult)) {
    $carouselSpecialities[] = $carouselRow;
  }

  $query = "SELECT * FROM speciality_sections";
  $result = mysqli_query($conn, $query);

  // Create an array to hold the retrieved data
  $specialities = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $specialities[] = $row;
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
      </div> 
    </nav>

    <div class="content">
      <!-- Homepage background banner content -->
      <div id="carouselExampleCaptions" class="carousel slide position-relative">
        <div class="carousel-indicators">
          <?php
            $slideCount = count($carouselSpecialities);
            for ($i = 0; $i < $slideCount; $i++) {
                $indicatorClass = ($i === 0) ? 'active' : '';
                echo '<button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="' . $i . '" class="' . $indicatorClass . '" aria-label="Slide ' . ($i + 1) . '"></button>';
            }
          ?>
        </div>
        <div class="carousel-inner">
          <?php
            foreach ($carouselSpecialities as $i => $slide) {
              $itemClass = ($i === 0) ? 'active' : '';
              echo '<div class="carousel-item ' . $itemClass . '">
                      <img src="' . $slide['carousel_image_url'] . '" class="d-block w-100 banner-image" alt="Food Delivery">
                      <div class="carousel-caption d-none d-md-block">
                        <input type="text" class="form-control mb-3" id="postcodeInput' . $i . '" placeholder="Enter your postcode" />
                        <button class="btn btn-primary" onclick="checkDelivery(' . $i . ')">Check Location</button>
                        <h3 class="header">' . $slide['header'] . '</h3>
                        <p class="attractive-text-center">' . $slide['carousel_description'] . '</p>
                        <p class="delivery-message" id="deliveryMessage' . $i . '"></p>
                      </div>
                    </div>';
            }
          ?>
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
            <?php foreach ($specialities as $speciality) { ?>
              <div class="box" onclick="menuPage()">
                <img class="image" src="<?php echo $speciality['speciality_img_url']; ?>" alt="<?php echo $speciality['name']; ?>">
                <div class="item">
                  <h3 class="small-header"><?php echo $speciality['name']; ?></h3>
                  <p class="attractive-text-center"><?php echo $speciality['speciality_description']; ?></p>
                </div>
              </div>
            <?php } ?>
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
    <script>
      $(document).ready(function () {
    // ...

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

    // ...
});
      function checkDelivery(slideIndex) {
        var postcodeInput = document.getElementById('postcodeInput' + slideIndex).value;
        console.log(postcodeInput);
        var origin = "-2.009392,52.534513"; // Food Delivery App Office coordinates (no spaces)

        // Call the function to get coordinates from postcode
        getCoordinatesFromPostcode(postcodeInput).then(destinationCoordinates => {
          if (destinationCoordinates) {
            var destination = destinationCoordinates.join(','); // Convert [longitude, latitude] to a single string
            // Call the function to calculate the distance
            calculateDistance(origin, destination).then(distance => {
              var deliveryMessage = "";
              if (distance >= 0) {
                if (distance <= 5) {
                  deliveryMessage = "Food is delivered in your area within 15-30 minutes. Choose your food from the menu.";
                } else if (distance <= 10) {
                  deliveryMessage = "Food is delivered in your area within 30-45 minutes. Choose your food from the menu.";
                } else {
                  deliveryMessage = "Sorry! We are unable to deliver in your area. We are expanding our area soon.";
                }
              } else {
                deliveryMessage = "Error calculating distance. Please try again later.";
              }
              alert(deliveryMessage); // Show the alert with the delivery message
            });
          } else {
            alert("Invalid postcode. Please enter a valid postcode.");
          }
        });
      }

      function getCoordinatesFromPostcode(postcode) {
        var mapboxGeocodingToken = 'pk.eyJ1Ijoic3RyYW56ZXJuZWVyYXoiLCJhIjoiY2xqcjJhdjhsMDNrYjNjdnN4a3IyM3l1biJ9.DVRVAz534tJ0fGyhteiEEQ';
        var url = `https://api.mapbox.com/geocoding/v5/mapbox.places/${postcode}.json?access_token=${mapboxGeocodingToken}`;
    
        return fetch(url)
        .then(response => response.json())
        .then(data => {
          if (data.features && data.features.length > 0) {
            return data.features[0].center; // Return the coordinates [longitude, latitude]
          }
          return null; // Indicates error or no data
        });
      }

      function calculateDistance(origin, destination) {
        var mapboxToken = 'pk.eyJ1Ijoic3RyYW56ZXJuZWVyYXoiLCJhIjoiY2xqcjJhdjhsMDNrYjNjdnN4a3IyM3l1biJ9.DVRVAz534tJ0fGyhteiEEQ';
        var url = `https://api.mapbox.com/directions-matrix/v1/mapbox/driving/${origin};${destination}?access_token=${mapboxToken}`;
        
        return fetch(url)
        .then(response => response.json())
        .then(data => {
            
          if (data.durations && data.durations.length > 0) {
            var durationInSeconds = data.durations[0][1];
            var averageSpeedMph = 21.5; // Average speed in miles per hour (adjust as needed)
                
            // Calculate estimated distance based on duration and average speed
            var distanceInMiles = durationInSeconds / 3600 * averageSpeedMph;
                
            return distanceInMiles;
          }
          return -1; // Indicates error or no data
        })
        .then(distance => {
          return distance; // Return the estimated distance
        });
      }
    </script>
  </body>
</html>
