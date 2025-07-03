<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="<?php echo base_url('assets/') ?>img/profil/<?php echo $toko->logo_toko ?>" rel="icon">
  <title><?php echo $title ?> | DIGIPOS</title>
  <link href="<?php echo base_url('assets/') ?>vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo base_url('assets/') ?>vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="<?php echo base_url('assets/') ?>vendors/nprogress/nprogress.css" rel="stylesheet">
  <link href="<?php echo base_url('assets/') ?>vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
  <link href="<?php echo base_url('assets/') ?>build/css/custom.min.css" rel="stylesheet">
  <!-- <link rel="stylesheet" href="<?php //echo base_url('assets/')
                                    ?>select2/dist/css/select2.min.css"> -->
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/'); ?>DataTables/datatables.min.css" />
  <link href="<?php echo base_url('assets/'); ?>bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
  <link href="<?php echo base_url('assets/'); ?>bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/'); ?>sweetalert2/dist/sweetalert2.min.css" />
  <script src="<?php echo base_url('assets/') ?>sweetalert2/dist/sweetalert2.min.js"></script>
  <link rel="stylesheet" href="<?php echo base_url('assets/select2/select2.css'); ?>" type="text/css" />
  <link rel="stylesheet" href="<?php echo base_url('assets/icomoon/styles.css'); ?>" type="text/css" />
  <link href="<?php echo base_url('assets/') ?>vendors/switchery/dist/switchery.min.css" rel="stylesheet">
  <style>
    .dataTables_processing {
      display: none !important;
    }
    
    /* Universal Alert Styling */
    .alert-container {
      margin-bottom: 15px;
    }

    .alert-container .alert {
      position: relative;
      padding: 15px;
      margin-bottom: 20px;
      border: 1px solid transparent;
      border-radius: 4px;
    }

    .alert-container .alert-success {
      color: #3c763d;
      background-color: #dff0d8;
      border-color: #d6e9c6;
    }

    .alert-container .alert-danger {
      color: #a94442;
      background-color: #f2dede;
      border-color: #ebccd1;
    }

    .alert-container .alert-warning {
      color: #8a6d3b;
      background-color: #fcf8e3;
      border-color: #faebcc;
    }

    .alert-container .alert-info {
      color: #31708f;
      background-color: #d9edf7;
      border-color: #bce8f1;
    }

    .alert-container .alert .close {
      position: absolute;
      top: 0;
      right: 0;
      padding: 15px;
      cursor: pointer;
      background: transparent;
      border: 0;
      font-size: 21px;
      font-weight: bold;
      line-height: 1;
      color: #000;
      text-shadow: 0 1px 0 #fff;
      opacity: .2;
    }

    .alert-container .alert .close:hover {
      opacity: .5;
    }
  </style>

</head>

<script>
  var base_url = "<?php echo base_url() ?>";
</script>

<body class="nav-md">
  <div class="container body">
    <div class="main_container">