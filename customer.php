<?php
  include 'config.php';

  session_start();

  $phone = $_SESSION['phone'];

  $key = "1234"; // Key for encryption
  $iv = "1234123412341234"; 

  // Function to decrypt data using AES decryption
  function decrypt($data, $key, $iv) {
      $cipher = "aes-128-cbc";
      $options = OPENSSL_RAW_DATA;
      $decryptedData = openssl_decrypt(base64_decode($data), $cipher, $key, $options, $iv);
      return $decryptedData;
  }

  // Retrieve all card numbers related to the logged-in user
  $cardQuery = "SELECT * FROM cards WHERE phone = '$phone'";
  $cardResult = $conn->query($cardQuery);

  if (!$cardResult) {
    die('Error executing query: ' . $conn->error);
  }

  $encryptedCardNumbers = array();
  if ($cardResult->num_rows > 0) {
      while ($row = $cardResult->fetch_assoc()) {
          // Store encrypted card number
          $encryptedCardNumbers[] = $row['cardNumber'];
      }
  }

  // Construct the IN clause with encrypted card numbers
  $transactionIDs = "'" . implode("','", $encryptedCardNumbers) . "'";

  // Query transactions related to the encrypted card numbers
  $sql = "SELECT * FROM transactions WHERE cardNumber IN ($transactionIDs)";
  $result = $conn->query($sql);

  if (!$result) {
    die('Error executing query: ' . $conn->error);
  }
  /*echo "<script>
          alert('Decrypted Card Numbers: $cardNumbersString');
          window.location.href = 'customer.php';
      </script>";
  exit();*/
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Card Vault Home</title>
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
          <li><a href="customer-cards.php" class="getstarted scrollto"  style="cursor: pointer;">Cards</a></li>
          <li><a class="getstarted scrollto" data-bs-toggle="modal" data-bs-target="#viewTransaction" style="cursor: pointer;">Transactions</a></li>
          <li><a class="getstarted scrollto" href="logout.php">Logout</a></li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->

    </div>
  </header><!-- End Header -->

  <!-- ======= Hero Section ======= -->
  <section id="hero" class="hero d-flex align-items-center">

    <!-- Grids in modals -->
   
  <div class="modal fade" id="cardSettings" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalgridLabel">Card Settings</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <div class="row">
                      <div class="col-xxl-9 bg-light">                                              
                              
                                  <div class="tab-content">
                                      
                                      <!--end tab-pane-->
                                      <div class="tab-pane active" id="personalDetails" role="tabpanel">                                            
                                          <div class="col-xxl-4">
                                              <div class="card card-height-100 ">
                                                  <div class="card-header">
                                                      <h5 class="card-title mb-0">Credit Card</h5>
                                                  </div>
                                                  <div class="card-body">
                                                      <div class="mx-auto" style="max-width: 400px">
                                                          <div class="text-bg-info bg-gradient p-4 rounded-3 mb-3">
                                                              <div class="d-flex">
                                                                  <div class="flex-grow-1">
                                                                      <i class="bx bx-chip h1 text-warning"></i>
                                                                  </div>
                                                                  <div class="flex-shrink-0">
                                                                      <i class="bx bxl-visa display-2 mt-n3"></i>
                                                                  </div>
                                                              </div>
                                                              <div class="card-number fs-20" id="card-num-elem">
                                                                  XXXX XXXX XXXX XXXX
                                                              </div>
                                      
                                                              <div class="row mt-4">
                                                                  <div class="col-4">
                                                                      <div>
                                                                          <div class="text-white-50">Card Holder</div>
                                                                          <div id="card-holder-elem" class="fw-medium fs-14">Full Name</div>
                                                                      </div>
                                                                  </div>
                                                                  <div class="col-4">
                                                                      <div class="expiry">
                                                                          <div class="text-white-50">Expires</div>
                                                                          <div class="fw-medium fs-14">
                                                                              <span id="expiry-month-elem">00</span>
                                                                              /
                                                                              <span id="expiry-year-elem">0000</span>
                                                                          </div>
                                                                      </div>
                                                                  </div>
                                                                  <div class="col-4">
                                                                      <div>
                                                                          <div class="text-white-50">CVC</div>
                                                                          <div id="cvc-elem" class="fw-medium fs-14">---</div>
                                                                      </div>
                                                                  </div>
                                                              </div>
                                                          </div>
                                                          <!-- end card div elem -->
                                                      </div>
                                      
                                      
                                                      <form id="custom-card-form" autocomplete="off">
                                                          <div class="mb-3">
                                                              <label for="card-num-input" class="form-label">Card Number</label>
                                                              <input id="card-num-input" class="form-control" maxlength="19" placeholder="0000 0000 0000 0000" />
                                                          </div>
                                      
                                                          <div class="mb-3">
                                                              <label for="card-holder-input" class="form-label">Card Holder</label>
                                                              <input type="text" class="form-control" id="card-holder-input" placeholder="Enter holder name" />
                                                          </div>
                                      
                                                          <div class="row">
                                                              <div class="col-lg-4">
                                                                  <div>
                                                                      <label for="expiry-month-input" class="form-label">Expiry Month</label>
                                                                      <select class="form-select" id="expiry-month-input">
                                                                          <option></option>
                                                                          <option value="01">01</option>
                                                                          <option value="02">02</option>
                                                                          <option value="03">03</option>
                                                                          <option value="04">04</option>
                                                                          <option value="05">05</option>
                                                                          <option value="06">06</option>
                                                                          <option value="07">07</option>
                                                                          <option value="08">08</option>
                                                                          <option value="09">09</option>
                                                                          <option value="10">10</option>
                                                                          <option value="11">11</option>
                                                                          <option value="12">12</option>
                                                                      </select>
                                                                  </div>
                                                              </div>
                                                              <!-- end col -->
                                      
                                                              <div class="col-lg-4">
                                                                  <div>
                                                                      <label for="expiry-year-input" class="form-label">Expiry Year</label>
                                                                      <select class="form-select" id="expiry-year-input">
                                                                          <option></option>
                                                                          <option value="2020">2020</option>
                                                                          <option value="2021">2021</option>
                                                                          <option value="2022">2022</option>
                                                                          <option value="2023">2023</option>
                                                                          <option value="2024">2024</option>
                                                                          <option value="2025">2025</option>
                                                                          <option value="2026">2026</option>
                                                                          <option value="2027">2027</option>
                                                                          <option value="2028">2028</option>
                                                                          <option value="2029">2029</option>
                                                                          <option value="2030">2030</option>
                                                                      </select>
                                                                  </div>
                                                              </div>
                                                              <!-- end col -->
                                      
                                                              <div class="col-lg-4">
                                                                  <div class="cvc">
                                                                      <label for="cvc-input" class="form-label">CVC</label>
                                                                      <input type="text" id="cvc-input" class="form-control" maxlength="3" />
                                                                  </div>
                                                              </div>
                                                              <!-- end col -->
                                                          </div>
                                                          <!-- end row -->
                                      
                                                          <button class="btn btn-danger w-30 mt-3" type="submit">Update</button>
                                                      </form>
                                                      <!-- end card form elem -->
                                                  </div>
                                                  <div class="modal-footer">
                                                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" data-bs-target="#newCard">Add New Card</button>
                                                    
                                                  </div>
                                              </div>
                                              <!-- end card -->
                                          </div>
                                          <!-- end col -->
                                      </div>
                                      <!--end tab-pane-->                                        
                                  </div>
                      </div>
                      <!--end col-->
                  </div>
              </div>
          </div>
      </div>
  </div>

  <!-- View Transaction Modal -->
<div class="modal fade modal-xl" id="viewTransaction" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Transactions</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="card-body">
          <div class="table-responsive">
              <table class="table table-success table-striped">
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
                    <tbody>
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
                          echo "<tr><td colspan='7'>No data available</td></tr>";
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

    <div class="container">
      <div class="row">
        <div class="col-lg-6 d-flex flex-column justify-content-center">
          <h1 data-aos="fade-up">Welcome to SecureVault:</h1>
          <h2 data-aos="fade-up" data-aos-delay="400">Your Trusted Partner in Safekeeping Customer Data</h2>
          <div data-aos="fade-up" data-aos-delay="600">
            
          </div>
        </div>
        <div class="col-lg-6 hero-img" data-aos="zoom-out" data-aos-delay="200">
          <img src="assets/img/customer2-img.png" class="img-fluid" alt="">
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