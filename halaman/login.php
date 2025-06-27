<!DOCTYPE html>
<html>
<head>
	<title>Sistem Alih Rekam Medis</title>
	<link rel="stylesheet" type="text/css" href="../assets/login/css/loginpage.css">
</head>
<body>
	<?php 
	if(isset($_GET['pesan'])){
		if($_GET['pesan']=="gagal"){
			echo "<div class='alert'>Username dan Password tidak sesuai !</div>";
		}
		elseif($_GET['pesan']=="logout"){
			echo "<div class='alert'>Anda Telah Logout !</div>";
		}
	}
	?>
 
	<div class="kotak_login">
		<p class="tulisan_login">Masuk</p>
 
		<form action="../proses/cek_login.php" method="post">
			<label>Username</label>
			<input type="email" name="email" class="form_login" placeholder="Email.." required="required" id="email">
 
			<label>Password</label>
			<input type="password" name="password" class="form_login" placeholder="Password .." required="required" id="password">
 
			<input type="submit" class="tombol_login" value="LOGIN">
 
			<br/>
			<br/>
			<center>
				<a class="link" href="https://wizzytech.co.id">kembali</a>
			</center>
		</form>
		
	</div>
</body>
</html>