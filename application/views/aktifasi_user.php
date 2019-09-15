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
		            <div class="container">
		                <h2 align="center">Aktifasi Akun</h2>
						<form class="form-group my-5" action="<?= site_url('aktifasi/user') ?>" method="POST">
							<input class="form-control my-2" type="text" name="username" placeholder="Username" required />
							<?= "<font color='red'>".  validation_errors() . "</font>"?>
							<input class="form-control my-2" type="password" name="password" placeholder="Password" required />
							<?= "<font color='red'>".  validation_errors() . "</font>"?>
							<input class="btn btn-success w-100" type="submit" name="register" value="Aktifkan"></input>
						</form>
		            </div>
		        </div>
		    </div>
		</div>
	</body>
</html>