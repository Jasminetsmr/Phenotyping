<?php 
    include_once "../../_db/db_conn.php";

    $success = "";
    
    if(isset($_POST['hapus'])) {
        
        $id = $_POST["id_user"];
        // echo "aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa".$id;
        $query = "DELETE FROM `users` WHERE `id_user` = $id ;";

        if(mysqli_query($mysqli,$query)){
            $success = "[Entry [id: ".$id."] berhasil dihapus.]";
        } else {
            $success =  "[Gagal menghapus entri data.]";
            echo "Error: ".mysqli_error($mysqli);
        }
    } 
?>