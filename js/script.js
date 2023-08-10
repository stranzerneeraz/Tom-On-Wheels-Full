// Load Menu page when clicking from speciality section in homepage
function menuPage() {
    window.location.href = "menu.html";
}
  
// Display signup container when user needs to register account hiding login container
function showSignup() {
    var signupContainer = document.getElementById("signupContainer");
    signupContainer.style.display = "flex";
}
  
// Hide signup container when user needs to login to the account
function hideSignup() {
    var signupContainer = document.getElementById("signupContainer");
    signupContainer.style.display = "none";
}

function confirmLogout() {
    var result = confirm("Are you sure you want to log out?");
    if (result) {
      window.location.href = "logout.php";
    }
  }
  
// Display map of the location of the application with the help of Map Box map
function loadMap() {
    mapboxgl.accessToken = 'pk.eyJ1Ijoic3RyYW56ZXJuZWVyYXoiLCJhIjoiY2xqcjJhdjhsMDNrYjNjdnN4a3IyM3l1biJ9.DVRVAz534tJ0fGyhteiEEQ';
    var map = new mapboxgl.Map({
      container: 'map',
      style: 'mapbox://styles/mapbox/streets-v11',
      center: [-1.9975, 52.5304], // [longitude, latitude]
      zoom: 14,
      scrollZoom: false 
    });
  
    // map additional controls
    map.addControl(new mapboxgl.NavigationControl());
  
    // Mark location on the map
    var markerElement = document.createElement('div');
    markerElement.className = 'marker';
    var marker = new mapboxgl.Marker(markerElement)
      .setLngLat([-1.9975, 52.5304]) // [longitude, latitude]
      .addTo(map);
  
    var popup = new mapboxgl.Popup({ closeButton: false })
      .setHTML('<h3>Food Delivery App Office</h3><p>B71 2RJ, West Bromwich, United Kingdom</p>');
  
    marker.setPopup(popup);
  
    // Some more controls
    var markerStyle = document.createElement('style');
    markerStyle.textContent = '.marker { background-color: #f15a24; width: 24px; height: 24px; border-radius: 50%; cursor: pointer; }';
    document.head.appendChild(markerStyle);
}
  
// Validate login details
function validateLogin() {
    var username = document.getElementById('username').value;
    var password = document.getElementById('password').value;
  
    if (username === '') {
        alert('Please enter your email or mobile number.');
        return false;
    }
  
    if (password === '') {
        alert('Please enter your password.');
        return false; 
    }
  
    return true;
}
  
// Signup for validation 
function validateSignupForm() {
    var name = document.getElementById('signup-name').value;
    var email = document.getElementById('signup-email').value;
    var mobile = document.getElementById('signup-mobile').value;
    var username = document.getElementById('signup-username').value;
    var password = document.getElementById('signup-password').value;
    var confirmPassword = document.getElementById('signup-confirm-password').value;
    var address = document.getElementById('signup-address').value;
    var postcode = document.getElementById('signup-postcode').value;
  
    // Name fieldvalidation whether it is empty or not
    if (name === '') {
        alert('Please enter your name.');
        return false;
    }
  
    //Email Validation: whether the email is in appropriate format
    if (!isValidEmail(email)) {
        alert('Please enter a valid email address.');
        return false;
    }
  
    // Mobile number validation: check if it has exactly 10 digits
    if (!isValidMobile(mobile)) {
        alert('Please enter a valid mobile number.');
        return false;
    }
  
    // Username validation: check if username is empty or not
    if (username === '') {
        alert('Please enter a username.');
        return false;
    }
  
    // Password validation: check if password is at least 8 characters long
    if (password.length < 8) {
        alert('Password should be at least 8 characters long.');
        return false;
    }
  
    // Confirm password validation: check if it matches the password
    if (confirmPassword !== password) {
        alert('Passwords do not match.');
        return false;
    }
  
    // Address validation: check if address is empty or not
    if (address === '') {
        alert('Please enter your address.');
        return false;
    }
  
    // Postcode validation: check if postcode is not empty
    if (postcode === '') {
        alert('Please enter your postcode.');
        return false;
    }
    
    return true;
  }
  
//  email format validation
function isValidEmail(email) {
    return /\S+@\S+\.\S+/.test(email);
}
  
// Simple mobile format validation (10 digits)
function isValidMobile(mobile) {
    return /^\d{10}$/.test(mobile);
}
