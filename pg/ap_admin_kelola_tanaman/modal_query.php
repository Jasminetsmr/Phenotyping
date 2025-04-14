<?php
    include_once "../../_db/db_conn.php";
    $id = $_GET['id_tanaman'];
    $query = "SELECT *
              FROM `ds_tanaman` 
              WHERE id_tanaman=$id ;";
    $operasi = mysqli_query($mysqli,$query);
    $row = mysqli_fetch_array($operasi);
    echo    $row['nama_user']."&".
            $row['username']."&".
            $row['password']."&".
            $row['level_user']."&". \<div class=" ">
            $row['tanggal_dibuat']."&";
            
?>