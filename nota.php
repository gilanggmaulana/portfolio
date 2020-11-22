<?php
$koneksi = new mysqli("localhost","root","","toko_online");
		if (empty($_SESSION["keranjang"]) OR !isset($_SESSION["keranjang"]))
?>
<!DOCTYPE html>
<html>
<head>
	<title>Nota Pembelian</title>
	<link rel="stylesheet" href="admin/assets/css/bootstrap.css">
</head>
<body>

<nav class="navbar navbar-default">
	<div class="container">
		
		<ul class="nav navbar-nav">
			<li><a href="index.php">Home</a></li>
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

<section class="konten">
	<div class="container">



		<!--nota disini copas saja dari nota yang ada diadmin-->
		<h2>Detail Pembelian</h2>
<?php
$ambil = $koneksi->query("SELECT * FROM pembelian JOIN pelanggan ON pembelian.id_pelanggan=pelanggan.id_pelanggan WHERE pembelian.id_pembelian='$_GET[id]'");
$detail = $ambil->fetch_assoc();
?>


<div class="row">
	<div class="col-md-4">
		<h3>Pembelian</h3>
		<strong>No. Pembelian: <?php echo $detail['id_pembelian'] ?></strong><br>
		Tanggal: <?php echo $detail['tanggal_pelanggan']; ?><br>
		Total: Rp. <?php echo number_format($detail['total_pelanggan']); ?>
	</div>
	<div class="col-md-4">
		<h3>Pelanggan</h3>
		<strong><?php echo $detail['nama_pelanggan']?></strong><br>
		<p>
			<?php echo $detail['telepon_pelanggan']; ?><br>
			<?php echo $detail['email_pelanggan']?>
		</p>
	</div>
	<div>
		<h3>Pengiriman</h3>
		<strong><?php echo $detail['nama_kota']; ?></strong><br>
		Ongkos Kirim: Rp. <?php echo number_format($detail['tarif']); ?><br>
		Alamat: <?php echo $detail['alamat_pengiriman']; ?> 
	</div>
</div>

<table class="table table-bordered">
	<thead>
		<tr>
			<th>No</th>
			<th>Nama Produk</th>
			<th>Harga</th>
			<th>Berat</th>
			<th>Jumlah</th>
			<th>SubBerat</th>
			<th>SubTotal</th>
		</tr>
	</thead>
	<tbody>
		<?php $nomor=1; ?>
		<?php $ambil=$koneksi->query("SELECT * FROM pembelian_produk WHERE id_pembelian='$_GET[id] '"); ?>
		<?php while($pecah=$ambil->fetch_assoc()){ ?>
		<tr>
			<td><?php echo $nomor; ?></td>
			<td><?php echo $pecah['nama']; ?></td>
			<td>Rp. <?php echo number_format($pecah['harga']); ?></td>
			<td><?php echo $pecah['berat'] ; ?>Gram</td>
			<td><?php echo $pecah['jumlah'] ; ?></td>
			<td><?php echo $pecah['subberat'] ; ?>Gram</td>
			<td>Rp. <?php echo number_format($pecah['subharga']); ?></td>
		</tr>
	<?php $nomor++; ?>	
	<?php } ?>
	</tbody>
</table>

<div class="row">
	<div class="col-md-7">
		<div class="alert alert-info">
			<p>
				silahkan melakukan pembayaran Rp. <?php echo number_format($detail['total_pelanggan']); ?> ke<br>
				<strong>BANK BCA 190-000-987 AN. Puja Satria Pandu</strong>
			</p>
		</div>
	</div>

</div>
<a href="kirim.php" class="btn btn-primary">Kirim</a>

	</div>
</section>	

</body>
</html>