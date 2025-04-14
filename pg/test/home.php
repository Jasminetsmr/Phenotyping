<?php 

$tanggal = date ("Y-m-d");
$jam = date ("h:i:s");

$now = new DateTime();
$tanggal_full = $now->format("s");

echo $tanggal;
    echo "<br>";
    echo "$tanggal_full";
echo date("l");
    echo "<br>";
echo $jam;

?>