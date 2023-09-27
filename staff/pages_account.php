<?php
session_start();
include('conf/config.php');
include('conf/checklogin.php');
check_login();
$staff_id = $_SESSION['staff_id'];

// Perbarui akun pengguna yang masuk
if (isset($_POST['update_staff_account'])) {
    // Registrasi Staff
    $name = $_POST['name'];
    $staff_id = $_SESSION['staff_id'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    //$password = sha1(md5($_POST['password']));
    $sex  = $_POST['sex'];

    $profile_pic  = $_FILES["profile_pic"]["name"];
    move_uploaded_file($_FILES["profile_pic"]["tmp_name"], "../admin/dist/img/" . $_FILES["profile_pic"]["name"]);

    // Memasukkan informasi yang diambil ke dalam tabel database
    $query = "UPDATE iB_staff SET name=?, phone=?, email=?, sex=?, profile_pic=? WHERE staff_id=?";
    $stmt = $mysqli->prepare($query);
    // Ikatan parameter
    $rc = $stmt->bind_param('sssssi', $name, $phone, $email, $sex, $profile_pic, $staff_id);
    $stmt->execute();

    // Mendeklarasikan variabel yang akan digunakan dalam fungsi alert
    if ($stmt) {
        $success = "Akun Staff Diperbarui";
    } else {
        $err = "Silakan Coba Lagi atau Coba Nanti";
    }
}

// Ganti kata sandi
if (isset($_POST['change_staff_password'])) {
    $password = sha1(md5($_POST['password']));
    $staff_id = $_SESSION['staff_id'];
    // Memasukkan ke dalam tabel tertentu dalam database
    $query = "UPDATE iB_staff  SET password=? WHERE  staff_id=?";
    $stmt = $mysqli->prepare($query);
    // Ikatan parameter
    $rc = $stmt->bind_param('si', $password, $staff_id);
    $stmt->execute();
    // Mendeklarasikan variabel yang akan digunakan dalam fungsi alert
    if ($stmt) {
        $success = "Kata Sandi Staff Diperbarui";
    } else {
        $err = "Silakan Coba Lagi atau Coba Nanti";
    }
}
?>

<!-- Kunjungi freesourcecodes.buzz untuk lebih banyak proyek! -->
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
            <!-- Header Konten dengan detail pengguna yang masuk (header halaman) -->
            <?php
            $staff_id = $_SESSION['staff_id'];
            $ret = "SELECT * FROM  iB_staff  WHERE staff_id = ? ";
            $stmt = $mysqli->prepare($ret);
            $stmt->bind_param('i', $staff_id);
            $stmt->execute(); // ok
            $res = $stmt->get_result();
            while ($row = $res->fetch_object()) {
                // Mengatur gambar default pengguna yang masuk jika mereka belum memperbarui foto mereka
                if ($row->profile_pic == '') {
                    $profile_picture = "
                        <img class='img-fluid'
                        src='../admin/dist/img/user_icon.png'
                        alt='Foto profil pengguna'>
                        ";
                } else {
                    $profile_picture = "
                        <img class=' img-fluid'
                        src='../admin/dist/img/$row->profile_pic'
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
                                    <li class="breadcrumb-item"><a href="pages_manage.php">Staff TASNUSA</a></li>
                                    <li class="breadcrumb-item"><a href="pages_manage.php">Kelola</a></li>
                                    <li class="breadcrumb-item active"><?php echo $row->name; ?></li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <!-- Konten utama -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-3">

                                <!-- Foto Profil -->
                                <div class="card card-purple card-outline">
                                    <div class="card-body box-profile">
                                        <div class="text-center">
                                            <?php echo $profile_picture; ?>
                                        </div>

                                        <h3 class="profile-username text-center"><?php echo $row->name; ?></h3>

                                        <p class="text-muted text-center">Staff @iBanking</p>

                                        <ul class="list-group list-group-unbordered mb-3">
                                            <li class="list-group-item">
                                                <b>Email: </b> <a class="float-right"><?php echo $row->email; ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Telepon: </b> <a class="float-right"><?php echo $row->phone; ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Nomor Staff: </b> <a class="float-right"><?php echo $row->staff_number; ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Jenis Kelamin: </b> <a class="float-right"><?php echo $row->sex; ?></a>
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
                                            <li class="nav-item"><a class="nav-link" href="#Change_Password" data-toggle="tab">Ganti Kata Sandi</a></li>
                                        </ul>
                                    </div><!-- /.card-header -->
                                    <div class="card-body">
                                        <div class="tab-content">
                                            <!-- / Perbarui Profil -->
                                            <div class="tab-pane active" id="update_Profile">
                                                <form method="post" enctype="multipart/form-data" class="form-horizontal">
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
                                                        <label for="inputName2" class="col-sm-2 col-form-label">Kontak</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" class="form-control" required name="phone" value="<?php echo $row->phone; ?>" id="inputName2">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="inputName2" class="col-sm-2 col-form-label">Foto Profil</label>
                                                        <div class="input-group col-sm-10">
                                                            <div class="custom-file">
                                                                <input type="file" name="profile_pic" class=" form-control custom-file-input" id="exampleInputFile">
                                                                <label class="custom-file-label  col-form-label" for="exampleInputFile">Pilih berkas</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="inputName2" class="col-sm-2 col-form-label">Jenis Kelamin</label>
                                                        <div class="col-sm-10">
                                                            <select class="form-control" name="sex">
                                                                <option>Laki-laki</option>
                                                                <option>Perempuan</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="offset-sm-2 col-sm-10">
                                                            <button name="update_staff_account" type="submit" class="btn btn-outline-success">Perbarui Akun</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>

                                            <!-- / Ganti Kata Sandi -->
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
                                                            <button type="submit" name="change_staff_password" class="btn btn-outline-success">Ganti Kata Sandi</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <!-- /.tab-pane -->
                                        </div>
                                        <!-- /.tab-content -->
                                    </div><!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </div><!-- /.container-fluid -->
                </section>
                <!-- /.content -->

            <?php } ?>
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
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- AdminLTE untuk tujuan demo -->
    <script src="dist/js/demo.js"></script>
</body>

</html>
