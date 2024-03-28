<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database configuration file
include 'config.php';

// Generate a random encryption key
function generateEncryptionKey($length = 32) {
    return bin2hex(random_bytes($length / 2)); // Generates a random binary string and converts it to hexadecimal
}

// Generate a random IV
function generateIV($length = 16) {
    return bin2hex(random_bytes($length / 2)); // Generates a random binary string and converts it to hexadecimal
}

// Check if the form is submitted
if(isset($_POST['addTransaction'])){
    // Get data from the form and sanitize inputs
    $transactionID = mysqli_real_escape_string($conn, $_POST['transactionID']);
    $customerName = mysqli_real_escape_string($conn, $_POST['customerName']);
    $cardNumber = mysqli_real_escape_string($conn, $_POST['cardNumber']);
    $cvv = mysqli_real_escape_string($conn, $_POST['cvv']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $amount = mysqli_real_escape_string($conn, $_POST['amount']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);

     // Check if transaction ID already exists
     $checkTransactionQuery = "SELECT * FROM transactions WHERE transactionID = '$transactionID'";
     $transactionResult = mysqli_query($conn, $checkTransactionQuery);
     if(mysqli_num_rows($transactionResult) > 0) {
         // Transaction ID already exists, return an error
         echo "<script>
                 alert('Error! Transaction ID already exists, check the ID and try again.');
                 window.location.href = 'manage-transaction.html';
               </script>";
         exit();
     }
 
     // Check if the card number exists in the cards table
     $checkCardQuery = "SELECT * FROM cards WHERE card_number = '$cardNumber'";
     $cardResult = mysqli_query($conn, $checkCardQuery);
     if(mysqli_num_rows($cardResult) == 0) {
         // Card number does not exist, return an error
         echo "<script>
                 alert('Error! Card Number does not exist!');
                 window.location.href = 'administrator.html';
               </script>";
         exit();
     }
 

    // Generate encryption key and IV
    $encryptionKey = generateEncryptionKey(); // Generate encryption key
    $iv = generateIV(); // Generate IV

    // Encrypt card number and CVV using AES encryption
    $encryptedCardNumber = openssl_encrypt($cardNumber, 'aes-256-cbc', $encryptionKey, 0, $iv);
    $encryptedCVV = openssl_encrypt($cvv, 'aes-256-cbc', $encryptionKey, 0, $iv);

    // Check if the encrypted card number and CVV match in the cards table
    $checkQuery = "SELECT * FROM cards WHERE cardNumber = '$encryptedCardNumber' AND cvv = '$encryptedCVV'";
    $result = mysqli_query($conn, $checkQuery);

    if (!$result) {
        // Query execution failed, display error message and exit
        $errorMessage = mysqli_error($conn);
        echo "<script>
                alert('Database Error: $errorMessage');
                window.location.href = 'index.html';
            </script>";
        exit();
    }

    if (mysqli_num_rows($result) == 0) {
        // Card number and CVV do not match, return an error
        echo "<script>
                alert('Error! Card number and CVV do not match!');
                window.location.href = 'index.html';
            </script>";
        exit();
    }
    // SQL insert query
    $sql = "INSERT INTO transactions (transaction_id, customer_name, card_number, cvv, description, amount, date) 
            VALUES ('$transactionID', '$customerName', '$encryptedCardNumber', '$encryptedCVV', '$description', '$amount', '$date')";

    // Perform the insert operation
    if(mysqli_query($conn, $sql)) {
        // Registration successful, redirect user to success page
        echo "<script>
                alert('Transaction added successfully!');
                window.location.href = 'administrator.html';
              </script>";
        exit();
    } else {
        // Registration failed, display error message
        echo "<script>
                alert('Error: " . mysqli_error($conn) . "');
                window.location.href = 'administrator.html';
              </script>";
        exit();
    }

    // Close the database connection
    mysqli_close($conn);
}
?>
