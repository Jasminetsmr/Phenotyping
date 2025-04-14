<?php 
    include_once "../../_db/db_conn.php";

    $query_id = "SELECT * FROM users ORDER BY users.id_user ASC;
                ";
    $result = mysqli_query($mysqli,$query_id);
    $id = "99";
    while($row = mysqli_fetch_array($result)) {
        $id = $row['id_user'];
    }
    $id = $id+1;
    
    $success = "";
    
    if(isset($_POST['tambah'])) {
        $id = $_POST["id_user"];
        $nama_user = $_POST["nama_user"];
        $username = $_POST["username"];
        $password = $_POST["password"];
        $level_user = $_POST["level_user"];
        $tanggal = $_POST["tanggal_dibuat"];
        
        if(isset($_POST['id'])) {
        $query = "INSERT INTO ``(`nama_user`, `username`,`password`,`level_user`,`tanggal_dibuat`)
                    VALUES ('$nama_user','$username','$password','$level_user','$tanggal');";
        }

        if(mysqli_query($mysqli,$query)){
            $success = "[Perubahan entry berhasil disimpan.]";
        } else {
            $success =  "[Gagal menyimpan perubahan.]";
            echo "Error: ".mysqli_error($mysqli);
        }
    }
?>