<?php
$hlm = "Home";
if (uri_string() != "") {
  $hlm = ucwords(uri_string());
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>- LaundryKu - <?php echo $hlm ?></title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="<?= base_url() ?>NiceAdmin/assets/img/favicon.png" rel="icon">
  <link href="<?= base_url() ?>NiceAdmin/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="<?= base_url() ?>NiceAdmin/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?= base_url() ?>NiceAdmin/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="<?= base_url() ?>NiceAdmin/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="<?= base_url() ?>NiceAdmin/assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="<?= base_url() ?>NiceAdmin/assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="<?= base_url() ?>NiceAdmin/assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="<?= base_url() ?>NiceAdmin/assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="<?= base_url() ?>NiceAdmin/assets/css/style.css" rel="stylesheet">

  <style>
    /* Global font override for Baloo 2 */
    * {
      font-family: 'Baloo 2', sans-serif !important;
    }
    
    /* Rounded corners for various elements */
    .card, .modal-content, .alert, .dropdown-menu {
      border-radius: 16px !important;
    }
    
    .form-control, .form-select {
      border-radius: 12px !important;
    }
    
    .btn {
      border-radius: 50px !important; /* Rounded pill buttons */
      font-weight: 600 !important;
    }
    
    /* Sidebar styling: rounded active link, custom color matching */
    .sidebar-nav .nav-link {
      border-radius: 12px !important;
      margin-bottom: 5px !important;
      font-weight: 600 !important;
      transition: all 0.2s ease !important;
    }
    
    /* Soft cyan/blue theme for active link */
    .sidebar-nav .nav-link:not(.collapsed) {
      background-color: #e0f2fe !important; /* Soft blue/cyan */
      color: #0284c7 !important; /* Tailored blue text */
    }
    
    .sidebar-nav .nav-link:not(.collapsed) i,
    .sidebar-nav .nav-link:not(.collapsed) span {
      color: #0284c7 !important;
    }
    
    /* Hover state for sidebar items - strictly no purple! */
    .sidebar-nav .nav-link:hover {
      background-color: #f0f9ff !important;
      color: #0284c7 !important;
    }
    
    .sidebar-nav .nav-link:hover i,
    .sidebar-nav .nav-link:hover span {
      color: #0284c7 !important;
    }
    
    /* Primary buttons override */
    .btn-primary {
      background-color: #0ea5e9 !important;
      border-color: #0ea5e9 !important;
      color: #fff !important;
    }
    
    /* Override info colors to match fresh laundry theme */
    .btn-info {
      background-color: #0ea5e9 !important;
      border-color: #0ea5e9 !important;
      color: #fff !important;
    }
    
    /* Hover states - strictly no purple! */
    .btn:hover {
      opacity: 0.9 !important;
    }
    
    .btn-primary:hover {
      background-color: #0284c7 !important;
      border-color: #0284c7 !important;
      color: #fff !important;
    }
    
    .btn-info:hover {
      background-color: #0284c7 !important;
      border-color: #0284c7 !important;
      color: #fff !important;
    }
    
    .btn-success:hover {
      background-color: #059669 !important;
      border-color: #059669 !important;
      color: #fff !important;
    }
    
    .btn-warning:hover {
      background-color: #d97706 !important;
      border-color: #d97706 !important;
      color: #fff !important;
    }
    
    .btn-danger:hover {
      background-color: #dc2626 !important;
      border-color: #dc2626 !important;
      color: #fff !important;
    }
    
    .text-info {
      color: #0ea5e9 !important;
    }
    
    .bg-info {
      background-color: #0ea5e9 !important;
    }
  </style>
</head>

<body>

  <?= $this->include('components/header') ?>

  <?= $this->include('components/sidebar') ?>

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Data Tables</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">Home</li>
          <?php
          if ($hlm != "Home") {
            ?>
            <li class="breadcrumb-item"><?php echo $hlm ?></li>
            <?php
          }
          ?>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <div class="card-body">
                <h5 class="card-title"><?php echo $hlm ?></h5>
                <?= $this->renderSection('content') ?>
              </div>
            </div>
          </div>

        </div>
      </div>
    </section>

  </main><!-- End #main -->

  <?= $this->include('components/footer') ?>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="<?= base_url() ?>NiceAdmin/assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="<?= base_url() ?>NiceAdmin/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?= base_url() ?>NiceAdmin/assets/vendor/chart.js/chart.umd.js"></script>
  <script src="<?= base_url() ?>NiceAdmin/assets/vendor/echarts/echarts.min.js"></script>
  <script src="<?= base_url() ?>NiceAdmin/assets/vendor/quill/quill.min.js"></script>
  <script src="<?= base_url() ?>NiceAdmin/assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="<?= base_url() ?>NiceAdmin/assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="<?= base_url() ?>NiceAdmin/assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="<?= base_url() ?>NiceAdmin/assets/js/main.js"></script>

</body>

</html>