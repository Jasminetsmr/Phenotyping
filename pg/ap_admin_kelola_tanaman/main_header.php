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
                Kelola Data Tanaman
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
                      <th>Jenis Tanaman</th>
                      <th>Varietas Tanaman</th>
                      <th>Tipe Budidaya</th>
                      <th>Tipe Irigasi</th>
                      <th>Penanggung Jawab</th> 
                      <th>Deskripsi</th>           
                      <th>Edit | Hapus</th>

                    </tr>
                </thead>

                <!-- data -->
                <tbody>
                
                <?php $query = "SELECT *
                                FROM `ds_tanaman`
                                ;";
                    $operasi = mysqli_query($mysqli,$query);
                    while($row = mysqli_fetch_array($operasi)) {
                        
                        echo "<tr style='line-height: 25px !important; min-height !important: 25px; height: 25px !important;'> 
                        <td>".$row['id_tanaman']."</td>
                        <td>".$row['jenis_tanaman']."</td>
                        <td>".$row['varietas_tanaman']."</td>
                        <td>".$row['populasi']."</td>
                        <td>".$row['tipe_budidaya']."</td>
                        <td>".$row['tipe_irigasi']."</td>
                        <td>".$row['deskripsi_budidaya']."</td>";

                        $row['jenis_tanaman'] = str_replace(" ", "_", $row['jenis_tanaman']);
                        $row['varietas_tanaman'] = str_replace(" ", "_", $row['varietas_tanaman']);
                        $row['populasi'] = str_replace(" ", "_", $row['populasi']);
                        $row['tipe_budidaya'] = str_replace(" ", "_", $row['tipe_budidaya']);
                        $row['tipe_irigasi'] = str_replace(" ", "_", $row['tipe_irigasi']); 
                        $row['deskripsi_budidaya'] = str_replace(" ", "_", $row['deskripsi_budidaya']);                        
                        $arr = array($row['id_tanaman'], $row['jenis_tanaman'], $row['varietas_tanaman'], $row['populasi'],
                                      $row['tipe_budidaya'], $row['tipe_irigasi'], $row['deskripsi_budidaya']);
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
                                <td><label for="jenis" class="control-label">Jenis Tanaman:</label></td>
                                <td><input id="jenis" name="jenis" type="text" class="form-control" ></td>
                              </tr>
                              <tr>
                                <td><label for="varietas" class="control-label">Varietas Tanaman:</label></td>
                                <td><input id="varietas" name="varietas" type="text" class="form-control" ></td>
                              </tr>
                              <tr>
                                <td><label for="populasi" class="control-label">Populasi:</label></td>
                                <td><input id="populasi" name="populasi" type="text" class="form-control" ></td>
                              </tr>
                              <tr>
                                <td><label for="budidaya" class="control-label">Tipe Budidaya:</label></td>
                                <td><input id="budidaya" name="budidaya" type="text" class="form-control" ></td>
                              </tr>
                              <tr>
                                <td><label for="irigasi" class="control-label">Tipe Irigasi:</label></td>
                                <td><input id="irigasi" name="irigasi" type="text" class="form-control" ></td>
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
                                <td><label for="jenis" class="control-label">Jenis Tanaman:</label></td>
                                <td><input id="jenis" name="jenis" type="text" class="form-control" ></td>
                              </tr>
                              <tr>
                                <td><label for="varietas" class="control-label">Varietas Tanaman:</label></td>
                                <td><input id="varietas" name="varietas" type="text" class="form-control" ></td>
                              </tr>
                              <tr>
                                <td><label for="populasi" class="control-label">Populasi:</label></td>
                                <td><input id="populasi" name="populasi" type="text" class="form-control" ></td>
                              </tr>
                              <tr>
                                <td><label for="budidaya" class="control-label">Tipe Budidaya:</label></td>
                                <td><input id="budidaya" name="budidaya" type="text" class="form-control" ></td>
                              </tr>
                              <tr>
                                <td><label for="irigasi" class="control-label">Tipe Irigasi:</label></td>
                                <td><input id="irigasi" name="irigasi" type="text" class="form-control" ></td>
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
                                <td><label for="jenis" class="control-label">Jenis Tanaman:</label></td>
                                <td><input id="jenis" name="jenis" type="text" class="form-control" disabled readonly></td>
                              </tr>
                              <tr>
                                <td><label for="varietas" class="control-label">Varietas Tanaman:</label></td>
                                <td><input id="varietas" name="varietas" type="text" class="form-control" id="tipe_operasi" disabled readonly></td>
                              </tr>
                              <tr>
                                <td><label for="populasi" class="control-label">Populasi:</label></td>
                                <td><input id="populasi" name="populasi" type="text" class="form-control" disabled readonly></td>
                              </tr>
                              <tr>
                                <td><label for="budidaya" class="control-label">Tipe Budidaya:</label></td>
                                <td><input id="budidaya" name="budidaya" type="text" class="form-control" disabled readonly></td>
                              </tr>
                              <tr>
                                <td><label for="irigasi" class="control-label">Tipe Irigasi:</label></td>
                                <td><input id="irigasi" name="irigasi" type="text" class="form-control" disabled readonly></td>
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
  var id_tanaman = array[0]
  var jenis_tanaman = array[1]
  var varietas_tanaman = array[2]
  var populasi = array[3]
  var tipe_budidaya = array[4]
  var tipe_irigasi = array[5]  
  var deskripsi = array[6]  

  var modal = $(this);
  modal.find('.modal-title').text("Ubah Data pada Id = " + id_tanaman );
  modal.find(".modal-body input[id='id']").val(id_tanaman);
  modal.find(".modal-body input[id='jenis_tanaman']").val(jenis_tanaman);
  modal.find(".modal-body input[id='varietas_tanaman']").val(varietas_tanaman);
  modal.find(".modal-body input[id='populasi']").val(populasi);
  modal.find(".modal-body input[id='tipe_budidaya']").val(tipe_budidaya);
  modal.find(".modal-body input[id='tipe_irigasi']").val(tipe_irigasi);  
  modal.find(".modal-body input[id='deskripsi_tanaman']").val(deskripsi_tanaman); 
})

$('#deleteEntryModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget); // Button that triggered the modal
  var entry = button.data('entry'); // Extract info from data-* attributes
  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
  
  var array = entry.split('&')
  var id_tanaman = array[0]
  var jenis_tanaman = array[1]
  var varietas_tanaman = array[2]
  var populasi = array[3]
  var tipe_budidaya = array[4]
  var tipe_irigasi = array[5]  
  var deskripsi = array[6]  
  
  var modal = $(this);
  modal.find('.modal-title').text("Hapus Data pada Id = " + id );
  modal.find(".modal-body input[id='id']").val(id_tanaman);
  modal.find(".modal-body input[id='jenis_tanaman']").val(jenis_tanaman);
  modal.find(".modal-body input[id='varietas_tanaman']").val(varietas_tanaman);
  modal.find(".modal-body input[id='populasi']").val(populasi);
  modal.find(".modal-body input[id='tipe_budidaya']").val(tipe_budidaya);
  modal.find(".modal-body input[id='tipe_irigasi']").val(tipe_irigasi);  
  modal.find(".modal-body input[id='deskripsi_tanaman']").val(deskripsi_tanaman); 
})
</script>