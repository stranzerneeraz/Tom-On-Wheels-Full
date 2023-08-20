<?php
session_start(); // Start the session
require_once "config.php"; // Include your config file

// Check if the user is logged in
$isLoggedIn = isset($_SESSION["user_id"]);

if ($isLoggedIn) {
    // User is logged in, you can perform any necessary actions here
    $userId = $_SESSION["user_id"];
    // ... (perform additional logic if needed)
    $user_query = "SELECT * FROM users WHERE user_id = $userId";
    $user_result = mysqli_query($conn, $user_query);

    if ($user_result && mysqli_num_rows($user_result) > 0) {
        $userData = mysqli_fetch_assoc($user_result);
    }
}

if (isset($_POST["update_profile"])) {
    // Handle updating profile information
    $name = $_POST["name"];
    $mobile = $_POST["mobile"];
    $email = $_POST["email"];
    $address = $_POST["address"];
    $postcode = $_POST["postcode"];

    $updateProfileQuery = "UPDATE users SET name = '$name', mobile = '$mobile', email = '$email', address = '$address', postcode = '$postcode' WHERE user_id = $userId";
    mysqli_query($conn, $updateProfileQuery);
}

if (isset($_POST["update_password"])) {
    // Handle updating password
    $oldPassword = $_POST["old_password"];
    $newPassword = $_POST["new_password"];
    $confirmPassword = $_POST["confirm_password"];

    // Verify old password
    $verifyPasswordQuery = "SELECT password FROM users WHERE user_id = $userId";
    $result = mysqli_query($conn, $verifyPasswordQuery);
    $user = mysqli_fetch_assoc($result);

    if (password_verify($oldPassword, $user["password"])) {
        // Verify and update new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $updatePasswordQuery = "UPDATE users SET password = '$hashedPassword' WHERE user_id = $userId";
        mysqli_query($conn, $updatePasswordQuery);
        
    }
}
?>

<html>
    <head>
        <title>Tom On Wheels - Orders</title>
        <!-- Css, bootstrap and fonts imports -->
        <link rel="stylesheet" type="text/css" href="css/style.css" /> 
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
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
                                            <a class="nav-link" href="#"> Hi, ' . $_SESSION['name'] . '</a>
                                        </li>';
                            } else {
                                echo '<li class="nav-item">
                                            <a class="nav-link" href="login.php">Login</a>
                                        </li>';
                            }
                        ?>
                    </ul>
                    <form class="d-flex ms-auto" role="search" id="search-form">
                        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" id="search-input">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </form>
                    <div id="search-results"></div>
                </div>
            </div> 
        </nav>

        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 order-history">
                        <h2 class="header">Your Order History</h2>
                        <?php
                            if ($isLoggedIn) {
                                $orderQuery = "SELECT * FROM order_history WHERE user_id = $userId";
                                $orderResult = mysqli_query($conn, $orderQuery);

                                if ($orderResult && mysqli_num_rows($orderResult) > 0) {
                                    echo '<ul class="order-history-list">';
                                    while ($orderData = mysqli_fetch_assoc($orderResult)) {
                                        echo '<li>';
                                        echo '<p><strong>Order ID:</strong> ' . $orderData['order_id'] . '</p>';
                                        echo '<p><strong>Order Date:</strong> ' . $orderData['order_date'] . '</p>';
                                        echo '<p><strong>Order Name:</strong> ' . $orderData['order_name'] . '</p>';
                                        echo '<p><strong>Total Price:</strong> £' . $orderData['total_price'] . '</p>';
                                        echo '</li>';
                                    }
                                    echo '</ul>';
                                } else {
                                    echo '<p>No order history available.</p>';
                                }
                            } else {
                                echo '<p>You are not logged in.</p>';
                            }
                        ?>
                    </div>
                    <div class="col-md-6 update-profile">
                        <h2 class="header">Update Profile</h2>
                        <?php if ($isLoggedIn): ?>
                            <form id="update-form" action="profile.php" method="post">
                                <label for="name">Name</label>
                                <input type="text" id="name" name="name" placeholder="Name" value="<?php echo $userData['name']; ?>">
                                <label for="mobile">Mobile</label>
                                <input type="text" id="mobile" name="mobile" placeholder="Mobile" value="<?php echo $userData['mobile']; ?>">
                                <label for="email">Email</label><br/>
                                <input class = "profile-email" type="email" id="email" name="email" placeholder="Email" value="<?php echo $userData['email']; ?>" class="input-text"> <br/>
                                <label for="address">Address</label>
                                <input type="text" id="address" name="address" placeholder="Address" value="<?php echo $userData['address']; ?>">
                                <label for="postcode">Postcode</label>
                                <input type="text" id="postcode" name="postcode" placeholder="Postcode" value="<?php echo $userData['postcode']; ?>">
                                <button class="btn btn-primary" type="submit" name="update_profile">Update Profile</button>
                            </form>
                            <h4 class="header">Change Password</h4>
                            <form id="password-form" action="profile.php" method="post">
                                <label for="old_password">Old Password</label>
                                <input type="password" id="old_password" name="old_password" placeholder="Old Password">
                                <label for="new_password">New Password</label>
                                <input type="password" id="new_password" name="new_password" placeholder="New Password">
                                <label for="confirm_password">Confirm New Password</label>
                                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm New Password">
                                <button class="btn btn-primary btn-change-password" type="submit" name="update_password">Change Password</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

    <!-- Footer Section -->
    <?php include 'footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>
    <script src="js/script.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.getElementById("update-form").addEventListener("submit", function (event) {
                if (!validateProfileForm()) {
                    event.preventDefault();
                }
            });

            document.getElementById("password-form").addEventListener("submit", function (event) {
                if (!validatePasswordForm()) {
                    event.preventDefault();
                }
            });

            function validateProfileForm() {
                var name = document.getElementById("name").value;
                var mobile = document.getElementById("mobile").value;
                var email = document.getElementById("email").value;
                var address = document.getElementById("address").value;
                var postcode = document.getElementById("postcode").value;
                
                var isValid = true;
                
                if (name.trim() === "") {
                    highlightField("name");
                    isValid = false;
                }
                
                if (mobile.trim() === "" || !isValidMobile(mobile)) {
                    highlightField("mobile");
                    isValid = false;
                }
                
                if (!isValidEmail(email)) {
                    highlightField("email");
                    isValid = false;
                }
                
                if (address.trim() === "") {
                    highlightField("address");
                    isValid = false;
                }
                
                if (postcode.trim() === "") {
                    highlightField("postcode");
                    isValid = false;
                }
                
                return isValid;
            }

            function validatePasswordForm() {
                var oldPassword = document.getElementById("old_password").value;
                var newPassword = document.getElementById("new_password").value;
                var confirmPassword = document.getElementById("confirm_password").value;

                var isValid = true;

                if (oldPassword.trim() === "") {
                    highlightField("old_password");
                    isValid = false;
                }

                if (newPassword.length < 8 || !passwordRequirementsMet(newPassword)) {
                    highlightField("new_password");
                    isValid = false;
                }

                if (newPassword !== confirmPassword) {
                    highlightField("confirm_password");
                    isValid = false;
                }

                return isValid;
            }

            function highlightField(fieldId) {
                var field = document.getElementById(fieldId);
                field.style.borderColor = "red";
                field.addEventListener("input", function () {
                    field.style.borderColor = "";
                });
            }
        });

        function passwordRequirementsMet($password) {
            return strlen($password) >= 8 && preg_match('/[A-Z]/', $password) && preg_match('/[0-9]/', $password) && preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $password);
        }
    </script>

  </body>
</html>
