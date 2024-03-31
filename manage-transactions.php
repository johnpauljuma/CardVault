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
          <li><a class="getstarted scrollto" href="logout.php">Logout</a></li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->

    </div>
  </header><!-- End Header -->

  <!-- ======= Hero Section ======= -->
  <section id="hero" class="hero d-flex align-items-center">

    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Transactions</h4>
                        <!-- Grids in modals -->
                        <div class="d-flex mb-2 ms-auto">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalgrid">
                                Add Transaction
                            </button>
                        </div>
                        <div class="modal fade" id="exampleModalgrid" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalgridLabel">Add New Transaction</h5>
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
                                                                            <h5 class="card-title mb-0">Transaction Details</h5>
                                                                        </div>
                                                                        <div class="card-body">
                                                                             
                                                                            <form id="custom-card-form" autocomplete="off" action="add-transaction.php" method="POST">
                                                                                <div class="mb-3">
                                                                                    <label for="card-num-input" class="form-label">Transaction ID</label>
                                                                                    <input name="transactionID" class="form-control" maxlength="19" placeholder="0000" />
                                                                                </div>
                                                            
                                                                                <div class="mb-3">
                                                                                    <label for="card-holder-input" class="form-label">Customer Name</label>
                                                                                    <input type="text" class="form-control" name="customerName" placeholder="Enter customer name" />
                                                                                </div>
                                                                                <div class="mb-3">
                                                                                    <label for="card-holder-input" class="form-label">Card Number</label>
                                                                                      <input type="text" class="form-control" id="cardNumber" name="cardNumber" maxlength="19" placeholder="0000 0000 0000 0000" oninput="formatCardNumber(this)">
                                                                                </div>
                                                                                <div class="mb-3">
                                                                                    <label for="card-holder-input" class="form-label">CVV</label>
                                                                                    <input type="number" class="form-control" name="cvv" placeholder="Enter cvv" />
                                                                                </div>
                                                                                <div class="mb-3">
                                                                                    <label for="card-holder-input" class="form-label">Description</label>
                                                                                    <input type="text" class="form-control" name="description" placeholder="Enter description" />
                                                                                </div>
                                                                                <div class="mb-3">
                                                                                    <label for="card-holder-input" class="form-label">Amount</label>
                                                                                    <input type="text" class="form-control" name="amount" placeholder="Enter amount" />
                                                                                </div>
                                                                                <div class="mb-3">
                                                                                    <label for="card-holder-input" class="form-label">Date</label>
                                                                                    <input type="datetime-local" class="form-control" name="date" placeholder="Enter holder name" />
                                                                                </div>
                                                            
                                                                                <button class="btn btn-danger w-100 mt-3" type="submit" name="addTransaction">Add</button>
                                                                            </form>
                                                                            <!-- end card form elem -->
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
                        
                    </div><!-- end card header --> 

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-success table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">Transaction ID</th>
                                        <th scope="col">Customer Name</th>
                                        <th scope="col">Card Number</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Date</th>  
                                        <th scope="col">Action</th>          
                                      </tr>
                                  </thead>
                                  <tbody>
                                    
                                  </tbody>
                            </table>
                        </div>
                    </div>
                </div> <!-- end card-->
            </div>
    </div>
    </div>

  </section><!-- End Hero -->

  <script>
    function formatCardNumber(input) {
      // Remove any non-numeric characters
      let value = input.value.replace(/\D/g, '');
      
      // Add a white space every 4 digits
      value = value.replace(/(.{4})/g, '$1 ');
      
      // Update the input value
      input.value = value.trim();
    }
    </script>
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