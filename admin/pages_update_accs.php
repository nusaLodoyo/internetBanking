<?php
session_start();
include('conf/config.php');
include('conf/checklogin.php');
check_login();
$admin_id = $_SESSION['admin_id'];
if (isset($_POST['update_acc_type'])) {
    // Mendaftarkan jenis akun
    $name = $_POST['name'];
    $description = $_POST['description'];
    $rate = $_POST['rate'];
    $code = $_GET['code'];


    // Memasukkan informasi yang ditangkap ke tabel database
    $query = "UPDATE  iB_Acc_types SET name=?, description=?, rate=? WHERE code=?";
    $stmt = $mysqli->prepare($query);
    // Mengikat parameter
    $rc = $stmt->bind_param('ssss', $name, $description, $rate, $code);
    $stmt->execute();

    // Mendeklarasikan variabel yang akan dilewatkan ke fungsi peringatan
    if ($stmt) {
        $success = "Kategori Akun iBank Diperbarui";
    } else {
        $err = "Silakan Coba Lagi atau Coba Nanti";
    }
}

?>
<!DOCTYPE html>
<html>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<?php include("dist/_partials/head.php"); ?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php include("dist/_partials/nav.php"); ?>
        <!-- /.navbar -->

        <!-- Kontainer Sidebar Utama -->
        <?php include("dist/_partials/sidebar.php"); ?>

        <!-- Content Wrapper Berisi konten halaman -->
        <?php
        // Mengambil semua jenis akun
        $code = $_GET['code'];
        $ret = "SELECT * FROM  iB_Acc_types WHERE code = ? ";
        $stmt = $mysqli->prepare($ret);
        $stmt->bind_param('s', $code);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        $cnt = 1;
        while ($row = $res->fetch_object()) {

        ?>
            <div class="content-wrapper">
                <!-- Header Konten (Header Halaman) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Perbarui <?php echo $row->name; ?></h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="pages_dashboard.php">Dasbor</a></li>
                                    <li class="breadcrumb-item"><a href="pages_update_accs.php">Akun iBank</a></li>
                                    <li class="breadcrumb-item"><a href="pages_update_accs.php">Kelola</a></li>
                                    <li class="breadcrumb-item active"><?php echo $row->name; ?></li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <!-- Konten Utama -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <!-- Kolom kiri -->
                            <div class="col-md-12">
                                <!-- elemen formulir umum -->
                                <div class="card card-purple">
                                    <div class="card-header">
                                        <h3 class="card-title">Isi Semua Kolom</h3>
                                    </div>
                                    <!-- Memulai formulir -->
                                    <form method="post" enctype="multipart/form-data" role="form">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class=" col-md-4 form-group">
                                                    <label for="exampleInputEmail1">Nama Kategori Akun</label>
                                                    <input type="text" name="name" value="<?php echo $row->name; ?>" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                                <div class=" col-md-4 form-group">
                                                    <label for="exampleInputEmail1">Rate Kategori Akun % Per Tahun </label>
                                                    <input type="text" name="rate" value="<?php echo $row->rate; ?>" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                                <div class=" col-md-4 form-group">
                                                    <label for="exampleInputPassword1">Kode Kategori Akun</label>
                                                    <input type="text" readonly name="code" value="<?php echo $row->code; ?>" readonly class="form-control" id="exampleInputPassword1">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class=" col-md-12 form-group">
                                                    <label for="exampleInputEmail1">Deskripsi Kategori Akun</label>
                                                    <textarea type="text" name="description" required class="form-control" id="desc"><?php echo $row->description; ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                        <div class="card-footer">
                                            <button type="submit" name="update_acc_type" class="btn btn-success">Perbarui Akun</button>
                                        </div>
                                    </form>
                                </div>
                                <!-- /.card -->
                            </div><!-- /.container-fluid -->
                </section>
                <!-- /.content -->
            </div>
        <?php } ?>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Konten control sidebar ada di sini -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- bs-custom-file-input -->
    <script src="plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- AdminLTE untuk tujuan demo -->
    <script src="dist/js/demo.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            bsCustomFileInput.init();
        });
    </script>
    <!-- Memuat Javascript CK EDITOR -->
    <script src="//cdn.ckeditor.com/4.6.2/basic/ckeditor.js"></script>
    <script type="text/javascript">
        CKEDITOR.replace('desc')
    </script>
    </script>
</body>

</html>
