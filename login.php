<?php
session_start();
$koneksi = new mysqli("localhost","root","","toko_online");
?>
<!DOCTYPE html>
<html>
<head>
	<title>Login Pelanggan</title>
	<link rel="stylesheet" href="admin/assets/css/bootstrap.css">
</head>
<body>


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


<div class="container">
	<div class="row"> 
		<div class="col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Login Pelanggan</h3>
				</div>
				<div class="panel-body">
					<form method="post">
						<div class="form-group">
							<label>Email</label>
							<input type="email" class="form-control" name="email">
						</div>
						<div class="form-group">
							<label>Password</label>
							<input type="password" class="form-control" name="password">
						</div>
						<button class="btn btn-primary" name="login">Login</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>


<?php
//jika ada tombol
if (isset($_POST["login"])) 
{
	
	$email = $_POST["email"];
	$password = $_POST["password"];
	//lakukan kuery ngecek akun di table pelanggan
	$ambil = $koneksi->query("SELECT * FROM pelanggan WHERE email_pelanggan='$email' AND password_pelanggan='$password'");

	//ngitung akun yang terambil
	$akunyangcocok = $ambil->num_rows;

	//jika 1 akun yang cocok
	if ($akunyangcocok==1) 
	{
	 	//anda sukses login
	 	//mendapatkan akun dalam bentuk array
	 	$akun = $ambil->fetch_assoc();
	 	//simpan di session pelanggan
	 	$_SESSION["pelanggan"] = $akun;
	 	echo "<script>alert('login anda sukses sam!!');</script>";
	 	echo "<script>location='checkout.php';</script>";
	}
	else  
	{
	 	//anda gagal login
	 	echo "<script>alert('login anda gagal sam, periksa yaa!!');</script>";
	 	echo "<script>location='login.php';</script>";
	} 
}
?>













</body>
</html>