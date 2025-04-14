<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <b>Sistem Monitoring</b> Seedling 
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-tree"></i></span>

            <?php 
             require_once "../../_db/db_conn.php";
                      $sql_recap = "SELECT
                                        ds_tanaman.id_tanaman,
                                        ds_tanaman.populasi,
                                        ds_layout.id_layout,
                                        ds_foto.id_foto
                                    FROM
                                        ds_tanaman
                                    JOIN
                                        ds_layout ON ds_tanaman.id_tanaman = ds_layout.id_tanaman
                                    JOIN
                                        ds_foto ON ds_layout.id_layout = ds_foto.id_layout
                                    ORDER BY id_foto DESC LIMIT 1
                                    ";
                                        
                      $result_recap = $mysqli->query($sql_recap);
                      $recap = $result_recap->fetch_array();
                      echo'
              <div class="info-box-content">
              <span class="info-box-text">Populasi Tanaman</span>
              <span class="info-box-number">'. $recap['populasi'] .' tanaman</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-list-ul"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Jenis Tanaman</span>
              <span class="info-box-number">'. $recap['id_tanaman'] .' jenis</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-file-photo-o"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Citra Tersimpan</span>
              <span class="info-box-number">'. $recap['id_foto'] .' entri</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fa fa-archive"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Tray Terpantau</span>
              <span class="info-box-number">'. $recap['id_layout'] .' tray</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
      ';?>

      <div class="row">
        <div class="col-md-3">
          <!-- About Me Box -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Informasi Budidaya</h3>
            </div>
            <!-- /.box-header -->
            <?php 
             require_once "../../_db/db_conn.php";
                      $sql_info = "SELECT * FROM ds_tanaman, ds_lokasi";
                      $result_info = $mysqli->query($sql_info);
                      $info = $result_info->fetch_array();
                      echo '
            <div class="box-body">
              <strong><i class="fa fa-book margin-r-5"></i>Jenis Tanaman</strong>

              <p class="text-muted">
                '. $info['deskripsi_budidaya'].
              '</p>

              <hr>

              <strong><i class="fa fa-map-marker margin-r-5"></i>Lokasi</strong>
              <p class="text-muted">'.$info['alamat_lokasi'].'</p>
              <hr>

              <strong><i class="fa fa-pencil margin-r-5"></i>Gambar Tanaman</strong>

              <p>
                <img src="../images/area/gh_semaian/gh_semaian.jpg" alt="gambar gh" width="100%"><h1></h1>
                <img src="../images/area/gh_semaian/dalam.jpg" alt="gambar gh" width="100%"><h1></h1>
                <img src="../images/area/gh_semaian/dalam_2.jpg" alt="gambar gh" width="100%"><h1></h1>
              </p>

              <hr>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>';?>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#activity" data-toggle="tab">Entry Terakhir</a></li>
              <li><a href="#timeline" data-toggle="tab">Interpretasi</a></li>
            </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="activity">
                <!-- Post -->
                <div class="post">
                   <!-- /.user-block -->
                   <?php  require_once "../../_db/db_conn.php";

                    // Attempt select query execution
                    $sql_foto = "SELECT
                    ds_foto.*,
                    ds_layout.kode,
                    ds_tanaman.jenis_tanaman
                  FROM
                    (( ds_foto
                    INNER JOIN ds_layout ON ds_foto.id_layout = ds_layout.id_layout
                    )
                      INNER JOIN ds_tanaman ON ds_layout.id_tanaman = ds_tanaman.id_tanaman)
                      ORDER BY id_foto DESC LIMIT 5";
                    $result_foto = $mysqli->query($sql_foto);
                    foreach($result_foto as $elements) { 
                    echo '
                  <div class="user-block">
                    <img class="img-circle img-bordered-sm" src="../images/admin.png" alt="user image"
                        <span class="username">
                          <a href="../../pg/ap_foto_detail/?tanggal='. $elements['tanggal_foto'] .'&waktu='. $elements['waktu_foto'] .'">'. $elements['jenis_tanaman'].' '.$elements['kode']. '</a>
                        </span>
                        <span class="description">'. $elements['tanggal_foto'].' | '.$elements['waktu_foto'].'</span>
                  </div>
                  <div class="row">
                    <div class="col-sm-4">
                      <img class="img-responsive" src="../../citra/'.$elements['nama_foto'] .'" alt="Original" width=400>
                    </div>
                    <div class="col-sm-4">
                      <img class="img-responsive" src="../../citra/threshold/'.$elements['nama_foto'] .'" alt="Threshold" width=400>
                    </div>
                    <div class="col-sm-4">
                      <img class="img-responsive" src="../../citra/bitwise/'.$elements['nama_foto'] .'" alt="Bitwise" width=400>
                    </div>  
                  </div>
                  <h1></h1>

                  ';}?>
                <!-- /.post -->
                </div>
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