<!-- Content Header (Page header) -->
    <?php 
    include_once "../../_db/db_conn.php";
    $id_lokasi  = 1;
    $query_lokasi = "SELECT * 
                        FROM ds_lokasi ;";
    // echo $query_fasilitas;
    if($result = $mysqli->query($query_lokasi)){
      // echo $id_fasilitas;
      if($result->num_rows > 0){
        
          while($row = $result->fetch_array()){
              
              $nama_lokasi= $row['nama_lokasi'];
              // $jenis_lokasi = $row['jenis_lokasi'];
              // $id_jenis_fasilitas = $row['id_jenis_fasilitas'];
              $deskripsi_lokasi= $row['deskripsi_lokasi'];
              $luas = $row['luas_lokasi'];

              // if (isset($row['luas_fasilitas'])) {
              //   $luas = $row['luas_fasilitas'];
              // } else {
              //   $luas = "-";
              // }
              if (isset($row['dimensi_lokasi'])) {
                $dimensi = $row['dimensi_lokasi'];
              } else {
                $dimensi = "-";
              }
              if (isset($row['lat_lokasi'])) {
                $lat = $row['lat_lokasi'];
              } else {
                $lat = "-";
              }
              if (isset($row['long_lokasi'])) {
                $long = $row['long_lokasi'];
              } else {
                $long = "-";
              }
              if (isset($row['gambar_lokasi'])) {
                $gambar_lokasi = $row['gambar_lokasi'];
              } else {
                $gambar_lokasi = "-";
              }
          }
            $result->free();
      }
    }

    
      $query_budidaya = "SELECT *
                        FROM ds_tanaman;";

      if($result = $mysqli->query($query_budidaya)){
        if($result->num_rows > 0){
            while($row = $result->fetch_array()){
              if (isset($row['tanggal_mulai'])) {
                $tanggal_mulai = $row['tanggal_mulai'];
              }
              if (isset($row['tanggal_semai'])) {
                $tanggal_selesai = $row['tanggal_semai'];
              }
              if (isset($row['tanggal_semai'])) {
                $tanggal_semai = $row['tanggal_semai'];
              }
              $tanggal_panen = $row['tanggal_panen'];
              $jenis_tanaman = $row['jenis_tanaman'];
              
              if (isset($row['varietas_tanaman'])) {
                $varietas_tanaman = $row['varietas_tanaman'];
              }
              if (isset($row['populasi'])) {
                $populasi = $row['populasi'];
              }
              if (isset($row['tipe_budidaya'])) {
                $tipe_budidaya = $row['tipe_budidaya'];
              }
              if (isset($row['tipe_irigasi'])) {
                $tipe_irigasi = $row['tipe_irigasi'];
              }
                $pic_budidaya = $row['pic_budidaya'];
                $gambar_budidaya = $row['gambar_budidaya'];
                $deskripsi_budidaya = $row['deskripsi_budidaya'];
            }
              $result->free();
        }
      }
    



    ?>

    <section class="content-header">
      <h1>
        <b>Sistem Informasi</b> <?php echo ucwords($nama_lokasi)?>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">  
              <h3 class="box-title">Informasi Lokasi Budidaya</h3>
              <a href='../../pg/ap_admin_kelola_user/' class='btn btn-primary pull-right' data-toggle='modal'>
                <i class='glyphicon glyphicon-cog' data-toggle='tooltitp' title='Setting'></i>
                <span class="text-bold">Pengaturan</span>
              </a>
            </div>
            <div class="box-body">

            <div class="col-md-6">
                <table class="table">
                  <thead>
                    <tr>
                      <th width="30%"> </th>
                      <th width="1%"> </th>
                      <th width="69%"> </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Nama Lokasi</td>
                      <td>:</td>
                      <td><?php echo ucfirst($nama_lokasi)?></td>
                    </tr>
                    <tr>
                      <td>Luas</td>
                      <td>:</td>
                      <td><?php echo ucfirst($luas)?> m²</td>
                    </tr>
                    <tr>
                      <td>Dimensi</td>
                      <td>:</td>
                      <td><?php echo ucfirst($dimensi)?> m</td>
                    </tr> 
                    <tr>
                      <td>Koordinat</td>
                      <td>:</td>
                      <td><?php echo ucfirst($lat)?>, <?php echo ucfirst($long)?></td>
                    </tr>
                    <tr>
                      <td>Deskripsi</td>
                      <td>:</td>
                      <td><?php echo ucfirst($deskripsi_lokasi)?></td>
                    </tr>                  
                  </tbody>
                </table>
              </div>
              
              <div class="col-md-6">
                <img src="../<?php echo $gambar_lokasi?>" alt="<?php echo $nama_lokasi?>" width="100%">
              </div>        
              <hr>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <!-- /.col -->
        <div class="col-md-12">

          <div class="nav-tabs-custom">
          <a href='../../pg/ap_admin_kelola_tanaman/' class='btn btn-primary pull-right' data-toggle='modal'>
                <i class='glyphicon glyphicon-cog' data-toggle='tooltitp' title='Setting'></i>
                <span class="text-bold">Pengaturan</span>
              </a>
            <ul class="nav nav-tabs">
              <li class="active"><a href="#cabairawit" data-toggle="tab">Cabai Rawit</a></li>
              <!-- <li ><a href="#tomat" data-toggle="tab">Tomat</a></li> -->
              <!-- <li><a href="#timeline" data-toggle="tab">Timeline</a></li> -->
            </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="cabairawit">
                
                <!-- Post -->
                <div class="post">
                  <div class="box-body">
                    <div class="col-md-6">
                      <img src="../../citra/cabairawit-A1-20230717162422.jpg" alt="screenhouse" width="100%">
                    </div>
                    <div class="col-md-6">
                      <table class="table">
                        <thead>
                          <tr>
                            <th width="28%"> </th>
                            <th width="2%"> </th>
                            <th width="70%"> </th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>Jenis Tanaman</td>
                            <td>:</td>
                            <td><?php echo ucfirst($jenis_tanaman)?> "<i><?php echo $varietas_tanaman?></i>"</td>
                          </tr>
                          <tr>
                            <td>Varietas Tanaman</td>
                            <td>:</td>
                            <td><?php echo ucwords($varietas_tanaman)?></td>
                          </tr>
                          <tr>
                            <td>Populasi</td>
                            <td>:</td>
                            <td><?php echo ucfirst($populasi)?></td>
                          </tr>
                          <tr>
                            <td>Tipe Budidaya</td>
                            <td>:</td>
                            <td><?php echo ucfirst($tipe_budidaya)?></td>
                          </tr>
                          <tr>
                            <td>Tipe Irigasi</td>
                            <td>:</td>
                            <td><?php echo ucfirst($tipe_irigasi)?></td>
                          </tr>                  
                          <tr>
                            <td>Penanggung Jawab</td>
                            <td>:</td>
                            <td><?php echo ucfirst($pic_budidaya)?></td>
                          </tr>                  
                          <!-- <tr>
                            <td>Pelaksana Budidaya</td>
                            <td>:</td>
                            <td><?php //echo ucfirst($petugas_budidaya)." (".$petugas_budidaya_telp.")"?></td>
                          </tr>                   -->
                          <tr>
                            <td>Deskripsi Budidaya</td>
                            <td>:</td>
                            <td><?php echo ucfirst($deskripsi_budidaya)?></td>
                          </tr>                     
                        </tbody>
                      </table>
                    </div>
                    
                </div>    
              </div>
                <!-- /.post -->
              </div>

              <!-- <div class="tab-pane" id="tomat">
              
              <div class="post">
                  <div class="box-body">
                    <div class="col-md-6">
                      <img src="../../images/melon/2.JPG" alt="screenhouse" width="100%">
                    </div>
                    <div class="col-md-6">
                      <table class="table">
                        <thead>
                          <tr>
                            <th width="28%"> </th>
                            <th width="2%"> </th>
                            <th width="70%"> </th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>Jenis Tanaman</td>
                            <td>:</td>
                            <td><?php echo ucfirst($jenis_tanaman)?> "<i><?php echo $varietas_tanaman?></i>"</td>
                          </tr>
                          <tr>
                            <td>Varietas Tanaman</td>
                            <td>:</td>
                            <td><?php echo ucwords($varietas_tanaman)?></td>
                          </tr>
                          <tr>
                            <td>Populasi</td>
                            <td>:</td>
                            <td><?php echo ucfirst($populasi)?></td>
                          </tr>
                          <tr>
                            <td>Tipe Budidaya</td>
                            <td>:</td>
                            <td><?php echo ucfirst($tipe_budidaya)?></td>
                          </tr>
                          <tr>
                            <td>Tipe Irigasi</td>
                            <td>:</td>
                            <td><?php echo ucfirst($tipe_irigasi)?></td>
                          </tr>                  
                          <tr>
                            <td>Penanggung Jawab</td>
                            <td>:</td>
                            <td><?php echo ucfirst($pic_budidaya)." (".$pic_budidaya_telp.")"?></td>
                          </tr>                  
                          <tr>
                            <td>Pelaksana Budidaya</td>
                            <td>:</td>
                            <td><?php echo ucfirst($petugas_budidaya)." (".$petugas_budidaya_telp.")"?></td>
                          </tr>                  
                          <tr>
                            <td>Deskripsi Budidaya</td>
                            <td>:</td>
                            <td><?php echo ucfirst($deskripsi_budidaya)?></td>
                          </tr>                     
                        </tbody>
                      </table>
                    </div>
                    
                </div>    
              </div> -->
                <!-- /.post -->
              </div>
              
            </div>

              
      <!-- /.row -->

    </section>
    <!-- /.content -->