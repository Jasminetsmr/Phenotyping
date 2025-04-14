<!-- Content Header (Page header) -->
<?php include_once "../../_db/db_conn.php"; ?>
    <section class="content-header">
      <h1>
        <b>Sistem Monitoring</b> Tanaman
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
        
      
        <!-- /.col -->
        <div class="col-md-12">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#info" data-toggle="tab">Informasi</a></li>
              <li><a href="#table" data-toggle="tab">Table</a></li>
            </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="info">
                <!-- Post -->
              
                <!-- Post -->
                <div class="post">
                  
                  <!-- /.user-block -->
                  <?php  require_once "../../_db/db_conn.php";

                          // Attempt select query execution
                          $sql = "SELECT
                          ds_foto.*,
                          ds_layout.kode,
                          ds_tanaman.jenis_tanaman,ds_tanaman.tanggal_semai
                        FROM
                          (( ds_foto
                          INNER JOIN ds_layout ON ds_foto.id_layout = ds_layout.id_layout
                          )
                            INNER JOIN ds_tanaman ON ds_layout.id_tanaman = ds_tanaman.id_tanaman)
                            ORDER BY id_foto DESC";
                          $result = $mysqli->query($sql);
                          echo '
                  <div class="row margin-bottom">';
                  foreach($result as $elements) { echo' 
                   <div class="col-sm-3"> 
                     <!-- box start -->
                     
                        <div class="box box-info">
                          <div class="box-header with-border">';
                            echo '<h3 class="box-title">'. $elements['jenis_tanaman'].' '. $elements['kode'] .'</h3>';
                            echo '</div>
                        <!-- /.box-header -->

                        <!-- box-body start -->
                        <form class="form-horizontal"> 
                          <div class="box-body">';
                          echo "<img src=../../citra/before/" . $elements['nama_foto']. " alt='Purwoceng' width='225'>";
                          echo '<h3>'. $elements['waktu_foto'] .'</h3>';
                          echo '
                          </div> 
                          <!-- /.box-body -->

                          <!-- box-footer -->
                          <div class="box-footer"><div class="btn-group">';
                            echo '<a href="../../pg/ap_foto_detail/?waktu='. $elements['waktu_foto'] .'" class="btn btn-info "><i class="fa fa-eye"></i> View</a>';
                            echo '<button type="submit" class="btn btn-info "><i class="fa fa-edit"></i> Edit</button>
                            <button type="submit" class="btn btn-info "><i class="fa fa-trash"></i> Delete</button>
                            </div>
                          </div>
                          <!-- /.box-fo                                                                                                                                                                                                                                                                                                                                                                                                                           oter -->
                        </form>
                      </div>
                          
                    <!-- /.col -->
                  </div>';}
                  ?>
                  <!-- /.row-->
                  </div>
                </div>
                <!-- /.post -->
              </div>
            
              <!-- /.tab-pane -->
  <div class="tab-pane" id="table">
    <!-- timeline time label -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">
            <!-- /.box-header -->
              <table id="example2" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Hasil</th>
                    <th>Tanaman</th>
                    <th>Waktu Foto</th>
                    <th>Jumlah Kosong</th>
                    <th>Jumlah Sakit</th>
                    <th>Jumlah Sehat</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach($result as $elements) { echo"
                  <tr>
                    <td>".$elements['id_foto']."</td>
                    <td><img src=../../citra/before/" . $elements['nama_foto']. " alt='Purwoceng' width='225'></td>
                    <td>".$elements['jenis_tanaman'].' '. $elements['kode'] ."</td>
                    <td>".$elements['waktu_foto']."</td>
                    <td>".$elements['jml_kosong']."</td>
                    <td>".$elements['jml_sakit']."</td>
                    <td>".$elements['jml_sehat']."
                    <td>"; 
                      echo '<a href="../../pg/ap_foto_detail/?waktu='. $elements['waktu_foto'] .'" class="btn btn-info "><i class="fa fa-eye"></i> View</a>';
                      echo '<button type="submit" class="btn btn-info "><i class="fa fa-edit"></i> Edit</button>';
                      echo '<button type="submit" class="btn btn-info "><i class="fa fa-trash"></i> Delete</button>
                  </tr>
                  ';}?>
                </tbody>
              </table>
        </div>

              <!-- /.tab-pane -->
      </div>
                  </section>  
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    </section>
    <!-- /.content -->