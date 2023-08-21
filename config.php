<?php
    // Database configuration
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $database = "tom_on_wheels";

    // Create connection to database
    $conn = new mysqli($hostname, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>