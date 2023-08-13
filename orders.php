<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read the request body and decode the JSON data
    $item = json_decode(file_get_contents('php://input'), true);

    // Call the addToCart function to add the item to the session cart
    addToCart($item);

    echo "Item added to cart.";
}

// Function to add an item to the cart
function addToCart($item)
{
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Check if the item is already in the cart
    if (isset($_SESSION['cart'][$item['id']])) {
        $_SESSION['cart'][$item['id']]['quantity'] += 1;
    } else {
        $_SESSION['cart'][$item['id']] = array(
            'id' => $item['id'],
            'name' => $item['name'],
            'price' => $item['price'],
            'quantity' => 1,
            'image_url' => $item['image_url'],
        );
    }
}
?>


<!DOCTYPE html>
<html>
  <head>
    <title>Tom On Wheels - Orders</title>
    <!-- Css, bootstrap and fonts imports -->
    <link rel="stylesheet" type="text/css" href="css/style.css" /> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
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
      <div class="container">
        <!-- Cart Items -->
<div class="cart-items">
    <?php
    if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
        foreach ($_SESSION['cart'] as $item) {
            echo '<div class="cart-item">';
            echo '<div class="item-image">';
            echo '<img src="' . $item['image_url'] . '" alt="' . $item['name'] . '" />';
            echo '</div>';
            echo '<div class="item-quantity">';
            echo '<button class="minus-btn">-</button>';
            echo '<input type="number" value="' . $item['quantity'] . '" min="1" />';
            echo '<button class="plus-btn">+</button>';
            echo '</div>';
            echo '<div class="item-details">';
            echo '<h3 class="header">' . $item['name'] . '</h3>';
            echo '</div>';
            echo '<div class="item-remove">';
            echo '<p class="price">£' . number_format($item['price'] * $item['quantity'], 2) . '</p>';
            echo '<button class="btn-remove"> </button>';
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo 'Your cart is empty.';
    }
    ?>
</div>

<div class="cart-total">
    <?php
    // Calculate the total price of items in the cart
    $total = 0;
    if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
    }
    echo '<h4>Total: £' . number_format($total, 2) . '</h4>';
    ?>
</div>

        <!-- Checkout Panel with payment method and detail -->
        <div class="checkout-panel mb-0">
          <div class="panel-body">
            <h2 class="header">Proceed with Payment</h2>
            <div class="payment-method">
              <label for="credit-card" class="method">
                <div class="card-logos">
                  <img src="images/visa.png" alt="Visa Logo" />
                  <img src="images/mastercard.png" alt="Mastercard Logo" />
                </div>
                <div class="radio-input">
                  <input id="card" type="radio" name="payment" />
                  Pay £26.46 with Credit Card
                </div>
              </label>
              <label for="paypal" class="method paypal">
                <div class="card-logos">
                  <img src="images/paypal.png" alt="PayPal Logo" />
                </div>  
                <div class="radio-input">
                  <input id="paypal" type="radio" name="payment" />
                  Pay £26.46 with PayPal
                </div>
              </label>
              <label for="apple-pay" class="method apple-pay">
                <div class="card-logos">
                  <div class="image-container">
                    <img src="images/apple-pay.png" alt="Apple Pay Logo" />  
                  </div>
                </div>  
                <div class="radio-input">
                  <input id="paypal" type="radio" name="payment" />
                  Pay £26.46 with Apple Pay
                </div>
              </label>
            </div>
            <div class="input-fields">
              <div class="column-1">
                <label for="cardholder">Name</label>
                <input type="text" id="cardholder" />
                <div class="small-inputs">
                  <div>
                    <label for="date">Valid date</label>
                    <input type="text" id="date" />
                  </div>
                  <div>
                    <label for="verification">CVV / CVC *</label>
                    <input type="password" id="verification" />
                  </div>
                </div>
              </div>
              <div class="column-2">
                <label for="cardnumber">Card Number</label>
                <input type="password" id="cardnumber" />
                <span class="info">* CVV or CVC is the card security code, unique three digits number on the back of your card separate from its number.</span>
              </div>
            </div>
          </div>
          <button class="btn btn-primary">Checkout</button>
        </div>        
      </div>
    </div>

    <!-- Footer Section -->
    <?php include 'footer.php'; ?>

    <script src="js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>
  </body>
</html>
