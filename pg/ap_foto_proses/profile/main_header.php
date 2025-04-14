<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Foto Tanaman 
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-md-4">
          <!-- About Me Box -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Informasi Budidaya</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <strong><i class="fa fa-book margin-r-5"></i>Jenis Tanaman</strong>

              <p class="text-muted">
                Tanaman yang dibudidayakan pada SMART Greenhouse adalah Purwoceng. Tanaman ini merupakan tanaman yang berasal dari dataran tinggi Dieng, Jawa Tengah.
              </p>

              <hr>

              <strong><i class="fa fa-map-marker margin-r-5"></i>Lokasi</strong>
              <p class="text-muted">SMART Greenhouse, Lab Lapangan Leuikopo, IPB University</p>

              <hr>


              <hr>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-8">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#timeline" data-toggle="tab">Timeline</a></li>
            </ul>
            <div class="tab-content">
              <!-- /.tab-pane -->
              <?php include_once "../../_db/db_conn.php"; ?>
              <?php  $sql = "SELECT * FROM db_monitoring_foto WHERE kode_tanaman = 'A61' ORDER by foto_tanggal DESC";?>  
              <?php
                if($result = $mysqli->query($sql)){
                  if($result->num_rows > 0){
                    echo "Tanaman";
                      while($row = $result->fetch_array()){
                          
              ?>
                <div class="active tab-pane" id="timeline">
                <!-- The timeline -->
                <ul class="timeline timeline-inverse">
                  <!-- timeline time label -->
                  <li class="time-label">
                        <span class="bg-green">
                          Foto Tanggal <?php echo "$row[foto_tanggal]";?>
                        </span>
                  </li>
                  <!-- /.timeline-label -->
                  <!-- timeline item -->
                  <li>
                    <i class="fa fa-envelope bg-blue"></i>

                    <div class="timeline-item">
                      <span class="time"><i class="fa fa-clock-o"></i><?php echo "$row[foto_tanggal]";?></span>

                      <h3 class="timeline-header"><a href="#">Foto Tanaman <?php echo "$row[kode_tanaman]";?></a></h3>
                      <div class="timeline-footer">
                        <img class="img-responsive" widht = src="<?php echo "../../$row[foto_folder]/$row[foto_nama]";?>" alt="Photo">
                      </div>
                    </div>
                  </li>
                  <!-- END timeline item -->
                </ul>
              </div>
              <?php
                      }
                    echo "";
                        $result->free();
                  } 
                }
              ?>
              

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

    