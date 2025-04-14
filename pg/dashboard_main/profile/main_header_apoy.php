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

            <div class="info-box-content">
              <span class="info-box-text">Populasi Tanaman</span>
              <span class="info-box-number">3500 tanaman</span>
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
              <span class="info-box-number">2 jenis</span>
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
              <span class="info-box-number">88 entri</span>
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
              <span class="info-box-number">7 tray</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

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
              <p class="text-muted"> Nursery Green House, Agribusiness and Technology Park, Cikarawang, Dramaga, Bogor </p>
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
              <!-- <li><a href="#timeline" data-toggle="tab">Interpretasi</a></li> -->
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
                      ORDER BY id_foto DESC LIMIT 3";
                    $result_foto = $mysqli->query($sql_foto);
                    foreach($result_foto as $elements) { 
                    echo '
                  <div class="user-block">
                    <img class="img-circle img-bordered-sm" src="../images/admin.png" alt="user image"
                        <span class="username">
                          <a href="../../pg/ap_foto_detail/?waktu='. $elements['waktu_foto'] .'">'. $elements['jenis_tanaman'].' '.$elements['kode']. '</a>
                        </span>
                        <span class="description">'.$elements['waktu_foto'].'</span>
                  </div>
                  <div class="row">
                    <div class="col-sm-4">
                      <img class="img-responsive" src="../../citra/before/'. $elements['nama_foto'] .'" alt="Original" width=400>
                    </div>
                    <div class="col-sm-4">
                      <img class="img-responsive" src="../../citra/after/'. $elements['nama_foto'] .'" alt="Threshold" width=400>
                    </div>
                    <div class="col-sm-4">
                    <div class="table-responsive">
                    <table class="table">
                      <tr>
                        <th>Jumlah Lubang Kosong:</th>';
                        echo '<td>'. $elements['jml_kosong'].' Lubang</td>
                      </tr>  
                      <tr>
                        <th>Jumlah Tanaman Sakit:</th>';
                        echo '<td>'. $elements['jml_sakit'].' Tanaman</td>
                      </tr>
                      <tr>
                        <th>Jumlah Tanaman Sehat:</th>';
                        echo '<td>'. $elements['jml_sehat'].' Tanaman</td>
                      </tr>
                    </table>
                  </div>
                    </div>  
                  </div>
                  <h1></h1>

                  ';}?>
                <!-- /.post -->
                </div>
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