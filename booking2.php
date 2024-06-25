<?php
include_once("controller.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selectedDate = $_POST['date'];
    $timeSlots = getAvailableTimes($selectedDate);

    if ($timeSlots === false) {
        $errorMessage = urlencode("Jadwal Tidak Tersedia");
        header("Location: booking.php?error=$errorMessage");
        exit();
    }

    if (empty($timeSlots)) {
        $errorMessage = urlencode("No available time slots for the selected date.");
        header("Location: booking2.php?error=$errorMessage");
        exit();
    }

    $facilities = isset($_POST['timeSlot']) ? getAvailableFacilities($selectedDate, $_POST['timeSlot']) : [];
    $vouchers = getVouchersFromDatabase(); // Retrieve list of vouchers
    $extras = getExtrasFromDatabase();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>CILMINTON</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <!-- Bootstrap Icons CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

</head>

<body>
    <div class="container-xxl bg-white p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->

        <!-- Header Start -->
        <div class="container-fluid bg-dark px-0">
            <div class="row gx-0">
                <div class="col-lg-3 bg-dark d-none d-lg-block">
                    <a href="index.php" class="navbar-brand w-100 h-100 m-0 p-0 d-flex align-items-center justify-content-center">
                        <img src="img/cilminton.png" alt="Logo" class="img-fluid">
                    </a>
                </div>
                <div class="col-lg-9">
                    <div class="row gx-0 bg-white d-none d-lg-flex">
                        <div class="col-lg-7 px-5 text-start">
                            <div class="h-100 d-inline-flex align-items-center py-2 me-4">
                                <i class="fa fa-envelope text-primary me-2"></i>
                                <p class="mb-0">cilminton@gmail.com</p>
                            </div>
                            <div class="h-100 d-inline-flex align-items-center py-2">
                                <i class="fa fa-phone-alt text-primary me-2"></i>
                                <p class="mb-0">+012 345 6789</p>
                            </div>
                        </div>
                        <div class="col-lg-5 px-5 text-end">
                            <div class="d-inline-flex align-items-center py-2">
                                <a class="me-3" href="https://www.facebook.com"><i class="fab fa-facebook-f"></i></a>
                                <a class="me-3" href="https://twitter.com"><i class="fab fa-twitter"></i></a>
                                <a class="me-3" href="https://www.linkedin.com"><i class="fab fa-linkedin-in"></i></a>
                                <a class="me-3" href="https://www.instagram.com"><i class="fab fa-instagram"></i></a>
                                <a class="" href="https://www.youtube.com"><i class="fab fa-youtube"></i></a>

                            </div>
                        </div>
                    </div>
                    <nav class="navbar navbar-expand-lg bg-dark navbar-dark p-3 p-lg-0">
                        <a href="index.php" class="navbar-brand d-block d-lg-none">
                            <h1 class="m-0 text-primary text-uppercase">CILMINTON</h1>
                        </a>
                        <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                            <div class="navbar-nav mr-auto py-0">
                                <a href="index.php" class="nav-item nav-link active">Home</a>
                                <a href="about.php" class="nav-item nav-link">About</a>
                                <a href="service.php" class="nav-item nav-link">Services</a>
                                <a href="booking.php" class="nav-item nav-link">Booking</a>
                                <a href="my-booking.php" class="nav-item nav-link">My Booking</a>

                                <a href="https://wa.me/" class="nav-item nav-link">Contact</a>
                            </div>
                            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) : ?>
                                <form action="loginController.php" method="POST" class="d-inline">
                                    <input type="hidden" name="action" value="logout">
                                    <button type="submit" class="btn btn-primary rounded-0 py-4 px-md-5 d-none d-lg-block">Logout<i class="fa fa-arrow-right ms-3"></i></button>
                                </form>
                            <?php else : ?>
                                <a href="login.php" class="btn btn-primary rounded-0 py-4 px-md-5 d-none d-lg-block">Login<i class="fa fa-arrow-right ms-3"></i></a>
                            <?php endif; ?>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
        <!-- Header End -->


        <!-- Page Header Start -->
        <div class="container-fluid page-header mb-5 p-0" style="background-image: url(img/bd.jpg);">
            <div class="container-fluid page-header-inner py-5">
                <div class="container text-center pb-5">
                    <h1 class="display-3 text-white mb-3 animated slideInDown">Booking</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center text-uppercase">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item text-white active" aria-current="page">Booking</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    <div class="container py-5">
        <h1 class="text-center mb-5">Select Time Slot</h1>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form method="post" action="booking2.php" class="card shadow-sm p-4">
                    <input type="hidden" name="date" value="<?php echo htmlspecialchars($selectedDate); ?>">
                    <div class="form-group">
                        <label for="timeSlot" class="font-weight-bold">Available Time Slots</label>
                        <select class="form-control" id="timeSlot" name="timeSlot" onchange="this.form.submit()" required>
                            <?php
                            // Array untuk menyimpan kombinasi unique
                            $uniqueTimeSlots = [];
                            // Counter untuk membatasi jumlah tampilan
                            $counter = 0;

                            if (isset($_POST['timeSlot'])) {
                                $selectedTimeSlot = $_POST['timeSlot'];
                                foreach ($timeSlots as $slot) {
                                    $timeSlotLabel = $slot['waktu_mulai'] . " - " . $slot['waktu_selesai'];
                                    // Gabungkan time slot dan id fasilitas untuk unique key
                                    $uniqueKey = $slot['id_waktu'] . '-' . $slot['id_fasilitas'];
                                    if (!in_array($uniqueKey, $uniqueTimeSlots)) {
                                        $uniqueTimeSlots[] = $uniqueKey;
                                        $selected = ($slot['id_waktu'] == $selectedTimeSlot) ? "selected" : "";
                                        echo "<option value='" . $slot['id_waktu'] . "' " . $selected . ">" . $timeSlotLabel . "</option>";
                                        // Increment counter and break if 15 unique slots have been displayed
                                        if (++$counter >= 15) {
                                            break;
                                        }
                                    }
                                }
                            } else {
                                echo "<option value='' selected>-- Select Time Slot --</option>";
                                foreach ($timeSlots as $slot) {
                                    if ($slot['status'] == 0) {
                                        $timeSlotLabel = $slot['waktu_mulai'] . " - " . $slot['waktu_selesai'];
                                        // Gabungkan time slot dan id fasilitas untuk unique key
                                        $uniqueKey = $slot['id_waktu'] . '-' . $slot['id_fasilitas'];
                                        if (!in_array($uniqueKey, $uniqueTimeSlots)) {
                                            $uniqueTimeSlots[] = $uniqueKey;
                                            echo "<option value='" . $slot['id_waktu'] . "'>" . $timeSlotLabel . "</option>";
                                            // Increment counter and break if 15 unique slots have been displayed
                                            if (++$counter >= 15) {
                                                break;
                                            }
                                        }
                                    }
                                }
                            }
                            ?>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <?php if (!empty($facilities)) : ?>
            <h1 class="text-center my-5">Select Facility</h1>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <form method="post" action="controller.php" class="card shadow-sm p-4">
                        <input type="hidden" name="action" value="bookFacility">
                        <input type="hidden" name="date" value="<?php echo htmlspecialchars($selectedDate); ?>">
                        <input type="hidden" name="timeSlot" value="<?php echo htmlspecialchars($_POST['timeSlot']); ?>">
                        <div class="form-group">
                            <label for="facility" class="font-weight-bold">Available Facilities</label>
                            <select class="form-control" id="facility" name="facility" required>
                                <?php foreach ($facilities as $facility) : ?>
                                    <option value="<?php echo $facility['id_fasilitas']; ?>">
                                        <?php echo htmlspecialchars($facility['nama_fasilitas']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Dropdown menu untuk memilih voucher -->
                        <div class="form-group">
                            <label for="voucher" class="font-weight-bold">Select Voucher</label>
                            <select class="form-control" id="voucher" name="voucher">
                                <option value="">-- Select Voucher --</option>
                                <?php foreach ($vouchers as $voucher) : ?>
                                    <option value="<?php echo $voucher['id_voucher']; ?>"><?php echo htmlspecialchars($voucher['nama_voucher']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Dropdown menu untuk memilih extra -->
                        <div class="form-group">
                            <label for="extra" class="font-weight-bold">Select Extra</label>
                            <select class="form-control" id="extra" name="extra">
                                <option value="">-- Select Extra --</option>
                                <?php foreach ($extras as $extra) : ?>
                                    <option value="<?php echo $extra['id_barang']; ?>"><?php echo htmlspecialchars($extra['nama_barang']) . " - Rp " . number_format($extra['harga_barang'], 0, ',', '.'); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Input untuk kuantitas extra -->
                        <div class="form-group">
                            <label for="kuantitas_extra" class="font-weight-bold">Quantity</label>
                            <input type="number" class="form-control" id="kuantitas_extra" name="kuantitas_extra" min="1" required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block mt-3">Confirm Booking</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer Start -->
    <br><br><br><br><br><br><br>
    <div class="container-fluid bg-dark text-light footer wow fadeIn" data-wow-delay="0.1s" style="padding-top: 50px; padding-bottom: 50px;">
        <div class="container pb-5">
            <div class="row g-5">
                <div class="col-md-6 col-lg-4">
                    <div class="bg-primary rounded p-4">
                        <a href="index.html">
                            <img src="img/cilminton.png" alt="Logo" class="img-fluid">
                        </a>

                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <h6 class="section-title text-start text-primary text-uppercase mb-4">Contact</h6>
                    <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>Jl.Cilimus No.14, Isola</p>
                    <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>+012 345 67890</p>
                    <p class="mb-2"><i class="fa fa-envelope me-3"></i>cilminton@gmail.com</p>
                    <div class="d-flex pt-2">
                        <a class="btn btn-outline-light btn-social" href="https://twitter.com"><i class="fab fa-twitter"></i></a>
                        <a class="btn btn-outline-light btn-social" href="https://facebook.com"><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-outline-light btn-social" href="https://youtube.com"><i class="fab fa-youtube"></i></a>
                        <a class="btn btn-outline-light btn-social" href="https://linkedin.com"><i class="fab fa-linkedin-in"></i></a>

                    </div>
                </div>
                <div class="col-lg-5 col-md-12">
                    <div class="row gy-5 g-4">
                        <div class="col-md-6">
                            <h6 class="section-title text-start text-primary text-uppercase mb-4">Company</h6>
                            <a class="btn btn-link" href="about.php">About Us</a>
                            <a class="btn btn-link" href="booking.php">Booking</a>
                            <a class="btn btn-link" href="contact.php">Contact Us</a>
                        </div>
                        <div class="col-md-6">
                            <h6 class="section-title text-start text-primary text-uppercase mb-4">Services</h6>
                            <a class="btn btn-link" href="service.php">Court</a>
                            <a class="btn btn-link" href="service.php">Foods</a>
                            <a class="btn btn-link" href="service.php">Drinks</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="copyright">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        &copy; <a class="border-bottom" href="#">Cilminton</a>, All Right Reserved.

                        <!-- /*** This template is free as long as you keep the footer author’s credit link/attribution link/backlink. If you'd like to use the template without the footer author’s credit link/attribution link/backlink, you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". Thank you for your support. ***/
                            Designed By <a class="border-bottom" href="https://htmlcodex.com">HTML Codex</a>
                            <br>Distributed By: <a class="border-bottom" href="https://themewagon.com" target="_blank">ThemeWagon</a> -->
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <div class="footer-menu">
                            <a href="">Home</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>