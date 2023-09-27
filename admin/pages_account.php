<?php
session_start();
include('conf/config.php');
include('conf/checklogin.php');
check_login();
$admin_id = $_SESSION['admin_id'];
// Memperbarui akun pengguna yang sudah masuk
if (isset($_POST['update_account'])) {
    $name = $_POST['name'];
    $admin_id = $_SESSION['admin_id'];
    $email = $_POST['email'];
    // Memperbarui tabel tertentu di database
    $query = "UPDATE iB_admin SET name=?, email=? WHERE admin_id=?";
    $stmt = $mysqli->prepare($query);
    // Parameter terikat
    $rc = $stmt->bind_param('ssi', $name, $email, $admin_id);
    $stmt->execute();
    // Mendeklarasikan variabel yang akan digunakan dalam fungsi peringatan
    if ($stmt) {
        $success = "Akun Diperbarui";
    } else {
        $err = "Silakan Coba Lagi Atau Coba Nanti";
    }
}
// Mengubah kata sandi
if (isset($_POST['change_password'])) {
    $password = sha1(md5($_POST['password']));
    $admin_id = $_SESSION['admin_id'];
    // Memperbarui tabel tertentu di database
    $query = "UPDATE iB_admin SET password=? WHERE admin_id=?";
    $stmt = $mysqli->prepare($query);
    // Parameter terikat
    $rc = $stmt->bind_param('si', $password, $admin_id);
    $stmt->execute();
    // Mendeklarasikan variabel yang akan digunakan dalam fungsi peringatan
    if ($stmt) {
        $success = "Kata Sandi Diperbarui";
    } else {
        $err = "Silakan Coba Lagi Atau Coba Nanti";
    }
}
?>
<!-- Kunjungi freesourcecodes.buzz untuk proyek-proyek lainnya! -->
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

        <!-- Kontainer Konten. Berisi konten halaman -->
        <div class="content-wrapper">
            <!-- Header Konten dengan detail pengguna yang sudah masuk (Header Halaman) -->
            <?php
            $admin_id = $_SESSION['admin_id'];
            $ret = "SELECT * FROM  iB_admin  WHERE admin_id = ? ";
            $stmt = $mysqli->prepare($ret);
            $stmt->bind_param('i', $admin_id);
            $stmt->execute(); //ok
            $res = $stmt->get_result();
            while ($row = $res->fetch_object()) {
                // Mengatur gambar profil pengguna yang sudah masuk secara default jika mereka belum memperbarui foto profil mereka
                if ($row->profile_pic == '') {
                    $profile_picture = "
                        <img class='img-fluid'
                        src='dist/img/user_icon.png'
                        alt='Foto profil pengguna'>
                        ";
                } else {
                    $profile_picture = "
                        <img class=' img-fluid'
                        src='dist/img/$row->profile_pic'
                        alt='Foto profil pengguna'>
                        ";
                }
            ?>
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Profil <?php echo $row->name; ?></h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="pages_dashboard.php">Dasbor</a></li>
                                    <li class="breadcrumb-item"><a href="pages_account.php">Profil</a></li>
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
                            <div class="col-md-3">

                                <!-- Gambar Profil -->
                                <div class="card card-purple card-outline">
                                    <div class="card-body box-profile">
                                        <div class="text-center">
                                            <?php echo $profile_picture; ?>
                                        </div>

                                        <h3 class="profile-username text-center"><?php echo $row->name; ?></h3>

                                        <p class="text-muted text-center">@Admin iBanking</p>

                                        <ul class="list-group list-group-unbordered mb-3">
                                            <li class="list-group-item">
                                                <b>Email: </b> <a class="float-right"><?php echo $row->email; ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Nomor: </b> <a class="float-right"><?php echo $row->number; ?></a>
                                            </li>
                                        </ul>

                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->

                            </div>

                            <!-- /.col -->
                            <div class="col-md-9">
                                <div class="card">
                                    <div class="card-header p-2">
                                        <ul class="nav nav-pills">
                                            <li class="nav-item"><a class="nav-link active" href="#update_Profile" data-toggle="tab">Perbarui Profil</a></li>
                                            <li class="nav-item"><a class="nav-link" href="#Change_Password" data-toggle="tab">Ubah Kata Sandi</a></li>
                                        </ul>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <div class="tab-content">
                                            <!-- / Perbarui Profil -->
                                            <div class="tab-pane active" id="update_Profile">
                                                <form method="post" class="form-horizontal">
                                                    <div class="form-group row">
                                                        <label for="inputName" class="col-sm-2 col-form-label">Nama</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" name="name" required class="form-control" value="<?php echo $row->name; ?>" id="inputName">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                                                        <div class="col-sm-10">
                                                            <input type="email" name="email" required value="<?php echo $row->email; ?>" class="form-control" id="inputEmail">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="inputName2" class="col-sm-2 col-form-label">Nomor</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" class="form-control" required readonly name="number" value="<?php echo $row->number; ?>" id="inputName2">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="offset-sm-2 col-sm-10">
                                                            <button name="update_account" type="submit" class="btn btn-outline-success">Perbarui Akun</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>

                                            <!-- / Ubah Kata Sandi -->
                                            <div class="tab-pane" id="Change_Password">
                                                <form method="post" class="form-horizontal">
                                                    <div class="form-group row">
                                                        <label for="inputName" class="col-sm-2 col-form-label">Kata Sandi Lama</label>
                                                        <div class="col-sm-10">
                                                            <input type="password" class="form-control" required id="inputName">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="inputEmail" class="col-sm-2 col-form-label">Kata Sandi Baru</label>
                                                        <div class="col-sm-10">
                                                            <input type="password" name="password" class="form-control" required id="inputEmail">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="inputName2" class="col-sm-2 col-form-label">Konfirmasi Kata Sandi Baru</label>
                                                        <div class="col-sm-10">
                                                            <input type="password" class="form-control" required id="inputName2">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="offset-sm-2 col-sm-10">
                                                            <button type="submit" name="change_password" class="btn btn-outline-success">Ubah Kata Sandi</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <!-- /.tab-pane -->
                                        </div>
                                        <!-- /.tab-content -->
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.nav-tabs-custom -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </div>
                    <!-- /.container-fluid -->
                </section>
                <!-- /.content -->

            <?php } ?>
        </div>
        <!-- /.content-wrapper -->
        <!-- Kaki Utama -->
        <footer class="main-footer">
            <div class="float-left">
                &copy; 2023 - NUSA Media
            </div>
            <div class="float-right d-none d-sm-inline">
                Developed by Pramudya Sensei
            </div>
        </footer>
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
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- AdminLTE untuk tujuan demo -->
    <script src="dist/js/demo.js"></script>
</body>

</html>
