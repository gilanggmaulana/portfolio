<?php
session_start();
//kamu
$id_produk = $_GET['id'];


//ya kamu
if (isset($_SESSION['keranjang'][$id_produk])) 
{
	$_SESSION['keranjang'][$id_produk]+=1;
}
//selain itu
else
{
	$_SESSION['keranjang'][$id_produk] = 1;
}



//echo "<pre>";
//print_r($_SESSION);
//echo "</pre>";

//
echo "<script>alert('produk anda telah masuk');</script>";
echo "<script>location='keranjang.php';</script>";
?>