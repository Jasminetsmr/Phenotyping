<?php
  $active_menu = "profile";
  include_once "../layout/header.php";
  if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
  } else {
    $id = 1;
  }
  
?>


<body class="hold-transition skin-green sidebar-mini">
  <!-- Put Page-level css and javascript libraries here -->


  <!-- ================================================ -->

  <div class="wrapper">

    <?php include_once "../layout/topmenu.php"; ?>
    <?php include_once "../layout/left-sidebar.php"; ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
    <?php
        if ($_SESSION["level_user"] == "admin") {
          
          include_once("admin.php");
        } 
          elseif ($_SESSION["level_user"] == "user") {
          include_once("user.php");
        }
    ?>   
    </div><!-- /.content-wrapper -->
    
    <?php include_once "../layout/copyright.php"; ?>
  

    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
  </div><!-- ./wrapper -->

<?php include_once "../layout/footer.php" ?>
<script src="profile/script.js"></script>