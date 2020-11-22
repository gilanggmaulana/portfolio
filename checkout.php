<?php
session_start();
$koneksi = new mysqli("localhost","root","","toko_online");


//jika tidak login maka akan diberi peringatan
if (!isset($_SESSION["pelanggan"])) 
{
	echo "<script>alert('silahkan login dulu');</script>";
	echo "<script>location='login.php';</script>";
}

?>
<!DOCTYPE html>
<html>
<head>
	<h2>Checkout Keranjang</h2>
	<title>Checkout</title>
	<link rel="stylesheet" href="admin/assets/css/bootstrap.css">
</head>
<body>
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


<section class="konten">
	<div class="container">
		<h1></h1>
		<hr>
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>No</th>
					<th>Produk</th>
					<th>Harga</th>
					<th>Jumlah</th>
					<th>Subharga</th>
				</tr>
			</thead>
			<tbody>
				<?php $nomor=1; ?>
				<?php $totalbelanja = 0; ?>
				<?php foreach ($_SESSION["keranjang"] as $id_produk => $jumlah): ?>
				<!--menampilkan produk yg sedan diperulangkan berdasarkan produk-->
				<?php
				$ambil = $koneksi->query("SELECT * FROM produk WHERE id_produk='$id_produk'");
				$pecah = $ambil->fetch_assoc();
				$subharga = $pecah["harga_produk"]*$jumlah;
				
				?>	
				<tr>
					<td><?php echo $nomor; ?></td>
					<td><?php echo $pecah["nama_produk"]; ?></td>
					<td>Rp. <?php echo number_format($pecah["harga_produk"]); ?></td>
					<td><?php echo $jumlah; ?></td>
					<td>Rp. <?php echo number_format($subharga); ?></td>
		
				</tr>
				<?php $nomor++; ?>
				<?php $totalbelanja+=$subharga; ?>
				<?php endforeach?>
			</tbody>
			<tfoot>
				<tr>
					<th colspan="4">Total Belanja</th>
					<th>Rp. <?php echo number_format($totalbelanja)?> </th>
				</tr>
			</tfoot>
		</table>

		<form method="post">
			
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<input type="text" readonly value="<?php echo $_SESSION["pelanggan"]['nama_pelanggan'] ?>" class="form-control">
			</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<input type="text" readonly value="<?php echo $_SESSION["pelanggan"]['telepon_pelanggan'] ?>" class="form-control">
					</div>
				</div>
				
				<div class="col-md-4">
					<select class="form-control" name="id_ongkir">
						<option value="">Pilih Ongkos Kirim</option>
						<?php 
						$ambil = $koneksi->query("SELECT * FROM ongkir");
						while($perongkir = $ambil->fetch_assoc()){
						?>
						<option value="<?php echo $perongkir["id_ongkir"] ?>">
							<?php echo $perongkir['nama_kota'] ?> -
							Rp. <?php echo $perongkir['tarif'] ?>
						</option>
					<?php } ?>
					</select>
				</div>
			</div>
			
			
			<div>
				<label>Alamat Lengkap Pengiriman</label>
				<textarea class="form-control" name="alamat_pengiriman" placeholder="masukkan alamat lengkap pengiriman(sertakan kode pos)"></textarea>
			</div>
			<button class="btn btn-primary" name="checkout">Checkout</button>
		</form>

		<?php
		if (isset($_POST["checkout"])) 
		{
			$id_pelanggan = $_SESSION["pelanggan"]["id_pelanggan"];
			$id_ongkir = $_POST["id_ongkir"];
			$tanggal_pelanggan = date("Y-m-d");
			$alamat_pengiriman = $_POST['alamat_pengiriman'];
			
			$ambil = $koneksi->query("SELECT * FROM ongkir WHERE id_ongkir='$id_ongkir'");
			$arrayongkir = $ambil->fetch_assoc();
			$nama_kota = $arrayongkir['nama_kota'];
			$tarif = $arrayongkir['tarif'];

			$total_pelanggan = $totalbelanja + $tarif;

			//1.menyimapan data ke tabel opembelian
			$koneksi->query("INSERT INTO pembelian (id_pelanggan,id_ongkir,tanggal_pelanggan,total_pelanggan,nama_kota,tarif,alamat_pengiriman) VALUES ('$id_pelanggan','$id_ongkir','$tanggal_pelanggan','$total_pelanggan','$nama_kota','$tarif','$alamat_pengiriman') ");

			//mendapatkan id_pembelian barusan terjadi
			$id_pembelian_barusan = $koneksi->insert_id;

			foreach ($_SESSION["keranjang"]as $id_produk => $jumlah) 
			{
				//mendapatkan adata produk berdasarkan id_produk

				$ambil=$koneksi->query("SELECT * FROM produk WHERE id_produk='$id_produk'");
				$perproduk = $ambil->fetch_assoc();

				$nama = $perproduk['nama_produk'];
				$harga = $perproduk['harga_produk'];
				$berat = $perproduk['berat_produk'];

				$subberat = $perproduk['berat_produk']*$jumlah;
				$subharga = $perproduk['harga_produk']*$jumlah;
				$koneksi->query("INSERT INTO pembelian_produk(id_pembelian,id_produk,nama,harga,berat,subberat,subharga,jumlah)VALUES('$id_pembelian_barusan','$id_produk','$nama','$harga','$berat','$subberat','$subharga','$jumlah')");
			}

			//mengosongkan keranjang belanja
			unset($_SESSION["keranjang"]);


			//tampilkan dialihkan kehalaman nota, nota dari pembelian tersebut
			echo "<script>alert('pembelian berhasil');</script>";
			echo "<script>location='nota.php?id=$id_pembelian_barusan';</script>";
		}
		
		?>
		
	</div>

</section>
</body>
</html>