<!-- Content Header (Page header) -->
<section class="content-header">
      <h1>
        <b>Pemrosesan Citra</b> Online
      </h1>
    </section>

    <!-- Main content -->
    
    <section class="content">
    <div class="box box-primary">
        <div class="box-header with-border">
        <div class="row">
        
          <?php require_once "../../_db/db_conn.php";

          // Attempt select query execution
          $sql = "SELECT * FROM ds_foto ORDER BY id_foto DESC LIMIT 1";
          $result = $mysqli->query($sql);
          $row = $result->fetch_array();

          echo '<div class="col-md-4"><img class="img-center" src="../../citra/'. $row['nama_foto'] .'" alt="Foto Terakhir" width=400></div>';
          echo '<div class="col-md-4"><img class="img-center" src="../../citra/threshold/'. $row['nama_foto'] .'" alt="Foto Terakhir" width=400></div>';
          echo '<div class="col-md-4"><img class="img-center" src="../../citra/bitwise/'. $row['nama_foto'] .'" alt="Foto Terakhir" width=400></div>';
          ?>
          
          </div>
          <h1></h1>
<h3 class="profile-username text-center">Pemrosesan Citra Online</h3>

<p class="text-muted text-center"></p>



<!-- <a href="#" class="btn btn-primary btn-block"><b>Capture</b></a>
<a href="#" class="btn btn-success btn-block"><b>Details</b></a> -->
<div class="row">
                <form action="index.php" method="POST" id="combo">
                <div class="col-md-4">
                    <label for="tanaman-dropdown">Tanaman</label>
                    <select class="form-control" id="tanaman-dropdown" name="tanaman-dropdown" style="width: 100%;">
                      <option selected="selected" value="" disabled>Pilih Tanaman</option>
                      <?php
                      $query_tanaman = "SELECT *
                                        FROM `ds_tanaman`;";
                      
                      $list_tanaman = mysqli_query($mysqli, $query_tanaman);

                      while($row_tanaman = mysqli_fetch_array($list_tanaman)) {
                        $id_tanaman = $row_tanaman['id_tanaman'];
                        $jenis_tanaman = $row_tanaman['jenis_tanaman'];
                        echo "<option value=".$id_tanaman.">".$jenis_tanaman."</option>";                      
                      }
                      ?>
                    </select>
                </div>
                <div class="col-md-4">
                  <label for='blok-dropdown'>Blok</label>
                  <select class="form-control" id='blok-dropdown' name="blok-dropdown" style="width: 100%;">
                    <option selected="selected" value="" disabled>Pilih Blok</option>
                    <?php
                    $query_blok = "SELECT blok
                                      FROM `ds_layout`
                                        GROUP BY blok;";
                    
                    $list_blok = mysqli_query($mysqli, $query_blok);

                    while($row_blok = mysqli_fetch_array($list_blok)) {
                      $blok = $row_blok['blok'];              
                      echo "<option value=".$blok.">".$blok."</option>";                      
                    }
                    ?>
                  </select>
                </div>
                <div class="col-md-4">
                  <label for='tray-dropdown'>Tray</label>
                  <select class="form-control" id='tray-dropdown' name="tray-dropdown" style="width: 100%;">
                    <option selected="selected" value="" disabled>Pilih Tray</option>
                    <?php
                    $query_tray = "SELECT tray
                                      FROM `ds_layout`
                                        GROUP BY tray;";
                    
                    $list_tray = mysqli_query($mysqli, $query_tray);

                    while($row_tray = mysqli_fetch_array($list_tray)) {
                      $tray = $row_tray['tray'];              
                      
                      echo "<option value=".$tray.">".$tray."</option>";                      
                    }
                    ?>
                  </select>
                </div>
              </div>
              <h1></h1>
              <div class="row">
              <div class="col-md-4">      
              <label for='ketinggian'>Ketinggian Pemotretan (cm)</label>
                <input type="text" id="ketinggian" name="ketinggian" style="width: 100%">
                  </div>
                <div class="col-md-4">  
                  <label for="tanggal">Tanggal Foto Diambil</label>
                    <input type="date" id="tanggal" name="tanggal" style="width: 100%">
                  </div>
                <div class="col-md-4">  
                  <label for="waktu">Waktu Foto Diambil</label>
                    <input type="time" id="waktu" name="waktu" style="width: 100%">
                  </div>  
              </div>  
              <h1></h1>  
              <div class="row">
                <div class="col-md-12">   
                <label for="upload">Unggah File </label>
                  <input type="file" id="file-upload" name="file-upload">
                
                </div>  
              </div>
              <div class="row">
                <div class="col-md-2">
                    <br>
                    <button type="submit" form="combo" name="combo" value="combo" class="btn btn-primary btn-lg" style="width: 100%"><i class="fa fa-retweet"></i> Proses</button>
                    </div>
              
                    <div class="col-md-2">
                      <br>
                      <?php echo '<a href="../../pg/ap_foto_detail/?tanggal='. $row['tanggal_foto'] .'&waktu='. $row['waktu_foto'] .'';?>" class="btn btn-lg btn-success"><i class="fa fa-search"></i>   Detail</a>
                   </div>
                    
                </form>
              </div>
              <?php 
                if ($_SERVER["REQUEST_METHOD"] == "POST")  {
                    $dd_tanaman = $_POST["tanaman-dropdown"];
                    $dd_blok = $_POST["blok-dropdown"];
                    $dd_tray = $_POST["tray-dropdown"];
                    $in_tinggi = $_POST["ketinggian"];
                    $in_tanggal = $_POST["tanggal"];
                    $in_waktu = $_POST["waktu"];
                    $up_file = $_POST["file-upload"];

                    
                    $begin = time();
                    $command = "python processing.py " . escapeshellarg($dd_tanaman) . " " . escapeshellarg($dd_blok) . " " . escapeshellarg($dd_tray). " " . escapeshellarg($in_tinggi) . " " . escapeshellarg($in_tanggal) . " " . escapeshellarg($in_waktu) . " " . escapeshellarg($in_tinggi) . " " . escapeshellarg($in_tanggal) . " " . escapeshellarg($up_file);
                    $output = shell_exec($command);
                    while (1) {
                        if (time() - $begin < 15) {
                            if ($output == "") {
                                $output = shell_exec($command);
                            } else {
                                echo "<p class='lead text-center'>Berhasil mengambil gambar</p></div>";
                                if ($output != "end") {
                                  echo "<div class='text-center mt-4'>Berhasil menyimpan ke dalam database</div>";
                                  break;
                                }
                                else {
                                    echo "Gagal menyimpan ke dalam database.";
                                }
                            }
                        } 
                        else {
                            echo "<p class='lead text-center'>Gagal mengambil gambar gambar</p></div>";
                            break;                            
                        }
                    } 
                }
                ?>
</div>
    </section>
    <!-- /.content -->