<?php
session_start();
include('conf/config.php');
include('conf/checklogin.php');
check_login();
$admin_id = $_SESSION['admin_id'];
// Mendaftarkan akun baru
if (isset($_POST['create_staff_account'])) {
    // Mendaftarkan Staff
    $name = $_POST['name'];
    $staff_number = $_POST['staff_number'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = sha1(md5($_POST['password']));
    $sex  = $_POST['sex'];

    $profile_pic  = $_FILES["profile_pic"]["name"];
    move_uploaded_file($_FILES["profile_pic"]["tmp_name"], "dist/img/" . $_FILES["profile_pic"]["name"]);

    // Memasukkan informasi yang telah diambil ke dalam tabel database
    $query = "INSERT INTO iB_staff (name, staff_number, phone, email, password, sex, profile_pic) VALUES (?,?,?,?,?,?,?)";
    $stmt = $mysqli->prepare($query);
    // Mengikat parameter
    $rc = $stmt->bind_param('sssssss', $name, $staff_number, $phone, $email, $password, $sex, $profile_pic);
    $stmt->execute();

    // Mendeklarasikan variabel yang akan digunakan dalam fungsi peringatan
    if ($stmt) {
        $success = "Akun Staff Telah Dibuat";
    } else {
        $err = "Silakan Coba Lagi Atau Coba Nanti";
    }
}

?>
<!DOCTYPE html>
<html><!-- Kunjungi freesourcecodes.buzz untuk proyek-proyek lainnya! -->
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<?php include("dist/_partials/head.php"); ?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php include("dist/_partials/nav.php"); ?>
        <!-- /.navbar -->

        <!-- Kontainer Sidebar Utama -->
        <?php include("dist/_partials/sidebar.php"); ?>

        <!-- Content Wrapper. Berisi konten halaman -->
        <div class="content-wrapper">
            <!-- Header Konten (Header Halaman) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Buat Akun Staff</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="pages_dashboard.php">Dasbor</a></li>
                                <li class="breadcrumb-item"><a href="pages_add_staff.php">Staff iBanking</a></li>
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
                                            <div class="col-md-6 form-group">
                                                <label for="exampleInputEmail1">Nama Staff</label>
                                                <input type="text" name="name" required class="form-control" id="exampleInputEmail1">
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label for="exampleInputPassword1">Nomor Staff</label>
                                                <?php
                                                // Fungsi PHP untuk menghasilkan nomor penumpang acak
                                                $length = 4;
                                                $_staffNumber =  substr(str_shuffle('0123456789'), 1, $length);
                                                ?>
                                                <input type="text" readonly name="staff_number" value="iBank-STAFF-<?php echo $_staffNumber; ?>" class="form-control" id="exampleInputPassword1">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 form-group">
                                                <label for="exampleInputEmail1">Nomor Telepon Staff</label>
                                                <input type="text" name="phone" required class="form-control" id="exampleInputEmail1">
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label for="exampleInputPassword1">Jenis Kelamin Staff</label>
                                                <select class="form-control" name="sex">
                                                    <option>Pilih Jenis Kelamin</option>
                                                    <option>Perempuan</option>
                                                    <option>Laki-Laki</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 form-group">
                                                <label for="exampleInputEmail1">Email Staff</label>
                                                <input type="email" name="email" required class="form-control" id="exampleInputEmail1">
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label for="exampleInputPassword1">Password Staff</label>
                                                <input type="password" name="password" required class="form-control" id="exampleInputEmail1">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputFile">Foto Profil Staff</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" name="profile_pic" class="custom-file-input" id="exampleInputFile">
                                                    <label class="custom-file-label" for="exampleInputFile">Pilih file</label>
                                                </div>
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="">Unggah</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                    <div class="card-footer">
                                        <button type="submit" name="create_staff_account" class="btn btn-success">Tambahkan Staff</button>
                                    </div>
                                </form>
                            </div>
                            <!-- /.card -->
                        </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <div class="float-left">
                &copy; 2023 - NUSA Media
            </div>
            <div class="float-right d-none d-sm-inline">
                Developed by Pramudya Sensei
            </div>
        </footer>
        <!-- Kontrol Sidebar -->
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
</body>

</html>
