<?php
session_start();
include('conf/config.php');
include('conf/checklogin.php');
check_login();
$staff_id = $_SESSION['staff_id'];
if (isset($_POST['create_acc_type'])) {
    //Mendaftarkan jenis akun
    $name = $_POST['name'];
    $description = $_POST['description'];
    $rate = $_POST['rate'];
    $code = $_POST['code'];

    //Memasukkan informasi yang telah diambil ke dalam tabel database
    $query = "INSERT INTO iB_Acc_types (name, description, rate, code) VALUES (?,?,?,?)";
    $stmt = $mysqli->prepare($query);
    //Mengikat parameter
    $rc = $stmt->bind_param('ssss', $name, $description, $rate, $code);
    $stmt->execute();

    //Mendeklarasikan variabel yang akan digunakan dalam fungsi peringatan
    if ($stmt) {
        $success = "Kategori Akun Telah Dibuat";
    } else {
        $err = "Silakan Coba Lagi Atau Coba Nanti";
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

        <!-- Konten Wrapper. Berisi konten halaman -->
        <div class="content-wrapper">
            <!-- Header Konten (Header Halaman) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Buat Kategori Akun</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="pages_dashboard.php">Dasbor</a></li>
                                <li class="breadcrumb-item"><a href="pages_add_acc_type.php">iBanking</a></li>
                                <li class="breadcrumb-item active">Tambahkan</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Konten Utama -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <!-- kolom kiri -->
                        <div class="col-md-12">
                            <!-- elemen formulir umum -->
                            <div class="card card-purple">
                                <div class="card-header">
                                    <h3 class="card-title">Isi Semua Bidang</h3>
                                </div>
                                <!-- mulai formulir -->
                                <form method="post" enctype="multipart/form-data" role="form">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class=" col-md-4 form-group">
                                                <label for="exampleInputEmail1">Nama Kategori Akun</label>
                                                <input type="text" name="name" required class="form-control" id="exampleInputEmail1">
                                            </div>
                                            <div class=" col-md-4 form-group">
                                                <label for="exampleInputEmail1">Tarif Kategori Akun % Per Tahun</label>
                                                <input type="text" name="rate" required class="form-control" id="exampleInputEmail1">
                                            </div>
                                            <div class=" col-md-4 form-group">
                                                <label for="exampleInputPassword1">Kode Kategori Akun</label>
                                                <?php
                                                //Fungsi PHP untuk menghasilkan nomor penumpang acak
                                                $length = 5;
                                                $_Number =  substr(str_shuffle('0123456789QWERTYUIOPLKJHGFDSAZXCVBNM'), 1, $length);
                                                ?>
                                                <input type="text" readonly name="code" value="ACC-CAT-<?php echo $_Number; ?>" class="form-control" id="exampleInputPassword1">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class=" col-md-12 form-group">
                                                <label for="exampleInputEmail1">Deskripsi Kategori Akun</label>
                                                <textarea type="text" name="description" required class="form-control" id="desc"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                    <div class="card-footer">
                                        <button type="submit" name="create_acc_type" class="btn btn-success">Tambahkan Jenis Akun</button>
                                    </div>
                                </form>
                            </div>
                            <!-- /.card -->
                        </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Konten sidebar kontrol disini -->
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
    <!--Load CK EDITOR Javascript-->
    <script src="//cdn.ckeditor.com/4.6.2/basic/ckeditor.js"></script>
    <script type="text/javascript">
        CKEDITOR.replace('desc')
    </script>
    </script>
</body>

</html>
