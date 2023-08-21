<?php
  session_start();
  require_once 'config.php'; // Include your database connection configuration

  // Fetch cart items from the database if the user is logged in
  $cart_items = array();
  if (isset($_SESSION["user_id"])) {
      $user_id = $_SESSION["user_id"];
      $cart_query = "SELECT * FROM order_bag WHERE user_id = $user_id";
      $cart_result = mysqli_query($conn, $cart_query);

      if ($cart_result && mysqli_num_rows($cart_result) > 0) {
          while ($item = mysqli_fetch_assoc($cart_result)) {
              $cart_items[] = $item;
          }
      }

      // Clear cart if requested
  if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["clear_cart"])) {
    $clear_cart_query = "DELETE FROM order_bag WHERE user_id = $user_id";
    $clear_cart_result = mysqli_query($conn, $clear_cart_query);

    if ($clear_cart_result) {
        echo "Cart cleared successfully!";
    } else {
        echo "Error clearing cart.";
    }

    exit(); // Stop further processing
}
  }

  if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SESSION["user_id"])) {
    $user_id = $_SESSION["user_id"];
    $order_names = $_POST["order_names"];
    $order_date = $_POST["order_date"];
    $total_price = floatval($_POST["total_price"]);

    $order_name = implode(', ', $order_names);

    // Insert data into the order_history table
    $insert_query = "INSERT INTO order_history (user_id, order_name, order_date, total_price) VALUES ('$user_id', '$order_name', '$order_date', '$total_price')";
    $insert_result = mysqli_query($conn, $insert_query);

    if ($insert_result) {
        // Order history updated successfully
        echo "Order history updated successfully!";
    } else {
        // Failed to update order history
        echo "Error updating order history.";
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
        </nav>

    <div class="content">
      <div class="container">
        <!-- Orders detail review before checkout -->
        <div class="heading">
          <h2 class="header">Your Order</h2>
        </div>
        <?php
        if (isset($_SESSION["user_id"])) {
            // Display cart items for logged in users
            echo '<div class="cart-items">';
            if (count($cart_items) > 0) {
                foreach ($cart_items as $item) {
                    echo '<div class="cart-item">';
                    echo '<div class="item-image">';
                    echo '<img src="' . $item['image_url'] . '" alt="' . $item['name'] . '" />';
                    echo '</div>';
                    echo '<div class="item-quantity">';
                    echo '<button class="minus-btn" data-item-id="' . $item['item_id'] . '">-</button>';
                    echo '<input type="number" value="' . $item['quantity'] . '" min="1" data-item-id="' . $item['item_id'] . '" />';
                    echo '<button class="plus-btn" data-item-id="' . $item['item_id'] . '">+</button>';
                    echo '</div>';
                    echo '<div class="item-details">';
                    echo '<h3 class="header">' . $item['name'] . '</h3>';
                    echo '</div>';
                    echo '<div class="item-remove">';
                    echo '<p class="price">£' . number_format($item['order_price'], 2) . '</p>';
                    echo '<button class="btn-remove" data-item-id="' . $item['item_id'] . '">Remove</button>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<h3 class="header">Your cart is empty.</h3>';
            }
            echo '</div>';

            // Calculate and display total price
            $total = 0;
            foreach ($cart_items as $item) {
                $total += $item['order_price'];
            }
            if (count($cart_items) > 0) {
              echo '<div class="cart-total" id="cart-total">';
              echo '<h4>Total: £' . number_format($total, 2) . '</h4>';
              echo '</div>';
            }
        } else {
            // Display message for non-logged in users
            echo '<p>Login to view order items.</p>';
        }
        ?>

        <!-- Checkout Panel with payment method and detail -->
        <?php if (count($cart_items) > 0) { ?>
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
                    Pay £<span id="total-price-placeholder-card">TOTAL_PRICE</span> with Credit Card
                  </div>
                </label>
                <label for="paypal" class="method paypal">
                  <div class="card-logos">
                    <img src="images/paypal.png" alt="PayPal Logo" />
                  </div>  
                  <div class="radio-input">
                    <input id="paypal" type="radio" name="payment" />
                    Pay £<span id="total-price-placeholder-paypal">TOTAL_PRICE</span> with Paypal
                  </div>
                </label>
                <label for="apple-pay" class="method apple-pay">
                  <div class="card-logos">
                    <div class="image-container">
                      <img src="images/apple-pay.png" alt="Apple Pay Logo" />  
                    </div>
                  </div>  
                  <div class="radio-input">
                    <input id="apple-pay" type="radio" name="payment" />
                    Pay £<span id="total-price-placeholder-apple-pay">TOTAL_PRICE</span> with Apple Pay
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
            <button class="btn btn-primary btn-checkout">Checkout</button>
          </div>  
        <?php } ?>      
      </div>
    </div>

    <!-- Footer Section -->
    <?php include 'footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>
    <script src="js/script.js"></script>
    <script>
      $(document).ready(function() {


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

        var currentQuantity;

        $(".minus-btn").click(function() {
          var itemContainer = $(this).closest(".cart-item");
          var itemId = itemContainer.find(".btn-remove").data("item-id");
          currentQuantity = parseInt(itemContainer.find("input[type='number']").val());
          if (currentQuantity > 1) {
            var newQuantity = currentQuantity - 1;
            updateCartItemQuantity(itemId, newQuantity, currentQuantity);
          }
          // updateCartTotal();
        });

        $(".plus-btn").click(function() {
          var itemContainer = $(this).closest(".cart-item");
          var itemId = itemContainer.find(".btn-remove").data("item-id");
          currentQuantity = parseInt(itemContainer.find("input[type='number']").val());
          var newQuantity = currentQuantity + 1;
          updateCartItemQuantity(itemId, newQuantity, currentQuantity);
          // updateCartTotal();
        });

        $(".btn-remove").click(function() {
          var itemId = $(this).data("item-id");
          removeCartItem(itemId);
        });

          function removeCartItem(itemId) {
              $.ajax({
                  url: "remove-cart-item.php",
                  type: "POST",
                  data: { item_id: itemId },
                  success: function(response) {
                      // Item removed successfully, update the UI or reload the page
                      location.reload(); // Reload the page to reflect the changes
                  },
                  error: function(xhr, status, error) {
                      // Handle error
                      console.error("Error removing cart item.");
                  }
              });
          }

          $(".btn-checkout").click(function() {
            var totalPrice = <?php echo $total; ?>;
            var orderNames = [];

            $(".cart-item .item-details h3").each(function() {
                orderNames.push($(this).text());
            });

            var orderDate = "<?php echo date('Y-m-d'); ?>";

            $.ajax({
                type: "POST",
                data: {
                    total_price: totalPrice,
                    order_names: orderNames,
                    order_date: orderDate
                },
                success: function(response) {
                    // Order history updated successfully
                    alert("Order history updated successfully!");
                    clearCart(); // Call the function to clear the user's cart

                    // Clear cart UI and update cart total
                    $(".cart-items").empty();
                    $("#cart-total").remove();
                    console.log("Success:", response);
                },
                error: function(xhr, status, error) {
                    console.error("Error updating order history.");
                    // Show an error message to the user
                }
            });
        });

        function clearCart() {
          $.ajax({
              url: "orders.php", // Send the AJAX request to the same page
              type: "POST",
              data: { clear_cart: true }, // Add a parameter to indicate cart clearing
              success: function(response) {
                  // Cart cleared successfully, update the UI
                  console.log("Cart cleared successfully.");
                  $(".checkout-panel").hide(); // Hide the checkout panel
                  $(".cart-items").empty(); // Empty the cart items
                  $("#cart-total").remove(); // Remove the cart total
                  $(".content").append('<h3 class="header">Your cart is empty.</h3>'); // Display empty cart message
              },
              error: function(xhr, status, error) {
                  // Handle error
                  console.error("Error clearing cart.");
              }
          });
      }

          // Replace this with the actual total price from your PHP code
          var totalPrice = <?php echo $total; ?>;

          // Update the total price placeholder
          var cardTotalPricePlaceholder = $("#total-price-placeholder-card");
          cardTotalPricePlaceholder.text(totalPrice.toFixed(2));

          var paypalTotalPricePlaceholder = $("#total-price-placeholder-paypal");
          paypalTotalPricePlaceholder.text(totalPrice.toFixed(2));

          var applePayTotalPricePlaceholder = $("#total-price-placeholder-apple-pay");
          applePayTotalPricePlaceholder.text(totalPrice.toFixed(2));
      });
      function updateCartTotal() {
        var updatedTotal = 0;
        $(".cart-item").each(function() {
            var itemPrice = parseFloat($(this).find(".price").text().substring(1));
            updatedTotal += itemPrice;
        });
        $("#cart-total h4").text("Total: £" + updatedTotal.toFixed(2));
    }

      function updateCartItemQuantity(itemId, newQuantity, currentQuantity) {
        $.ajax({
          url: "update-cart-quantity.php",
          type: "POST",
          data: { item_id: itemId, new_quantity: newQuantity },
          success: function(response) {
            // Quantity and price updated successfully, update the UI
            var itemContainer = $(".btn-remove[data-item-id='" + itemId + "']").closest(".cart-item");
            itemContainer.find("input[type='number']").val(newQuantity);
            var itemPrice = parseFloat(itemContainer.find(".price").text().substring(1));
            var newPrice = (itemPrice / currentQuantity) * newQuantity;
            itemContainer.find(".price").text("£" + newPrice.toFixed(2));
            updateCartTotal();
            console.log("Success:", response);
          },
          error: function(xhr, status, error) {
            // Handle error
            console.error("Error:", xhr.responseText);
            console.error("Error updating cart item quantity.");
          }
        });
      }
    </script>
  </body>
</html>
