<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    include 'config.php';

    $key = "1234"; // Key for encryption
        $iv = "1234123412341234"; // Initialization Vector for encryption

        // Function to encrypt data using AES encryption
        function encrypt($data, $key, $iv) {
            $cipher = "aes-128-cbc";
            $options = OPENSSL_RAW_DATA;
            $encryptedData = openssl_encrypt($data, $cipher, $key, $options, $iv);
            return base64_encode($encryptedData);
        }

        // Function to decrypt data using AES decryption
        function decrypt($data, $key, $iv) {
            $cipher = "aes-128-cbc";
            $options = OPENSSL_RAW_DATA;
            $decryptedData = openssl_decrypt(base64_decode($data), $cipher, $key, $options, $iv);
            return $decryptedData;
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

        // Encrypt the card number and CVV using AES encryption        
        $encryptedCardNumber = mysqli_real_escape_string($conn, encrypt($cardNumber, $key, $iv));
        $encryptedCvv = mysqli_real_escape_string($conn, encrypt($cvv, $key, $iv));
        

        // Check if transaction ID already exists
        $checkTransactionQuery = "SELECT * FROM transactions WHERE transactionID = '$transactionID'";
        $transactionResult = mysqli_query($conn, $checkTransactionQuery);
        if (!$transactionResult) {
            // Query execution failed, display error message and exit
            $errorMessage = mysqli_error($conn);
            echo "<script>
                    alert('Database Error: $errorMessage');
                    window.location.href = 'manage-transactions.php';
                </script>";
            exit();
        }

        if(mysqli_num_rows($transactionResult) > 0) {
            // Transaction ID already exists, return an error
            echo "<script>
                    alert('Error! Transaction ID already exists, check the ID and try again.');
                    window.location.href = 'manage-transactions.php';
                </script>";
            exit();
        }
    
        // Check if the card number exists in the cards table
        $checkCardQuery = "SELECT * FROM cards";
        $cardResult = mysqli_query($conn, $checkCardQuery);

        if (!$cardResult) {
            // Query execution failed, display error message and exit
            $errorMessage = mysqli_error($conn);
            echo "<script>
                    alert('Database Error: $errorMessage');
                    window.location.href = 'manage-transactions.php';
                </script>";
            exit();
        }

        $cardExists = false;
        while ($row = mysqli_fetch_assoc($cardResult)) {
            $decryptedCardNumber = decrypt($row['cardNumber'], $key, $iv);
            if ($decryptedCardNumber === $cardNumber) {
                $cardExists = true;
                break;
            }
        }

        if (!$cardExists) {
            // Card number does not exist, return an error
            echo "<script>
                    alert('Error! Card Number does not exist!');
                    window.location.href = 'manage-transactions.php';
                </script>";
            exit();
        }


        // Insert the card details into the cards table
        $sql = "INSERT INTO transactions (transactionID, customerName, cardNumber, cvv, description, amount, date) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);  // Prepare the statement
        if (!$stmt) {
            echo "Error: Unable to prepare statement: " . mysqli_error($conn);
            exit();
        }

        // Bind values to the prepared statement using binary data type for encrypted fields
        mysqli_stmt_bind_param($stmt, "issssis",$transactionID , $customerName, $encryptedCardNumber, $encryptedCvv, $description, $amount, $date);

        // Execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Card insertion successful, redirect user to success page
            echo "<script>
                    alert('Transaction added successfully!');
                    window.location.href = 'manage-transactions.php';
                </script>";
            exit();
        } else {
            // Card insertion failed, display error message
            echo "<script>
                    alert('Error: " . mysqli_error($conn) . "');
                    window.location.href = 'manage-transactions.php';
                </script>";
            exit();
        }
        mysqli_stmt_close($stmt);


                // Close the database connection
        mysqli_close($conn);
    }
?>
