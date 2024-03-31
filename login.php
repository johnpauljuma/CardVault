<?php
    // Enable error reporting
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Start session
    session_start();

    // Include database configuration file
    include 'config.php';

    // Check if the form is submitted
    if (isset($_POST['login'])) {

        // Get data from the form and sanitize inputs
        $role = mysqli_real_escape_string($conn, $_POST['role']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $username = mysqli_real_escape_string($conn, $_POST['userInput']);
        $errorMessage = "";

        // Check the role and perform respective login validation
        
            
        if($role == 'administrator'){

            $checkQuery = "SELECT * FROM admin WHERE username = '$username'";
            $result = mysqli_query($conn, $checkQuery);
            $row = mysqli_fetch_assoc($result);
            if ($row && hash('sha256', $password) === $row['password']) {
                // Admin login successful, redirect user
                $_SESSION['username'] = $username;
                header("Location: administrator.php");
                exit();
            } else {
                $errorMessage = "Invalid username or password!";
            }
        }
        elseif($role == 'taller'){
            $checkQuery = "SELECT * FROM tallers WHERE tallerID = '$username'";
            $result = mysqli_query($conn, $checkQuery);
            $row = mysqli_fetch_assoc($result);
            if (hash_equals($row['password'], hash('sha256', $password))) {
                // Taller login successful, redirect user
                $_SESSION['tallerID'] = $username;
                header("Location: taller.php");
                exit();
            } else {
                $errorMessage = "Invalid taller ID or password!";
            }
        }
        elseif($role == 'customer'){
            $checkQuery = "SELECT * FROM customers WHERE email = '$username'";
            $result = mysqli_query($conn, $checkQuery);
            $row = mysqli_fetch_assoc($result);
            if ($row && hash('sha256', $password) === $row['password']) {
                // Customer login successful, redirect user
                $_SESSION['email'] = $email;
                header("Location: customer.php");
                exit();
            } else {
                $errorMessage = "Invalid email or password!";
            }
        }
        else{
            $errorMessage = "Invalid role!";
        }               
        // If login fails, display error message
        echo "<script>
                alert('$errorMessage');
                window.location.href = 'index.html';
            </script>";
        exit();
    }
?>
