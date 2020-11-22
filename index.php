<?php
session_start();
//koneksi ke database
$koneksi = new mysqli("localhost","root","","toko_online");
?>
<!DOCTYPE html>
<html>
<head>
	<title>Arek Kepanjen</title>
	<link rel="stylesheet" href="admin/assets/css/bootstrap.css">
</head>
<body>
	<header>
		<h2>Three Casual Malang</h2>
	</header>
<!--navbar-->
<nav class="navbar navbar-default">
	<div class="container">
		
		<ul class="nav navbar-nav">
			<li><a href="index.php">Home</a></li>
			<li><a href="sneakers.php">Sneakers</a></li>
			
			<li><a href="polo.php">Polo</a></li>
			<li><a href="tracktop.php">Tracktop</a></li>
			
			<li><a href="keranjang.php">Keranjang</a></li>
			<!--jika sudah login(ada session pelanggan)-->
			<?php if (isset($_SESSION["pelanggan"])): ?>
				<li><a href="logout.php">Logout</a></li>
			<!--selain itu blm login-->
			<?php else: ?>	
				<li><a href="login.php">Login</a></li>
			<?php endif ?>	
			
			<li><a href="checkout.php">Checkout</a></li>
		</ul>
	</div>
</nav>


<!--konten-->
<section class="konten">
	<div class="container">
		<h1>Produk Terbaru</h1>

		<div class="row">

			<?php $ambil = $koneksi->query("SELECT * FROM produk ORDER BY ket DESC LIMIT 17 "); ?>
			<?php while($r = $ambil->fetch_assoc()){ ?>
			<div class="col-md-3">
				<div class="thumbnail">
					<img src="foto_produk/<?php echo $r['foto_produk']; ?>">
					<div class="caption">
						<h3><?php echo $r['nama_produk']; ?></h3>
						<h5>Rp. <?php echo number_format ($r['harga_produk']); ?></h5>
						<a href="beli.php?id=<?php echo $r['id_produk']; ?>" class="btn btn-primary">Beli</a>
					</div>
				</div>
			</div>
			<?php } ?>



		</div>
	</div>
</section>	

</body>
</html>