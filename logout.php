<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    // Start or resume the current session
    session_start();

    // Destroy the session data
    session_destroy();

    // Redirect the user to the login page
    header("Location: index.html");
    exit; // Ensure that no further code is executed after redirection
?>
