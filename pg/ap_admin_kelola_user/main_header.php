<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       <b>Pengaturan</b> Entri Data
        <!-- <small>Smart Greenhouse IPB</small> -->
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">
            <div class="box-header my-0">
              <h3 class="text-bold text-center" style="margin-top: 0px !important; margin-bottom: 0px !important;">
                Kelola Data Greenhouse
              </h3>
              <!--  -->
            </div>
            <?php $now =  date("Y-m-d H:i:s");?>
            <div class="box-header my-0">
              <a href='#addEntryModal' class='btn btn-primary pull-right' data-toggle='modal'>
                <i class='glyphicon glyphicon-plus' data-toggle='tooltitp' title='Add'></i>
                <span class="text-bold">Tambah</span>
              </a>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              
               
              <table id="maintable" class="table table-striped">
                
                <!-- columns -->
                <thead>
                    <tr>
                      <th>Id</th>
                      <th>Nama Lokasi</th>
                      <th>Luas Lokasi</th>
                      <th>Dimensi Lokasi</th>
                      <th>Latitude</th>
                      <th>Longitde</th>
                      <th>Deskripsi</th>                    
                      <th>Edit | Hapus</th>
                     
                    </tr>
                </thead>

                <!-- data -->
                <tbody>
                
                <?php $query = "SELECT *
                                FROM `ds_lokasi`
                                ;";
                    $operasi = mysqli_query($mysqli,$query);
                    while($row = mysqli_fetch_array($operasi)) {
                        
                        echo "<tr style='line-height: 25px !important; min-height !important: 25px; height: 25px !important;'> 
                        <td>".$row['id_lokasi']."</td>
                        <td>".$row['nama_lokasi']."</td>
                        <td>".$row['luas_lokasi']."</td>
                        <td>".$row['dimensi_lokasi']."</td>
                        <td>".$row['lat_lokasi']."</td>
                        <td>".$row['long_lokasi']."</td>
                        <td>".$row['deskripsi_lokasi']."</td>";

                        $row['nama_lokasi'] = str_replace(" ", "_", $row['nama_lokasi']);
                        $row['luas_lokasi'] = str_replace(" ", "_", $row['luas_lokasi']);
                        $row['dimensi_lokasi'] = str_replace(" ", "_", $row['dimensi_lokasi']);
                        $row['lat_lokasi'] = str_replace(" ", "_", $row['lat_lokasi']);
                        $row['long_lokasi'] = str_replace(" ", "_", $row['long_lokasi']);   
                        $row['deskripsi_lokasi'] = str_replace(" ", "_", $row['deskripsi_lokasi']);                        
                        $arr = array($row['id_lokasi'], $row['nama_lokasi'], $row['luas_lokasi'], $row['dimensi_lokasi'],
                                      $row['lat_lokasi'], $row['long_lokasi'], $row['deskripsi_lokasi']);
                        $data = join("&", $arr);

                        echo "
                        <td>
                          <a href='#editEntryModal' class='edit' data-toggle='modal' data-target='#editEntryModal' data-entry=".$data.">
                            <i class='glyphicon glyphicon-edit' data-toggle='tooltitp' title='Ubah'></i>
                          </a>

                          <a href='#deleteEntryModal' class='delete' data-toggle='modal' data-target='#deleteEntryModal' data-entry=".$data.">
                            <i class='glyphicon glyphicon-trash' data-toggle='tooltitp' title='Hapus'></i>
                          </a>
                        </td>
                        </tr>";
                        
                      }
                ?>
                  
                </tbody>
              </table>
              
              <!-- Modal Tambah -->
              <div class="modal fade" id="addEntryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <h4 class="modal-title" id="exampleModalLabel">Isi Data</h4>
                    </div>
                    <div class="modal-body">

                      <!-- add entry form -->

                      <form action="index.php" method="post" id="addentryform">
                        <table class="table">                
                          <!-- columns -->
                          <tbody>
                              <tr>
                                <td><label for="id" class="control-label">Id:</label></td>
                                <td><input id="id" name="id" type="text" class="form-control" readonly></td>
                              </tr>
                              <tr>
                                <td><label for="nama" class="control-label">Nama Lokasi:</label></td>
                                <td><input id="nama" name="nama" type="text" class="form-control" ></td>
                              </tr>
                              <tr>
                                <td><label for="luas" class="control-label">Luas Lokasi:</label></td>
                                <td><input id="luas" name="luas" type="text" class="form-control" ></td>
                              </tr>
                              <tr>
                                <td><label for="dimensi" class="control-label">Dimensi Lokasi:</label></td>
                                <td><input id="dimensi" name="dimensi" type="text" class="form-control" ></td>
                              </tr>
                              <tr>
                                <td><label for="latitude" class="control-label">Latitude:</label></td>
                                <td><input id="latitude" name="latitude" type="text" class="form-control" ></td>
                              </tr>
                              <tr>
                                <td><label for="longitude" class="control-label">Longitude:</label></td>
                                <td><input id="longitude" name="longitude" type="text" class="form-control" ></td>
                              </tr>   
                              <tr>
                                <td><label for="deskripsi" class="control-label">Deskripsi:</label></td>
                                <td><input id="deskripsi" name="deskripsi" type="text" class="form-control" ></td>
                              </tr>                                                          
                          </tbody>
                        </table>
                      </form>


                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                      <button type="submit" form="addentryform" name="tambah" value="tambah" class="btn btn-primary">Tambah</button>
                    </div>
                  </div>
                </div>
              </div>


              <!-- Modal Ubah -->
              <div class="modal fade" id="editEntryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <h4 class="modal-title" id="exampleModalLabel">New message</h4>
                    </div>
                    <div class="modal-body">

                      <!-- edit entry form -->

                      <form action="index.php" method="post" id="editentryform">
                        <table class="table">                
                          <!-- columns -->
                          <tbody>
                              <tr>
                                <td><label for="id" class="control-label">Id:</label></td>
                                <td><input id="id" name="id" type="text" class="form-control" readonly></td>
                              </tr>
                              <tr>
                                <td><label for="nama_user" class="control-label">Nama Pengguna:</label></td>
                                <td><input id="nama_user" name="nama_user" type="text" class="form-control" ></td>
                              </tr>
                              <tr>
                                <td><label for="username" class="control-label">Username:</label></td>
                                <td><input id="username" name="username" type="text" class="form-control" ></td>
                              </tr>
                              <tr>
                                <td><label for="password" class="control-label">Password:</label></td>
                                <td><input id="password" name="password" type="text" class="form-control" ></td>
                              </tr>
                              <tr>
                                <td><label for="level_user" class="control-label">Level User:</label></td>
                                <td><input id="level_user" name="level_user" type="text" class="form-control" ></td>
                              </tr>
                              <tr>
                                <td><label for="tanggal" class="control-label">Tanggal Didaftarkan:</label></td>
                                <td><input id="tanggal" name="tanggal" type="text" class="form-control" ></td>
                              </tr>                                                            
                          </tbody>
                        </table>
                      </form>


                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                      <button type="submit" form="editentryform" name="ubah" value="ubah" class="btn btn-primary">Ubah</button>
                    </div>
                  </div>
                </div>
              </div>                          
              
              <!-- Modal Hapus -->
              <div class="modal fade" id="deleteEntryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <h4 class="modal-title" id="exampleModalLabel">New message</h4>
                    </div>
                    <div class="modal-body">

          
                      <form action="index.php" method="post" id="deleteentryform">
                      <table class="table">                
                          <!-- columns -->
                          <tbody>
                              <tr>
                                <td><label for="id" class="control-label">Id:</label></td>
                                <td><input id="id" name="id" type="text" class="form-control" ></td>
                              </tr>
                              <tr>
                                <td><label for="nama" class="control-label">Nama Lokasi:</label></td>
                                <td><input id="nama" name="nama" type="text" class="form-control" disabled readonly></td>
                              </tr>
                              <tr>
                                <td><label for="luas" class="control-label">Luas Lokasi:</label></td>
                                <td><input id="luas" name="luas" type="text" class="form-control" id="tipe_operasi" disabled readonly></td>
                              </tr>
                              <tr>
                                <td><label for="dimensi" class="control-label">Dimensi Lokasi:</label></td>
                                <td><input id="dimensi" name="dimensi" type="text" class="form-control" disabled readonly></td>
                              </tr>
                              <tr>
                                <td><label for="latitude" class="control-label">Latitude:</label></td>
                                <td><input id="latitude" name="latitude" type="text" class="form-control" disabled readonly></td>
                              </tr>
                              <tr>
                                <td><label for="longitude" class="control-label">Longitude:</label></td>
                                <td><input id="longitude" name="longitude" type="text" class="form-control" disabled readonly></td>
                              </tr>             
                              <tr>
                                <td><label for="deskripsi" class="control-label">Deskripsi:</label></td>
                                <td><input id="deskripsi" name="deskripsi" type="text" class="form-control" disabled readonly></td>
                              </tr>                                                 
                          </tbody>
                        </table>
                      </form>



                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                      <button type="submit" form="deleteentryform" name="hapus" value="hapus" class="btn btn-danger">Hapus</button>
                    </div>
                  </div>
                </div>
              </div>

            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->


        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->


<script>
//Script - Ketika ditekan hilang
 $('editentryform').submit(function(e) {
    e.preventDefault();
    // Coding
    $('editEntryModal').modal('hide');
    return false;
});

$('deleteentryform').submit(function(e) {
    e.preventDefault();
    // Coding
    $('deleteEntryModal').modal('hide');
    return false;
});

// Javascript untuk modal ubah
$('#editEntryModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget); // Button that triggered the modal
  var entry = button.data('entry'); // Extract info from data-* attributes
  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.

  var array = entry.split('&')
  var id = array[0]
  var nama_lokasi = array[1]
  var luas_lokasi = array[2]
  var dimensi_lokasi = array[3]
  var latitude = array[4]
  var longitude = array[5]
  var deskripsi = array[6]

  var modal = $(this);
  modal.find('.modal-title').text("Ubah Data pada Id = " + id );
  modal.find(".modal-body input[id='id']").val(id);
  modal.find(".modal-body input[id='nama_lokasi']").val(nama_user);
  modal.find(".modal-body input[id='luas_lokasi']").val(luas_lokasi);
  modal.find(".modal-body input[id='dimensi_lokasi']").val(dimensi_lokasi);
  modal.find(".modal-body input[id='latitude']").val(latitude);
  modal.find(".modal-body input[id='longitude']").val(longitude);  
  modal.find(".modal-body input[id='deskripsi']").val(deskripsi);  
})

$('#deleteEntryModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget); // Button that triggered the modal
  var entry = button.data('entry'); // Extract info from data-* attributes
  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
  
  var array = entry.split('&')
  var id = array[0]
  var nama_lokasi = array[1]
  var luas_lokasi = array[2]
  var dimensi_lokasi = array[3]
  var latitude = array[4]
  var longitude = array[5]
  var deskripsi = array[6]
  
  var modal = $(this);
  modal.find('.modal-title').text("Hapus Data pada Id = " + id );
  modal.find(".modal-body input[id='id']").val(id);
  modal.find(".modal-body input[id='nama_lokasi']").val(nama_user);
  modal.find(".modal-body input[id='luas_lokasi']").val(luas_lokasi);
  modal.find(".modal-body input[id='dimensi_lokasi']").val(dimensi_lokasi);
  modal.find(".modal-body input[id='latitude']").val(latitude);
  modal.find(".modal-body input[id='longitude']").val(longitude);  
  modal.find(".modal-body input[id='deskripsi']").val(deskripsi);  
})
</script>