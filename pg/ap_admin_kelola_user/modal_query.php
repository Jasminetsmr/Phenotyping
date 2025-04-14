<?php
    include_once "../../_db/db_conn.php";
    $id = $_GET['id_user'];
    $query = "SELECT *
              FROM `users` 
              WHERE id_users=$id ;";
    $operasi = mysqli_query($mysqli,$query);
    $row = mysqli_fetch_array($operasi);
    echo    $row['nama_user']."&".
            $row['username']."&".
            $row['password']."&".
            $row['level_user']."&". \<div class=" ">
            $row['tanggal_dibuat']."&";
            
?>