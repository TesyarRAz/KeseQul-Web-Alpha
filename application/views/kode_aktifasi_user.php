<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Aktifasi User</title>
		<meta charset="utf-8">
		<style type="text/css">
			.btn {
				text-decoration: none;
				color: white;
				background-color: green;
				width: 100%;
				padding: 15px;
			}
			.btn:hover {
				background-color: darkgreen;
			}
		</style>
	</head>
	<body>
		<div style="margin: 0 auto">
			<h2 align="center">Aktifkan Akun Dengan Tautan Link Yang Sudah Disediakan</h2>
			<div align="center" style="margin-top: 50px">
				<a class="btn" target="_blank" href="<?= site_url('aktifasi/user/' . $kode) ?>">Klik Untuk Aktifasi</a>
			</div>
		</div>
	</body>
</html>