<?php include 'header.php'; ?>

<?php
$id = (int) $_GET['id']; // amankan id

$galeri = mysqli_query($conn, "SELECT * FROM galeri WHERE id = '$id'");

if(mysqli_num_rows($galeri) == 0){
    echo "<script>window.location='galeri.php'</script>";
    exit;
}

$p = mysqli_fetch_object($galeri);
?>

<!-- content -->
<div class="content">
    <div class="container">
        <div class="box">

            <div class="box-header">
                Edit Galeri
            </div>

            <div class="box-body">
                
                <form action="" method="POST" enctype="multipart/form-data">

                    <div class="form-group">
                        <label>Keterangan</label>
                        <input type="text" name="keterangan" maxlength="255"
                            placeholder="Keterangan"
                            value="<?= $p->keterangan ?>"
                            class="input-control" required>
                    </div>

                    <div class="form-group">
                        <label>Gambar</label><br>
                        <img src="../uploads/galeri/<?= $p->foto ?>" width="200px" class="image"><br><br>
                        <input type="hidden" name="gambar2" value="<?= $p->foto ?>">
                        <input type="file" name="gambar" class="input-control">
                    </div>

                    <button type="button" class="btn" onclick="window.location = 'galeri.php'">Kembali</button>
                    <input type="submit" name="submit" value="Simpan" class="btn btn-blue">

                </form>

                <?php
                if(isset($_POST['submit'])){

                    $ket = substr(addslashes(ucwords($_POST['keterangan'])), 0, 255);
                    $currdate = date('Y-m-d H:i:s');

                    // CEK GAMBAR
                    if($_FILES['gambar']['name'] != ''){

                        $filename   = $_FILES['gambar']['name'];
                        $tmpname    = $_FILES['gambar']['tmp_name'];
                        $filesize   = $_FILES['gambar']['size'];

                        $formatfile = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                        $rename     = 'galeri'.time().'.'.$formatfile;

                        $allowedtype = array('png', 'jpg', 'jpeg', 'gif');

                        if(!in_array($formatfile, $allowedtype)){

                            echo '<div class="alert alert-error">Format file tidak diizinkan.</div>';
                            return false;

                        }elseif($filesize > 1000000){

                            echo '<div class="alert alert-error">Ukuran file tidak boleh lebih dari 1 MB.</div>';
                            return false;

                        }else{

                            // hapus gambar lama
                            if(file_exists("../uploads/galeri/".$_POST['gambar2'])){
                                unlink("../uploads/galeri/".$_POST['gambar2']);
                            }

                            move_uploaded_file($tmpname, "../uploads/galeri/".$rename);
                        }

                    }else{
                        $rename = $_POST['gambar2'];
                    }

                    $update = mysqli_query($conn, "UPDATE galeri SET
                        keterangan = '$ket',
                        foto = '$rename',
                        updated_at = '$currdate'
                        WHERE id = '$id'
                    ");

                    if($update){
                        echo "<script>window.location='galeri.php?success=Edit Data Berhasil'</script>";
                    }else{
                        echo 'gagal edit: '.mysqli_error($conn);
                    }
                }
                ?>

            </div>

        </div>
    </div>
</div>

<?php include 'footer.php'; ?>