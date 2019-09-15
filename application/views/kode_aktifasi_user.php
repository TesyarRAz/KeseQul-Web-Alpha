<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Aktifasi User</title>
		<meta charset="utf-8">
		<meta name="viewport" content="initial-scale=1, shrink-to-fit=no, width=device-width">
		<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/
		bootstrap.min.css">
		<style type="text/css">
			body, html {
				padding: 0;
				margin: 0;
				height: 100%;
			}
		</style>
	</head>
	<body>
		<div class="container h-100">
		    <div class="row align-items-center h-100">
		        <div class="col-12 mx-auto">
		            <div class="jumbotron">
		            	<h2 align="center">Aktifkan Akun Dengan Tautan Link Yang Sudah Disediakan</h2>
		            	<a class="btn btn-success w-50 my-5 d-block mx-auto" target="_blank" href="<?= site_url('aktifasi/user/' . $kode) ?>">Klik Untuk Aktifasi</a>
		            </div>
		        </div>
		    </div>
		</div>
	</body>
</html>