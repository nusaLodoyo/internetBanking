<?php
session_start();
include('conf/config.php');
include('conf/checklogin.php');
check_login();
$client_id = $_SESSION['client_id'];

if (isset($_POST['update_client_account'])) {
    // Ambil client_number dari sesi
    $client_number = $_SESSION['client_number'];

    // Perbarui data klien
    $phone = $_POST['phone'];

    $profile_pic = '';

    // Cek apakah ada file gambar yang diunggah
    if (!empty($_FILES["profile_pic"]["name"])) {
        $profile_pic  = $_FILES["profile_pic"]["name"];
        move_uploaded_file($_FILES["profile_pic"]["tmp_name"], "../admin/dist/img/" . $profile_pic);
    } else {
        // Jika tidak ada file yang diunggah, gunakan foto profil yang ada
        $query_existing_pic = "SELECT profile_pic FROM iB_clients WHERE client_number = ?";
        $stmt_existing_pic = $mysqli->prepare($query_existing_pic);
        $stmt_existing_pic->bind_param('s', $client_number);
        $stmt_existing_pic->execute();
        $stmt_existing_pic->store_result();
        $stmt_existing_pic->bind_result($existing_pic);
        $stmt_existing_pic->fetch();

        if ($stmt_existing_pic->num_rows > 0 && !empty($existing_pic)) {
            $profile_pic = $existing_pic;
        }
    }

    // Masukkan informasi yang telah diperoleh ke tabel database
    $query = "UPDATE iB_clients SET phone=?, profile_pic=? WHERE client_number = ?";
    $stmt = $mysqli->prepare($query);
    // Bind parameter
    $stmt->bind_param('sss', $phone, $profile_pic, $client_number);
    $stmt->execute();

    // Deklarasikan variabel yang akan digunakan pada fungsi alert
    if ($stmt) {
        $success = "Akun Klien Diperbarui";
    } else {
        $err = "Silakan Coba Lagi atau Coba Nanti";
    }
}

// Ganti kata sandi
if (isset($_POST['change_client_password'])) {
    $password = sha1(md5($_POST['password']));
    $client_number = $_SESSION['client_number'];
    // Masukkan data ke tabel database
    $query = "UPDATE iB_clients  SET password=? WHERE  client_number=?";
    $stmt = $mysqli->prepare($query);
    // Bind parameter
    $stmt->bind_param('ss', $password, $client_number);
    $stmt->execute();
    // Deklarasikan variabel yang akan digunakan pada fungsi alert
    if ($stmt) {
        $success = "Kata Sandi Klien Diperbarui";
    } else {
        $err = "Silakan Coba Lagi atau Coba Nanti";
    }
}
?>
<!DOCTYPE html>
<html>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<?php include("dist/_partials/head.php"); ?>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed ">
    <div class="wrapper">
        <!-- Navbar -->
        <?php include("dist/_partials/nav.php"); ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php include("dist/_partials/sidebar.php"); ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header with logged in user details (Page header) -->
            <?php
            $client_id = $_SESSION['client_id'];
            $ret = "SELECT * FROM  iB_clients  WHERE client_id = ? ";
            $stmt = $mysqli->prepare($ret);
            $stmt->bind_param('s', $client_id);
            $stmt->execute();
            $res = $stmt->get_result();
            while ($row = $res->fetch_object()) {
                // Set gambar profil default jika klien belum memperbarui gambar profil mereka
                if ($row->profile_pic == '') {
                    $profile_picture = "
                        <img class='img-fluid'
                        src='../admin/dist/img/user_icon.png'
                        alt='Foto Profil Klien'>
                    ";
                } else {
                    $profile_picture = "
                        <img class=' img-fluid'
                        src='../admin/dist/img/$row->profile_pic'
                        alt='Foto Profil Klien'>
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
                                    <li class="breadcrumb-item"><a href="pages_manage_clients.php">Kelola</a></li>
                                    <li class="breadcrumb-item active"><?php echo $row->name; ?></li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <!-- Main content -->
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

                                        <p class="text-muted text-center">Santri Nurus Salam</p>

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
                                            <li class="nav-item"><a class="nav-link active" href="#update_Profile" data-toggle="tab">Data Profil</a></li>
                                        </ul>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content">
                                            <!-- Perbarui Profil -->
                                            <div class="tab-pane active" id="update_Profile">
                                                <form method="post" enctype="multipart/form-data" class="form-horizontal">
                                                    <div class="form-group row">
                                                        <label for="inputName" class="col-sm-2 col-form-label">Nama</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" name="name" required readonly class="form-control" value="<?php echo $row->name; ?>" id="inputName">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                                                        <div class="col-sm-10">
                                                            <input type="email" name="email" required readonly value="<?php echo $row->email; ?>" class="form-control" id="inputEmail">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="inputName2" class="col-sm-2 col-form-label">NIK</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" class="form-control" required readonly name="national_id" value="<?php echo $row->national_id; ?>" id="inputName2">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="inputName2" class="col-sm-2 col-form-label">Kelas</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" class="form-control" required readonly name="address" value="<?php echo $row->address; ?>" id="inputName2">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="offset-sm-2 col-sm-10">
                                                            <!-- Tombol "Perbarui Akun" tidak ditampilkan -->
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>

                                            <!-- Ubah Kata Sandi -->
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
                                                            <!-- Tombol "Ubah Kata Sandi" tidak ditampilkan -->
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
                                <!-- /.card -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </div><!-- /.container-fluid -->
                </section><!-- /.content -->
            <?php } ?>
        </div>
        <!-- /.content-wrapper -->
        <!-- Footer -->
        <footer class="main-footer footer">
            <div class="float-right d-none d-sm-inline">
                Dikembangkan dengan OpenAI
            </div>
            <strong>Nusa Media &copy; 2023 <a href="#">iBanking TASNUSA</a> </strong> oleh @nandosensei
        </footer>
        <!-- /.footer -->
        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Konten control sidebar disini -->
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
