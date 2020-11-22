<?php
session_start();
$koneksi = new mysqli("localhost","root","","toko_online");

if (empty($_SESSION["keranjang"]) OR !isset($_SESSION["keranjang"])) 
{
	echo "<script>alert('mohon ditunggu pengiriman dari kami, barang akan segera diproses dan dikirimi secepatnya');</script>";
	echo "<script>location='index.php';</script>";
}
?>