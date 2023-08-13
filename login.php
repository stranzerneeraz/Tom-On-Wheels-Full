<?php
  // Start the session
  session_start();

  require_once "config.php";

  error_reporting(E_ALL);
  ini_set('display_errors', 1);

  // Initialize error messages
  $loginErrorMessage = "";
  $signupErrorMessage = "";

  // Login Process
  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login-submit"])) {
      $username = $_POST["Email_or_Mobile"];
      $password = $_POST["password"];

      // Check if the user exists in the database
      $checkQuery = "SELECT * FROM users WHERE (email='$username' OR mobile='$username')";
      $result = $conn->query($checkQuery);

      if ($result->num_rows > 0) {
          $user = $result->fetch_assoc();

          // Verify password
          if (password_verify($password, $user["password"])) {
            echo "<script>";
            echo "console.log('Password Verified')";
            echo "</script>";
            
            // Set the user ID in the session
            $_SESSION["user_id"] = $user["user_id"];
            // Redirect to menu.php after successful login
            header("Location:menu.php");
            exit();
          } else {
              $loginErrorMessage = "Invalid email or password.";
              echo "<script>";
              echo "console.log('Password Verification failed')";
              echo "</script>";
          }
      } else {
          $loginErrorMessage = "Invalid email or password.";
      }
  }

  // Signup Process
  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["signup-submit"])) {
      // Fetch user input from the form
      $name = $_POST["signup-name"];
      $email = $_POST["signup-email"];
      $mobile = $_POST["signup-mobile"];
      $password = $_POST["signup-password"];
      $address = $_POST["signup-address"];
      $postcode = $_POST["signup-postcode"];

      // Check if the user already exists in the system
      $checkQuery = "SELECT * FROM users WHERE email='$email' OR mobile='$mobile'";
      $result = $conn->query($checkQuery);

      if ($result->num_rows > 0) {
          $signupErrorMessage = "User already exists in the system.";
      } else {
          // Hash the password before storing it in the database
          $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

          // Insert the new user into the database
          $insertQuery = "INSERT INTO users (name, email, mobile, password, address, postcode) VALUES ('$name', '$email', '$mobile', '$hashedPassword', '$address', '$postcode')";

          if ($conn->query($insertQuery) === TRUE) {
              // Redirect to login page after successful signup
              header("Location: login.php");
              exit();
          } else {
              $signupErrorMessage = "Error: " . $insertQuery . "<br>" . $conn->error;
          }
      }
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Tom On Wheels - Login</title>
    <!-- Css, bootstrap and fonts imports -->
    <link rel="stylesheet" type="text/css" href="css/style.css" /> 
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
                <li class="nav-item">
                  <a class="nav-link" href="login.php">Login</a>
                </li>
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
        <!-- Login container -->
        <h1 class="header">Login</h1> 
        <div class="form-container">
          
          <form action="login.php" class="login-form" method="POST" onsubmit="return validateLogin()">
            <label for="username" class="label-text">Email or Mobile:</label>
            <input type="text" id="username" placeholder="Enter Email or Mobile Number" name="Email_or_Mobile" required>
            
            <label for="password" class="label-text">Password:</label>
            <input type="password" id="password" placeholder="Enter Password" name="password" required>
            
            <input type="hidden" name="login-submit" value="1">

            <?php if (!empty($loginErrorMessage)) : ?>
            <p class="error-message"><?php echo $loginErrorMessage; ?></p>
            <?php endif; ?>

            <button class="btn btn-primary" type="submit">Login</button>
            <span class="forget-password attractive-text-center"><a href="#">Forget Password?</a></span>
          </form>
        </div>
    
        <!-- Continue with social sites for easy login -->
        <div class="social-login">
          <p class="attractive-text-center">Or continue with:</p>
          
          <button type="button" class="btn btn-primary social-btn">
            Continue with Google <i class="fab fa-google"></i>
          </button>
          <br/>

          <button type="button" class="btn btn-primary social-btn">
            Continue with Apple <i class="fab fa-apple"></i>
          </button>
          <br/>
          
          <button type = "button" class = "btn btn-primary social-btn">
            Continue with Facebook <i class = "fab fa-facebook"></i>
          </button>
        </div>
        <p class="signup-link attractive-text-center mb-0">Don't have an account? <a href="#" onclick="showSignup()">Sign up</a></p>
       
      </div>

      <!-- Signup form displayed after clicking signup link -->
      <div class="signup-container" id="signupContainer">
        <div class="signup-form">
          <h1 class="header">Sign Up</h1>
          <?php if ($signupErrorMessage) : ?>
            <p class="error-message"><?php echo $signupErrorMessage; ?></p>
          <?php endif; ?>
          <form action="" method="POST" onsubmit="return validateSignupForm()">
            <label for="signup-name" class="label-text">Name:</label>
            <input type="text" id="signup-name" name="signup-name" required>

            <label for="signup-email" class="label-text">Email Address:</label>
            <input type="email" id="signup-email" name="signup-email" required>

            <label for="signup-mobile" class="label-text">Mobile Number:</label>
            <div class="input-group">
              <select id="country-code" name="country-code">
                <option value="+44">+44</option>
                <option value="+1">+1</option>
              </select>
              <input type="tel" id="signup-mobile" name="signup-mobile" required>
            </div>
            
            <label for="signup-password" class="label-text">Password:</label>
            <input type="password" id="signup-password" name="signup-password" required>

            <label for="signup-confirm-password" class="label-text">Confirm Password:</label>
            <input type="password" id="signup-confirm-password" name="signup-confirm-password" required>

            <label for="signup-address" class="label-text">Address:</label>
            <textarea id="signup-address" name="signup-address" rows="2" required></textarea>

            <label for="signup-postcode" class="label-text">Postcode:</label>
            <input type="text" id="signup-postcode" name="signup-postcode" required>
            
            <button class="btn btn-primary" type="submit" onclick="">Sign Up</button>
            </form>
            <p class="signup-link attractive-text-center">Already have an account? <a href="#" onclick="hideSignup()">Log in</a></p>
            <button type="button" class="btn cancel-btn" onclick="hideSignup()">Cancel</button>
          </form>
        </div>
      </div>
    </div>

    <!-- Footer Container -->
    <?php include 'footer.php'; ?>

    <script src="js/script.js"></script>
    <script>
      // Inline JavaScript to call the updateNavbar function
      var isLoggedIn = <?php echo isset($_SESSION["user_id"]) ? "true" : "false"; ?>;
      updateNavbar(isLoggedIn);
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>
  </body>
</html>
