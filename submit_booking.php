<?php
require_once 'database_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $annual_income = $conn->real_escape_string($_POST['annual_income']);
    $cruise_type = $conn->real_escape_string($_POST['cruise_type']);
    $preferred_date = $conn->real_escape_string($_POST['preferred_date']);
    $guests = (int)$_POST['guests'];
    $message = $conn->real_escape_string($_POST['message']);
    
    $sql = "INSERT INTO bookings (name, email, annual_income, cruise_type, preferred_date, guests, message, status) 
            VALUES ('$name', '$email', '$annual_income', '$cruise_type', '$preferred_date', $guests, '$message', 'pending')";
    
    if ($conn->query($sql)) {
        // Optional: Send notification email to admin
        $admin_email = "tsopy@otenet.gr";
        $subject = "New Booking Request - THEMIS IV";
        $body = "New booking from: $name\nEmail: $email\nDate: $preferred_date\nCruise: $cruise_type\nGuests: $guests\n\nMessage: $message";
        mail($admin_email, $subject, $body, "From: bookings@themisiv.com");
        
        header("Location: thank_you.html");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    header("Location: index.html");
    exit();
}
?>