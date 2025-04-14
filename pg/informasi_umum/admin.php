<!-- Content Header (Page header) -->

    <?php 
    include_once "../../_db/db_conn.php";
    $id_fasilitas  = $id;
    $query_fasilitas = "SELECT * 
                        FROM ds_kawasan_blok_fasilitas
                        JOIN ds_kawasan_blok_fasilitas_jenis
                        ON ds_kawasan_blok_fasilitas.id_jenis_fasilitas = ds_kawasan_blok_fasilitas_jenis.id_jenis_fasilitas
                        JOIN ds_kawasan_blok
                        ON ds_kawasan_blok.id_blok = ds_kawasan_blok_fasilitas.id_kawasan_blok
                        JOIN ds_kawasan
                        on ds_kawasan.id_kawasan = ds_kawasan_blok.id_kawasan
                        where id_fasilitas = ".$id_fasilitas.";";
    // echo $query_fasilitas;
    if($result = $mysqli->query($query_fasilitas)){
      // echo $id_fasilitas;
      if($result->num_rows > 0){
        
          while($row = $result->fetch_array()){
              
              $nama_fasilitas = $row['nama_fasilitas'];
              $jenis_fasilitas = $row['jenis_fasilitas_idn'];
              $id_jenis_fasilitas = $row['id_jenis_fasilitas'];
              $deskripsi_fasilitas = $row['deskripsi_fasilitas'];
              
              if (isset($row['luas_fasilitas'])) {
                $luas = $row['luas_fasilitas'];
              } else {
                $luas = "-";
              }
              
              if (isset($row['dimensi_fasilitas'])) {
                $dimensi = $row['dimensi_fasilitas'];
              } else {
                $dimensi = "-";
              }
              if (isset($row['lat_fasilitas'])) {
                $lat = $row['lat_fasilitas'];
              } else {
                $lat = "-";
              }
              if (isset($row['long_fasilitas'])) {
                $long = $row['long_fasilitas'];
              } else {
                $long = "-";
              }
              if (isset($row['gambar_fasilitas'])) {
                $gambar_fasilitas = $row['gambar_fasilitas'];
              } else {
                $gambar_fasilitas = "-";
              }
          }
            $result->free();
      }
    }

    if ($id_jenis_fasilitas == 2 || $id_jenis_fasilitas == 3){
      $query_budidaya = "SELECT *
                        FROM ds_kawasan_blok_fasilitas_budidaya 
                        JOIN ds_kawasan_blok_fasilitas 
                        ON ds_kawasan_blok_fasilitas_budidaya.id_kawasan_blok_fasilitas = ds_kawasan_blok_fasilitas.id_fasilitas 
                        WHERE id_fasilitas = ".$id_fasilitas.";";

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
              
              $tanggal_tanam = $row['tanggal_tanam'];
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
                $pic_budidaya_telp = $row['pic_budidaya_no_telp'];
                $petugas_budidaya = $row['petugas_budidaya'];
                $petugas_budidaya_telp = $row['petugas_budidaya_no_telp'];
                $gambar_budidaya = $row['gambar_budidaya'];
                $deskripsi_budidaya = $row['deskripsi_budidaya'];
            }
              $result->free();
        }
      }
    }



    ?>

    <section class="content-header">
      <h1>
        <b>Sistem Informasi</b> <?php echo ucwords($nama_fasilitas)?>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">  
              <h3 class="box-title">Informasi <?php echo ucwords($jenis_fasilitas)?></h3>
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
                      <td>Nama <?php echo ucfirst($jenis_fasilitas)?></td>
                      <td>:</td>
                      <td><?php echo ucfirst($nama_fasilitas)?></td>
                    </tr>
                    <tr>
                      <td>Luas</td>
                      <td>:</td>
                      <td><?php echo ucfirst($luas)?></td>
                    </tr>
                    <tr>
                      <td>Koordinat</td>
                      <td>:</td>
                      <td><?php echo ucfirst($lat)?>, <?php echo ucfirst($long)?></td>
                    </tr>
                    <tr>
                      <td>Deskripsi</td>
                      <td>:</td>
                      <td><?php echo ucfirst($deskripsi_fasilitas)?></td>
                    </tr>                  
                  </tbody>
                </table>
              </div>
              
              <div class="col-md-6">
                <img src="../../<?php echo $gambar_fasilitas?>" alt="<?php echo $nama_fasilitas?>" width="100%">
              </div>        
              <hr>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">  
              <h3 class="box-title">Informasi Budidaya</h3>
            </div>
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
              <div class="col-md-1">
              </div>
              
              
            </div>


          </div>
        </div>
      </div>

      <div class="row">
        <!-- /.col -->
        <div class="col-md-12">

          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#budidaya" data-toggle="tab">Aktivitas Budidaya</a></li>
              <li ><a href="#pelatihan" data-toggle="tab">Edukasi dan Pelatihan</a></li>
              <li ><a href="#riset" data-toggle="tab">Riset dan Pengembangan Teknologi</a></li>
              <!-- <li><a href="#timeline" data-toggle="tab">Timeline</a></li> -->
            </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="budidaya">
                
                <!-- Post -->
                <div class="post">
                  <div class="user-block">
                    <img class="img-circle img-bordered-sm" src="../../images/melon/2_bulat.png" alt="user image">
                        <span class="username">
                          <a href="#">Penjelasan Singkat</a>
                        </span>
                  </div>
                  <!-- /.user-block -->
                  <p>
                    Screenhouse hidroponik substrat dibangun sebagai unit percontohan dan edukasi pertanian tentang hidroponok substrat dan sistem irigasi tetes pada budidaya tanaman buah dan sayuran buah seperti semangka, melon, tomat, timun, dan cabai.
                    Sistem monitoring kemudian dikembangkan pada screenhouse ini untuk memantau dan merekam parameter lingkungan mikro berupa suhu dan kelembaban udara, intensitas cahaya matahari di dalam greenhouse, serta pertumbuhan tanaman melalui data citra tanaman selama budidaya.
                    Sistem monitoring ini dikembangkan oleh tim digital-farming Direktoran Pelayanan Masyarakat Agromaritim IPB untuk... .
                  </p><p> 
                    Beberapa luaran yang telah dihasilkan menggunakan screenhouse ini, antara lain:
                    
                  </p><p>                    
                    (1) ...
                  </p><p>    
                    (2) ...
                  
                </div>
                <!-- /.post -->

                <!-- Post -->
                <div class="post">
                  <div class="user-block">
                    <img class="img-circle img-bordered-sm" src="../../images/melon/2_bulat.png" alt="User Image">
                        <span class="username">
                          <a href="#">Foto Tanaman</a>

                        </span>
                    <span class="description">Posted 5 photos - 5 days ago</span>
                  </div>
                  <!-- /.user-block -->
                  <div class="row margin-bottom">
                    <div class="col-sm-5">
                      <img class="img-responsive" src="../../images/melon/1.jpg" alt="Photo">
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-7">
                      <div class="row">
                        <div class="col-sm-11">
                          <img class="img-responsive" src="../../images/melon/3.jpg" alt="Photo">
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                    </div>
                    <!-- /.col -->
                  </div>
                </div>
                <!-- /.post -->
              </div>

              <div class="active tab-pane" id="pelatihan">
                
                <!-- Post -->
                <div class="post">
                  <div class="user-block">
                    <img class="img-circle img-bordered-sm" src="../../images/melon/2_bulat.png" alt="user image">
                        <span class="username">
                          <a href="#">Penjelasan Singkat</a>
                        </span>
                  </div>
                  <!-- /.user-block -->
                  <p>
                    Screenhouse hidroponik substrat dibangun sebagai unit percontohan dan edukasi pertanian tentang hidroponok substrat dan sistem irigasi tetes pada budidaya tanaman buah dan sayuran buah seperti semangka, melon, tomat, timun, dan cabai.
                    Sistem monitoring kemudian dikembangkan pada screenhouse ini untuk memantau dan merekam parameter lingkungan mikro berupa suhu dan kelembaban udara, intensitas cahaya matahari di dalam greenhouse, serta pertumbuhan tanaman melalui data citra tanaman selama budidaya.
                    Sistem monitoring ini dikembangkan oleh tim digital-farming Direktoran Pelayanan Masyarakat Agromaritim IPB untuk... .
                  </p><p> 
                    Beberapa luaran yang telah dihasilkan menggunakan screenhouse ini, antara lain:
                    
                  </p><p>                    
                    (1) ...
                  </p><p>    
                    (2) ...
                  
                </div>
                <!-- /.post -->

              </div>

              <div class="active tab-pane" id="riset">
                

                <!-- Post -->
                <div class="post">
                  <div class="user-block">
                    <img class="img-circle img-bordered-sm" src="../../images/melon/2_bulat.png" alt="User Image">
                        <span class="username">
                          <a href="#">Foto Tanaman</a>

                        </span>
                    <span class="description">Posted 5 photos - 5 days ago</span>
                  </div>
                  <!-- /.user-block -->
                  <div class="row margin-bottom">
                    <div class="col-sm-5">
                      <img class="img-responsive" src="../../images/melon/1.jpg" alt="Photo">
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-7">
                      <div class="row">
                        <div class="col-sm-11">
                          <img class="img-responsive" src="../../images/melon/3.jpg" alt="Photo">
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                    </div>
                    <!-- /.col -->
                  </div>
                </div>
                <!-- /.post -->
              </div>

              <!-- /.tab-pane -->
               
              <div class="tab-pane" id="timeline">
                <!-- The timeline -->
                <ul class="timeline timeline-inverse">
                  <!-- timeline time label -->
                  <li class="time-label">
                        <span class="bg-red">
                          Tahun Pertama (2021-2022)
                        </span>
                  </li>
                  <!-- /.timeline-label -->
                  <!-- timeline item -->
                  <li>
                    <i class="fa fa-envelope bg-blue"></i>

                    <div class="timeline-item">
                      <span class="time"><i class="fa fa-clock-o"></i> 12:05</span>

                      <h3 class="timeline-header"><a href="#">Budidaya pada Dataran Tinggi </a> pada greenhouse Pengalengan</h3>

                      <div class="timeline-body">
                        Kegiatan budidaya dengan menggunakan beberapa teknik yaitu NFT dan Irigasi Tetes (Drip Irrigation)
                      </div>
                      <div class="timeline-footer">
                        <a class="btn btn-primary btn-xs">Home</a>
                      </div>
                    </div>
                  </li>
                  <li>
                    <i class="fa fa-comments bg-yellow"></i>

                    <div class="timeline-item">
                      <span class="time"><i class="fa fa-clock-o"></i> 12:05</span>

                      <h3 class="timeline-header"><a href="#">Budidaya pada Plant Factory</a> di IPB University</h3>

                      <div class="timeline-body">
                        Kegiatan budidaya dengan menggunakan beberapa teknik yaitu NFT dan Irigasi Tetes (Drip Irrigation)
                      </div>
                      <div class="timeline-footer">
                        <a class="btn btn-primary btn-xs">Home</a>
                      </div>
                    </div>
                  </li>
                  <!-- END timeline item -->
                  <li class="time-label">
                        <span class="bg-green">
                          Tahun ke 2 (2022-2023)
                        </span>
                  </li>
                  <!-- /.timeline-label -->
                  <!-- timeline item -->
                  <!-- timeline item -->
                  <li>
                    <i class="fa fa-envelope bg-blue"></i>

                    <div class="timeline-item">
                      <span class="time"><i class="fa fa-clock-o"></i> 12:05</span>

                      <h3 class="timeline-header"><a href="#">Budidaya pada SMART Greenhouse </a> IPB University</h3>

                      <div class="timeline-body">
                        Kegiatan budidaya dengan menggunakan beberapa teknik yaitu NFT dan Irigasi Tetes (Drip Irrigation)
                      </div>
                      <div class="timeline-footer">
                        <a class="btn btn-primary btn-xs">Home</a>
                      </div>
                    </div>
                  </li>
                  <li>
                    <i class="fa fa-comments bg-yellow"></i>

                    <div class="timeline-item">
                      <span class="time"><i class="fa fa-clock-o"></i> 12:05</span>

                      <h3 class="timeline-header"><a href="#">Pembuatan Kontrol Cerdas</a> Budidaya Purwoceng</h3>

                      <div class="timeline-body">
                        Kegiatan budidaya dengan menggunakan beberapa teknik yaitu NFT dan Irigasi Tetes (Drip Irrigation)
                      </div>
                      <div class="timeline-footer">
                        <a class="btn btn-primary btn-xs">Home</a>
                      </div>
                    </div>
                  </li>
                  <!-- END timeline item -->
                  <li>
                    <i class="fa fa-clock-o bg-gray"></i>
                  </li>
                </ul>
              </div>
              

              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    </section>
    <!-- /.content -->