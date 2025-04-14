<!-- Content Header (Page header) -->
<section class="content-header">
<?php require_once "../../_db/db_conn.php";
$waktu = "$_GET[waktu]";

// Attempt select query execution
$sql = "SELECT ds_foto.*, ds_layout.kode FROM ( ds_foto INNER JOIN ds_layout ON ds_foto.id_layout = ds_layout.id_layout ) WHERE waktu_foto='$waktu'";
$result = $mysqli->query($sql);
while($row = $result->fetch_array()){
echo '      
      <h1>
        <b>Detail Data</b> Citra Tanaman '. $row['kode'].'</small>
      </h1>
    </section>

    <!-- <div class="pad margin no-print">
      <div class="callout callout-info" style="margin-bottom: 0!important;">
        <h4><i class="fa fa-info"></i> Note:</h4>
        This page has been enhanced for printing. Click the print button at the bottom of the invoice to test.
      </div>
    </div> -->

    <!-- Main content -->
    <section class="invoice">
      <!-- title row -->
    

      <div class="row">
        <!-- accepted payments column -->
        <div class="col-xs-6">
          <p class="lead">Foto Asli</p>';
          echo '<img src="../../citra/before/'. $row['nama_foto'].'" alt="taneman" width=475>

            <!-- <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
              Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles, weebly ning heekya handango imeem plugg
              dopplr jibjab, movity jajah plickers sifteo edmodo ifttt zimbra.
            </p>
            -->
        </div>
        <!-- /.col -->
        <div class="col-xs-6">
          <p class="lead">Deeplearn</p>';
          echo '<img src="../../citra/after/'. $row['nama_foto'].'" alt="threshold" width=475>

        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
      <h1></h1>
      <div class="row">
        <!-- accepted payments column -->
        <!-- /.col -->
        <div class="col-xs-6">';
          echo '<p class="lead"><b>Data diambil pada '. $row['waktu_foto'].'</b></p>

          <div class="table-responsive">
            <table class="table">
              <tr>
                <th>Jumlah Lubang Kosong:</th>';
                echo '<td>'. $row['jml_kosong'].' Lubang</td>
              </tr>  
              <tr>
                <th>Jumlah Tanaman Sakit:</th>';
                echo '<td>'. $row['jml_sakit'].' Tanaman</td>
              </tr>
              <tr>
                <th>Jumlah Tanaman Sehat:</th>';
                echo '<td>'. $row['jml_sehat'].' Tanaman</td>
              </tr>
            </table>
          </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
      <!-- this row will not appear when printing -->
    </section>
  ';}?>
    <!-- /.content -->