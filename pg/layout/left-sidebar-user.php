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
        <li class="treeview">
        <a href="#">
           <span>Nursery Green House</span>
          <!-- <i class="../../pg/dashboard_main/index_nft.php"></i> <span>GH NFT</span> -->
          <span class="pull-right-container">
            <i class="fa fa-angle-down pull-right"></i>
          </span>
          
        </a>

        <ul class="treeview-menu">
            <li class='active' <?php isActive("dashboard") ?>>
              <a href="../../pg/dashboard_main/"><i class="fa fa-home"></i>Dashboard</a>
            </li>        
            <?php $tanggal_kini = date("Y-m-d")?>
          <li><a href="../../pg/ap_info/"><i class="fa fa-pencil-square-o"></i>Informasi Budidaya</a></li>    
          <li><a href="../../pg/ap_foto_capture/"><i class="fa fa-camera"></i>Pemotretan</a></li>
          <li><a href="../../pg/ap_foto_proses"><i class="fa fa-retweet"></i>Proses Gambar</a></li>
          <li><a href="../../pg/ap_foto_hasil/?kode=01&dari=<?php echo "$tanggal_kini"?>&sampai=<?php echo "$tanggal_kini"?>"><i class="fa fa-picture-o"></i>Hasil Foto</a></li>          
          <li>
              <a href="#"><i class="fa fa-bar-chart"></i>Interpretasi
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="../../pg/ap_interpretasi_pixel/?&blok=A&tray=1&dari=<?php echo "$tanggal_kini"?>&sampai=<?php echo "$tanggal_kini"?>"><i class="fa fa-circle-o"></i>Piksel-Luasan</a></li>                            
                <li><a href="../../pg/ap_interpretasi_kehijauan/?&blok=A&tray=1&dari=<?php echo "$tanggal_kini"?>&sampai=<?php echo "$tanggal_kini"?>"><i class="fa fa-circle-o"></i>Index Kehijauan</a></li>                            
                <li><a href="../../pg/ap_interpretasi_keabuan/?&blok=A&tray=1&dari=<?php echo "$tanggal_kini"?>&sampai=<?php echo "$tanggal_kini"?>"><i class="fa fa-circle-o"></i>Index Keabuan</a></li>                            
              </ul>
          </li>
        </ul>
        </li>

        <!-- <li class="header">GH Rakit Apung</li>

        <li class="treeview">
        <a href="index.php">
          <i class="fa fa-share"></i> <span>GH Rakit Apung</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>

        <ul class="treeview-menu">
          <li class='active' <?php isActive("dashboard") ?>>
            <a href="../../pg/dashboard_2_nft"><i class="fa fa-circle-o"></i>Dashboard</a>
          </li>            
          
          <li>
            
            <li><a href="../../pg/ds_sgh_chiller/?dari=<?php echo "$tanggal_kini"?>&sampai=<?php echo "$tanggal_kini"?>"><i class="fa fa-circle-o"></i>Chiller</a></li>  
          </li>
            <li><a href="../../pg/ds_ra_akar1/?dari=<?php echo "$tanggal_kini"?>&sampai=<?php echo "$tanggal_kini"?>"><i class="fa fa-circle-o"></i>Suhu Perakaran (°C)</a></li>
          </li>

          <li>
            <a href="#"><i class="fa fa-circle-o"></i>Intensitas Cahaya (Lux)
              <span class="pull-right-container">
              </span>
            </a>
          </li>         

          <li>
            <a href="#"><i class="fa fa-circle-o"></i>Kelembaban Udara (%)
              <span class="pull-right-container">
              </span>
            </a>
          </li> -->
          


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