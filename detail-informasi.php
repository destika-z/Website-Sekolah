<?php include 'header.php'; ?>

<div class="section">
	<div class="container">

		<?php
			$informasi = mysqli_query($conn, "
				SELECT informasi.*, pengguna.nama AS nama_user
				FROM informasi
				LEFT JOIN pengguna ON pengguna.id = informasi.created_by
				WHERE informasi.id = '".$_GET['id']."'
			");

			if(mysqli_num_rows($informasi) == 0){
				echo "<script>window.location='index.php'</script>";
			}

			$p = mysqli_fetch_object($informasi);
		?>

		<h3 class="text-center"><?= $p->judul ?></h3>

		<?php
			// AMAN dari NULL
			if (!empty($p->created_at) && $p->created_at != '0000-00-00 00:00:00') {
				$tanggal = date('d/m/Y', strtotime($p->created_at));
			} else {
				$tanggal = date('d/m/Y');
			}
		?>

		<small>
			Dibuat pada <?= $tanggal ?>, oleh <?= $p->nama_user ?>
		</small>

		<br><br>

		<img src="uploads/informasi/<?= $p->gambar ?>" width="100%" class="image" style="margin-top:5px">

		<div style="margin-top:15px;">
			<?= $p->keterangan ?>
		</div>

	</div>
</div>

<?php include 'footer.php'; ?>