<?php
  $active_menu = "chartjs";
  include_once "../layout/header.php";
?>

<body class="hold-transition skin-blue sidebar-mini">
  <script src="../../plugins/chartjs/Chart.min.js"></script>
  <div class="wrapper">
    <?php include_once "../../_db/db_conn.php"; ?>
    <?php include_once "../layout/topmenu.php"; ?>
    <?php include_once "../layout/left-sidebar.php"; ?>
    <div class="content-wrapper">
      <section class="content">
        <?php include_once("chartjs/main_header.php") ?>      
      </section>
    </div>
    <?php include_once "../layout/copyright.php"; ?>
    <?php include_once "../layout/right-sidebar.php"; ?>

    <div class="control-sidebar-bg"></div>
  </div><!-- ./wrapper -->

<?php include_once "../layout/footer.php" ?>