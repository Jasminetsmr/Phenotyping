<?php 
    include_once "../../_db/db_conn.php";

    $success = "";

    if(isset($_POST['ubah'])) {
        $id = $_POST["id_user"];
        $nama_user = $_POST["nama_user"];
        $username = $_POST["username"];
        $password = $_POST["password"];
        $level_user = $_POST["level_user"];
        $tanggal = $_POST["tanggal"];

        $nama_user = str_replace("_", " ", $nama_user);
        // $username = str_replace("_", " ", $username);
        $password = str_replace("_", " ", $password);
        $level_user = str_replace("_", " ", $level_user);
        $tanggal= str_replace("_", " ",  $tanggal);        
        
        $query = "UPDATE `users` 
                    SET `nama_user` = '$nama_user',
                        `username` = '$username',
                        `password` = '$password',
                        `level_user` = '$level_user',
                        `tanggal_dibuat` = '$tanggal'                                                                    
                    WHERE `id_user` = $id;
                    ";

        if(mysqli_query($mysqli,$query)){
            $success = "[Perubahan entry [id: ".$id."] berhasil disimpan.]";
        } else {
            $success =  "[Gagal menyimpan perubahan.]";
            echo "Error: ".mysqli_error($mysqli);
        }
    } 
?>