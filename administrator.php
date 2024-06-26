<?php
  include 'config.php';

  $key = "1234"; // Key for encryption
  $iv = "1234123412341234"; 

  // Function to decrypt data using AES decryption
  function decrypt($data, $key, $iv) {
    $cipher = "aes-128-cbc";
    $options = OPENSSL_RAW_DATA;
    $decryptedData = openssl_decrypt(base64_decode($data), $cipher, $key, $options, $iv);
    return $decryptedData;
  }
  
  $sql = "SELECT * FROM transactions";
  $result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Card Vault Admin</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">



  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">

      <a href="index.html" class="logo d-flex align-items-center">
        <img src="assets/img/logo.png" alt="">
        <span>Card Vault</span>
      </a>

      <nav id="navbar" class="navbar">
        <ul>
          <li><a class="getstarted scrollto" href="manage-cards.php">Manage Credit Cards</a></li>
          <li><button type="button" class="btn btn-primary getstarted scrollto" data-bs-toggle="modal" data-bs-target="#viewTransaction">
            <b>View Transaction</b>
          </button></li>
          <li><button type="button" class="btn btn-primary getstarted scrollto" data-bs-toggle="modal" data-bs-target="#addCustomer">
            <b>Add User</b>
          </button></li>
          <li><button type="button" class="btn btn-primary getstarted scrollto" data-bs-toggle="modal" data-bs-target="#addTaller">
            <b>Add Taller</b>
          </button></li>
          <li><a class="getstarted scrollto" href="logout.php">Logout</a></li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->

    </div>
  </header><!-- End Header -->

  <!-- ======= Hero Section ======= -->
  <section id="hero" class="hero d-flex align-items-center">

   
<!-- View Transaction Modal -->
<div class="modal fade modal-xl" id="viewTransaction" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="card-body">
          <div class="table-responsive">
              <table class="table table-success table-striped" id="cardTable">
                  <thead>
                      <tr>
                        <th scope="col">Transaction ID</th>
                        <th scope="col">Customer Name</th>
                        <th scope="col">Card Number</th>
                        <th scope="col">CVV</th>
                        <th scope="col">Description</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Date</th>            
                      </tr>
                    </thead>
                    <tbody id="cardTableBody">
                    <?php
                      if ($result->num_rows > 0) {
                          while ($row = $result->fetch_assoc()) {
                              // Decrypt card number and CVV
                              $decryptedCardNumber = decrypt($row['cardNumber'], $key, $iv);
                              $decryptedCvv = decrypt($row['cvv'], $key, $iv);

                              echo "<tr>";                                            
                              echo '<td>' . htmlspecialchars($row['transactionID']) . '</td>';
                              echo '<td>' . htmlspecialchars($row['customerName']) . '</td>';
                              echo '<td>' . htmlspecialchars($decryptedCardNumber) . '</td>'; 
                              echo '<td>' . htmlspecialchars($decryptedCvv) . '</td>'; 
                              echo '<td>' . htmlspecialchars($row['description']) . '</td>';
                              echo '<td>' . htmlspecialchars($row['amount']) . '</td>';                                             
                              echo '<td>' . htmlspecialchars($row['date']) . '</td>';                                            
                              echo "</tr>";
                          }
                      } else {
                          echo "<tr><td colspan='8'>No data available</td></tr>";
                      }
                   
                      ?>
                    </tbody>
                    
              </table>
          </div>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        
      </div>
    </div>
  </div>
</div>

<!-- ======= Add Customer Modal ======= -->
<div id="addCustomer" class="modal" tabindex="-1" aria-hidden="true" style="display: none;">
  <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 overflow-hidden">
          <div class="modal-header p-3">
              <h4 class="card-title mb-0">Sign Up</h4>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          
          <div class="modal-body">
              <form action="admin-add-user.php" method="POST">
                  <div class="mb-3">
                      <label for="fullName" class="form-label">Full Name</label>
                      <input type="text" class="form-control" name="fullName" placeholder="Enter your name">
                  </div>
                  <div class="mb-3">
                      <label for="emailInput" class="form-label">Email address</label>
                      <input type="email" class="form-control" name="email" placeholder="Enter your email">
                  </div>
                  <div class="mb-3">
                    <label for="emailInput" class="form-label">Phone Number</label>
                    <input type="number" class="form-control" name="phone" placeholder="Enter your email">
                </div>
                  
                  <div class="mb-3">
                      <label for="exampleInputPassword1" class="form-label">Password</label>
                      <input type="password" class="form-control" name="password" placeholder="Enter your password">
                  </div>
                  <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" name="confirmPassword" placeholder="Enter your password">
                </div>
                  <div class="mb-3 form-check">
                      <input type="checkbox" class="form-check-input" id="checkTerms">
                      <label class="form-check-label" for="checkTerms">I agree to the <span class="fw-semibold">Terms of Service</span> and Privacy Policy</label>
                  </div>
                  <div class="text-end">
                      <button type="submit" class="btn btn-primary" name="signup">Sign Up Now</button>
                  </div>
              </form>
          </div>
      </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Add Taller Modal -->
<div class="modal fade" id="addTaller" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add New Taller</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="add-taller.php" method="POST">
          <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Taller ID</label>
            <input type="number" class="form-control" name="tallerID" aria-describedby="emailHelp">
           
          </div>
          <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Password</label>
            <input type="password" class="form-control" name="password">
          </div>
          <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" name="confirmPassword">
          </div>
         
          <button type="submit" class="btn btn-primary" name="addTaller">Submit</button>
        </form>
      </div>
      
    </div>
  </div>
</div>
    <div class="container">
      <div class="row">
        <div class="col-lg-6 d-flex flex-column justify-content-center">
          <h1 data-aos="fade-up">Welcome to SecureVault Management:</h1>
          <h2 data-aos="fade-up" data-aos-delay="400">Your Trusted Partner in Safekeeping Customer Data</h2>
          <div data-aos="fade-up" data-aos-delay="600">
            
          </div>
        </div>
        <div class="col-lg-6 hero-img" data-aos="zoom-out" data-aos-delay="200">
          <img src="assets/img/admin-img.png" class="img-fluid" alt="">
        </div>
      </div>
    </div>

  </section><!-- End Hero -->

 
  <!-- Vendor JS Files -->
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
</body>
</html>