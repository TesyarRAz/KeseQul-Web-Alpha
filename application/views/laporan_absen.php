<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Laporan Absensi</title>
		<style type="text/css">
			#outtable {
				padding: 20px;
				border: 1px solid #e3e3e3;
				width: 600px;
				border-radius: 10px;
			}
			.short {
				width: 50px;
			}
			.normal {
				width: 150px;
			}

			table {
				border-collapse: collapse;
				font-family: arial;
				color: #5e5b5c;
			}

			thead th {
				text-align: center;
				padding: 10px;
			}

			tbody td {
				border-top: 1px solid #e3e3e3;
				padding: 10px;
			}

			tbody tr:nth-child(even) {
				background-color: #f6f5fa;
			}

			tbody tr:hover {
				background-color: #eae9f5;
			}
		</style>
	</head>
	<body>
		<div>
			<h2 align="center">Laporan Absensi Hari Ini</h2>
			<table border="1" cellspacing="0">
				<thead>
					<tr>
						<th class="short" rowspan="2">#</th>
						<th class="normal" rowspan="2">Kelas</th>
						<th class="normal" rowspan="2">Jurusan</th>
						<th class="normal" colspan="3">Kehadiran</th>
					</tr>
					<tr>
						<th class="short">A</th>
						<th class="short">S</th>
						<th class="short">I</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i=1;
					foreach($data as $key):
					?>

					<tr>
						<td><?= $i++ ?></td>
						<td><?= $key['kelas'] ?></td>
						<td><?= $key['jurusan'] ?></td>
						<td><?= $key['alfa'] ?> </td>
						<td><?= $key['sakit'] ?></td>
						<td><?= $key['izin'] ?></td>
					</tr>

					<?php
					endforeach;
					?>
				</tbody>
			</table>
		</div>
	</body>
</html>