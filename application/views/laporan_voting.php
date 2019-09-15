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
				border-radius: 10px;
				width: 100%;
			}
			.short {
				width: 5%;
			}
			.normal {
				width: 10%;
			}
			.large {
				width: 15%;
			}

			table {
				border-collapse: collapse;
				font-family: arial;
				color: #5e5b5c;
				width: 100%;
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
		<div id="outtable">
			<h2 align="center">Laporan Voting - <?= $header ?></h2>
			<table border="1" cellspacing="0">
				<thead>
					<tr>
						<th class="short" rowspan="2">#</th>
						<th class="normal" rowspan="2">Nama Team</th>
						<th class="large" colspan="2">Calon Ketua</th>
						<th class="large" colspan="2">Calon Wakil</th>
						<th class="normal" rowspan="2">Total Pemilih</th>
					</tr>
					<tr>
						<th class="short">Nama</th>
						<th class="short">Kelas</th>
						<th class="short">Nama</th>
						<th class="short">Kelas</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i=1;
					foreach($data as $key):
					?>

					<tr>
						<td><?= $i++ ?></td>
						<td><?= $key['nama_team'] ?></td>
						<td><?= $key['nama_ketua'] ?></td>
						<td><?= $key['kelas_ketua'] ?> </td>
						<td><?= $key['nama_wakil'] ?></td>
						<td><?= $key['kelas_wakil'] ?></td>
						<td><?= $key['total'] ?></td>
					</tr>

					<?php
					endforeach;
					?>
					<tr>
						<td colspan="5"></td>
						<td colspan="2">
							<h3 align="center">Pemenangnya : <?= $data[0]['nama_team'] ?></h3>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</body>
</html>