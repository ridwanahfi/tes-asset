<body>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/css/bootstrap-select.css" />
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/js/bootstrap-select.js"></script>
	<?php
	include_once "library/inc.seslogin.php";

	# TOMBOL SIMPAN DIKLIK
	if (isset($_POST['btnSimpan'])) {
		# Baca Variabel Form
		$txtNama		= $_POST['txtNama'];

		# Validasi form, jika kosong sampaikan pesan error
		$pesanError = array();
		if (trim($txtNama) == "") {
			$pesanError[] = "Data <b>Nama Departemen</b> tidak boleh kosong !";
		}

		# Validasi Nama departemen, jika sudah ada akan ditolak
		$Kode	= $_POST['txtKode'];
		$cekSql	= "SELECT * FROM departemen WHERE nm_departemen='$txtNama' AND NOT(kd_departemen='$Kode')";
		$cekQry	= mysql_query($cekSql, $koneksidb) or die("Eror Query" . mysql_error());
		if (mysql_num_rows($cekQry) >= 1) {
			$pesanError[] = "Maaf, departemen <b> $txtNama </b> sudah ada, ganti dengan yang lain";
		}

		# JIKA ADA PESAN ERROR DARI VALIDASI
		if (count($pesanError) >= 1) {
			echo "<div class='mssgBox'>";
			echo "<img src='images/attention.png'> <br><hr>";
			$noPesan = 0;
			foreach ($pesanError as $indeks => $pesan_tampil) {
				$noPesan++;
				echo "&nbsp;&nbsp; $noPesan. $pesan_tampil<br>";
			}
			echo "</div> <br>";
		} else {
			# SIMPAN PERUBAHAN DATA, Jika jumlah error pesanError tidak ada, simpan datanya
			$Kode	= $_POST['txtKode'];
			$mySql	= "UPDATE departemen SET nm_departemen='$txtNama' WHERE kd_departemen ='$Kode'";
			$myQry	= mysql_query($mySql, $koneksidb) or die("Gagal query" . mysql_error());
			if ($myQry) {
				echo "<script>alert('Data Departemen Berhasil Diubah')</script>";
				echo "<meta http-equiv='refresh' content='0; url=?open=Departemen-Data'>";
			}
			exit;
		}
	} // Penutup Tombol Simpan

	# TAMPILKAN DATA LOGIN UNTUK DIEDIT
	$Kode	 = $_GET['Kode'];
	$mySql	 = "SELECT * FROM departemen WHERE kd_departemen='$Kode'";
	$myQry	 = mysql_query($mySql, $koneksidb)  or die("Query ambil data salah : " . mysql_error());
	$myData	 = mysql_fetch_array($myQry);

	// Menyimpan data ke variabel temporary (sementara)
	$dataKode	= $myData['kd_departemen'];
	$dataNama	= isset($_POST['txtNama']) ? $_POST['txtNama'] : $myData['nm_departemen'];
	?>
	<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="form1">
		<table class="table-list" width="100%">
			<tr>
				<th colspan="3">UBAH DATA DEPARTEMEN </th>
			</tr>
			<tr>
				<td width="15%"><b>Kode</b></td>
				<td width="1%"><b>:</b></td>
				<td width="84%"><input name="textfield" value="<?php echo $dataKode; ?>" size="25" maxlength="4" readonly="readonly" />
					<input name="txtKode" type="hidden" value="<?php echo $dataKode; ?>" />
				</td>
			</tr>
			<tr>
				<td><b>Nama Departemen </b></td>
				<td><b>:</b></td>
				<td><input name="txtNama" type="text" value="<?php echo $dataNama; ?>" size="25" maxlength="100" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td><input type="submit" name="btnSimpan" value=" Simpan ">
					<a href="?open=Departemen-Data">
						<input type="button" value=" Kembali " />
					</a>
				</td>
			</tr>
		</table>
	</form>