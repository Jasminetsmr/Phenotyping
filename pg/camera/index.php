<?php
  $active_menu = "profile";
  include_once "../layout/header.php";
  if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
  } else {
    $id = 1;
  }
  
?>
<meta name="viewport" content="width=640, user-scalable=no" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"
    integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg=="
    crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.20/lodash.min.js"
    integrity="sha512-90vH1Z83AJY9DmlWa8WkjkV79yfS2n2Oxhsi2dZbIv0nC4E6m5AbH8Nh156kkM7JePmqD6tcZsfad1ueoaovww=="
    crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/async/3.2.0/async.min.js"
    integrity="sha512-6K6+H87tLdCWvY5ml9ZQXLRlPlDEt8uXmtELhuJRgFyEDv6JvndWHg3jadJuBVGPEhhA2AAt+ROMC2V7EvTIWw=="
    crossorigin="anonymous"></script>

<script src="https://cdn.roboflow.com/0.2.26/roboflow.js"></script>

<link rel="stylesheet" href="./main.css">
<script src="./main.js"></script>

<body class="hold-transition skin-green sidebar-mini">
  <!-- Put Page-level css and javascript libraries here -->


  <!-- ================================================ -->

  <div class="wrapper">

    <?php include_once "../layout/topmenu.php"; ?>
    <?php include_once "../layout/left-sidebar.php"; ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
    <?php include_once("cam.php"); ?>   
    </div><!-- /.content-wrapper -->
    
    <?php include_once "../layout/copyright.php"; ?>
  

    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
  </div><!-- ./wrapper -->

<?php include_once "../layout/footer.php" ?>
<script src="profile/script.js"></script>