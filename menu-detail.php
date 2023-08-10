<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tom On Wheels - Menu</title>

    <!-- PHP Code to Fetch Item Details -->
    <?php
      include 'config.php'; 

      $conn_serialized = $_GET['connection'];
      $conn = unserialize(urldecode($conn_serialized));

      error_reporting(E_ALL);
      ini_set('display_errors', 1);
      
      $item_id = $_GET['item_id'];

      // Fetch the item details from the database based on the item_id
      $query = "SELECT * FROM menu_items WHERE item_id = $item_id";
      $result = mysqli_query($connection, $query);
      $item = mysqli_fetch_assoc($result);
    ?>

    <!-- Css, bootstrap and fonts imports -->
    <link rel="stylesheet" type="text/css" href="css/style.css" /> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" />
    <!-- CSS unique to this page that cannot be mixed up -->
    <style>
      @media screen and (min-width: 992px){
        .product-detail .card{
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-gap: 1.5rem;
        }
        .product-imgs{
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .product-content{
            padding-top: 0;
        }
      }
    </style>
  </head>
  <body>
    <!-- Navbar Container -->
    <?php include 'navbar.php'; ?>

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
                      <div class="item-quantity">
                          <button class="minus-btn">-</button>
                          <input type="number" value="1" min="1" />
                          <button class="plus-btn">+</button>
                      </div>
                      <button type="button" class="btn btn-primary">
                          Add to Bag <i class="fas fa-shopping-cart"></i>
                      </button>
                  </div>
              </div>
          </div>
      </div>
        
      <div class="container">
        <div class="row">
          <div class="col-lg-12 col-12">
            <!-- Some more popular items -->
            <h2 class="header">Popular Items</h2>
            <div class="row">
              <div class="col-md-3 col-12">
                <div class="card m-3 border">
                  <div class="card-body">
                    <div class="cardimage" >
                      <a href="menu-detail.html" style="object-fit: cover;">
                        <img src="images/italian-spaghetti.jpg" class="" alt="...">
                      </a>
                      <h5 class="card-title header">Italian Spaghetti</h5>
                      <h6 class="card-title header">£5.50</h6>
                      <button class="btn btn-primary add-to-bag">Add to Bag <i class="fas fa-shopping-cart"></i></button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-3 col-12">
                <div class="card m-3 border">  
                  <a href="veg-pizza.html">
                    <img src="images/veg-pizza.jpg" class="card-img-top" alt="...">
                  </a>      
                  <div class="card-body">
                    <h5 class="card-title header">Veg Pizza</h5>
                    <h6 class="card-title header">£5.49</h6>
                    <button class="btn btn-primary add-to-bag">Add to Bag <i class="fas fa-shopping-cart"></i></button>
                  </div>
                </div>
              </div>
              <div class="col-md-3 col-12">
                <div class="card m-3 border">   
                  <a href="menu-detail.html">
                    <img src="images/fish-noodles.jpg" class="card-img-top" alt="...">
                  </a> 
                  <div class="card-body">
                    <h5 class="card-title header">Fish Noodles</h5>
                    <h6 class="card-title header">£5.99</h6>
                    <button class="btn btn-primary add-to-bag">Add to Bag <i class="fas fa-shopping-cart"></i></button>
                  </div>
                </div>
              </div>
              <div class="col-md-3 col-12">
                <div class="card m-3 border">
                  <a href="menu-detail.html">
                    <img src="images/salmon-fish.jpg" class="card-img-top" alt="...">
                  </a>
                  <div class="card-body">
                    <h5 class="card-title header">Salmon Fish</h5>
                    <h6 class="card-title header">£6.49</h6>
                    <button class="btn btn-primary add-to-bag">Add to Bag <i class="fas fa-shopping-cart"></i></button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
            
    <!-- Footer container -->
    <?php include 'footer.php'; ?>

    <script src="js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>
</body>
</html>
