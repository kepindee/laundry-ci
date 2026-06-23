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
        
        /* Override info colors to match fresh laundry theme */
        .btn-info {
            background-color: #0ea5e9 !important;
            border-color: #0ea5e9 !important;
            color: #fff !important;
        }
        
        .btn-info:hover {
            background-color: #0284c7 !important;
            border-color: #0284c7 !important;
        }
    </style>
</head>

<body>
    <main>

        <?= $this->renderSection('content') ?>

    </main><!-- End #main -->

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