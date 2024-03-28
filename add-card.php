<?php
    // Include the database configuration file
    include 'config.php';

    // Get the form input data and sanitize
    $cardNumber = mysqli_real_escape_string($conn, $_POST['cardNumber']);
    $cardHolder = mysqli_real_escape_string($conn, $_POST['cardHolder']);
    $phoneNumber = mysqli_real_escape_string($conn, $_POST['phoneNumber']);
    $expiryMonth = mysqli_real_escape_string($conn, $_POST['expiryMonth']);
    $expiryYear = mysqli_real_escape_string($conn, $_POST['expiryYear']);
    $cvc = mysqli_real_escape_string($conn, $_POST['cvc']);

    // Check if the phone number exists in the customers table
    $checkCustomerQuery = "SELECT * FROM customers WHERE phone = '$phoneNumber'";
    $result = mysqli_query($conn, $checkCustomerQuery);

    if (mysqli_num_rows($result) == 0) {
        // Phone number does not exist, return an error
        echo "<script>
                alert('User not found!');
                window.location.href = 'index.html';
            </script>";
        exit();
    }

    // Encrypt the card number and CVV using AES encryption (you need to implement this)
    // Example:
    // $encryptedCardNumber = encrypt($cardNumber, $key, $iv);
    // $encryptedCvc = encrypt($cvc, $key, $iv);

    // Insert the card details into the cards table
    $sql = "INSERT INTO cards (encrypted_card_number, card_holder, phone_number, expiry_month, expiry_year, encrypted_cvc) 
            VALUES ('$encryptedCardNumber', '$cardHolder', '$phoneNumber', '$expiryMonth', '$expiryYear', '$encryptedCvc')";

    // Perform the insert operation
    if (mysqli_query($conn, $sql)) {
        // Card insertion successful, redirect user to success page
        echo "<script>
                alert('Card added successfully!');
                window.location.href = 'success.html';
            </script>";
        exit();
    } else {
        // Card insertion failed, display error message
        echo "<script>
                alert('Error: " . mysqli_error($conn) . "');
                window.location.href = 'index.html';
            </script>";
        exit();
    }

    // Close the database connection
    mysqli_close($conn);
?>
