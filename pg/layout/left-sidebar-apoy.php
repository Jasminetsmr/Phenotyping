<?php
  function isActive($menu, $mode="full"){
    global $active_menu;
    if ($mode == "partial")
      echo ($active_menu == $menu? "active": "");
    else
      echo ($active_menu == $menu? "class='active'": "");
  }
?>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="../images/admin.png" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php echo $_SESSION["username"];?></p>
          <a href="pages/dashboard"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        
 

        <!-- <li class="header">GH NFT</li> -->
        <!-- <li class='treeview'>
          <a href='#'>
            <span>Sistem Admin</span>
            <span class='pull-right-container'>
              <i class='fa fa-angle-down'></i>
            </span>
          </a>
          <ul class='treeview-menu'>
            <li>
              <a href='../ap_admin_kelola_lokasi'><i class='fa fa-circle-o'></i>Tambah/Ubah Lokasi</a>
            </li>
            <li>
              <a href='../ap_admin_kelola_tanaman'><i class='fa fa-circle-o'></i>Tambah/Ubah Tanaman</a>
            </li>
            <li>
              <a href='../ap_admin_kelola_layout'><i class='fa fa-circle-o'></i>Tambah/Ubah Layout</a>
            </li>
            <li>
              <a href='../ap_admin_kelola_kamera'><i class='fa fa-circle-o'></i>Tambah/Ubah Kamera</a>
            </li>
            <li>
              <a href='../ap_admin_kelola_user'><i class='fa fa-circle-o'></i>Tambah/Ubah User</a>
            </li>
            
          </ul>
        </li> -->
        <li class="treeview">
        <a href="../../pg/dashboard_main/"><i class="fa fa-home"></i>Dashboard</a>
</li>
        <li class="treeview active">
        
        <a href="#">
           <span>Nursery Greenhouse</span>
          <!-- <i class="../../pg/dashboard_main/index_nft.php"></i> <span>GH NFT</span> -->
          <span class="pull-right-container">
            <i class="fa fa-angle-down pull-right"></i>
          </span>
          
        </a>

        <ul class="treeview-menu">   
            <?php $tanggal_kini = date("Y-m-d")?>
          <li><a href="../../pg/ap_info/"><i class="fa fa-pencil-square-o"></i>Informasi Budidaya</a></li>    
 
          <li><a href="../../pg/ap_foto_hasil/?kode=01&dari=<?php echo "$tanggal_kini"?>&sampai=<?php echo "$tanggal_kini"?>"><i class="fa fa-picture-o"></i>Hasil Foto</a></li>          
          <li>
              <a href="#"><i class="fa fa-object-group"></i>Image Processing
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="../../pg/ap_foto_capture/"><i class="fa fa-camera"></i>Capturing</a></li>
                <li><a href="../../pg/ap_foto_proses/"><i class="fa fa-cogs"></i>Prosessing</a></li>
              </ul>
          </li>
          <li>
              <a href="#"><i class="fa fa-cubes"></i>Deep Learning
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="../../pg/roboflow/"><i class="fa fa-eye"></i>Object Detection</a></li>
                <li><a href="../../pg/camera"><i class="fa fa-video-camera"></i>Real-Time Monitoring</a></li>
                <li><a href="../../pg/deeplearning"><i class="fa fa-eye"></i>Object Detection 2</a></li>
              </ul>
          </li>
        </ul>



        </li>

        <!-- <li class="header">DOKUMEN</li>

        <li <?php isActive("documentation") ?>>
          <a href="#">
            <i class="fa fa-book"></i> 
            <span>Panduan Penggunaan</span>
          </a>
        </li>

        <li>
          <a href="#">
            <i class="fa fa-circle-o text-red"></i> 
            <span>FAQ</span>
          </a>
        </li> -->

      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
<script>
  var parent = $("ul.sidebar-menu li.active").closest("ul").closest("li");
  if (parent[0] != undefined)
    $(parent[0]).addClass("active");
</script>