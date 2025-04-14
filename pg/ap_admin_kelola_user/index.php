<?php
  include_once "../layout/header.php";
   ?>
<body class="hold-transition skin-blue sidebar-mini">
  
  <link rel="stylesheet" href="tablesjs/dataTables.bootstrap.css">
  <script src="tablesjs/jquery.dataTables.js"></script>
  <script src="tablesjs/dataTables.bootstrap.js"></script>
  
  


  <div class="wrapper">
    <?php include_once "../../_db/db_conn.php"; ?>
    <?php include_once "tambah_entry.php"; ?>
    <?php include_once "ubah_entry.php"; ?>
    <?php include_once "hapus_entry.php"; ?>
    <?php include_once "../layout/topmenu.php"; ?>
    <?php include_once "../layout/left-sidebar-apoy.php"; ?>


    <div class="content-wrapper">
      <section class="content">
        
        
        <?php include_once("main_header.php") ?>  
        
        
      </section>
    </div>   
    
    
    <?php include_once "../layout/copyright.php"; ?>
    <?php include_once "../layout/right-sidebar.php"; ?>
    <div class="control-sidebar-bg"></div>
    </div>


    <?php include_once "../layout/footer.php" ?>
    <!-- <script src="tablesjs/script.js"></script> -->
