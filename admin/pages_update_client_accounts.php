<?php
session_start();
include('conf/config.php');
include('conf/checklogin.php');
check_login();
$admin_id = $_SESSION['admin_id'];
// Mendaftar akun baru
if (isset($_POST['update_account'])) {
    // Klien membuka akun
    $acc_name = $_POST['acc_name'];
    $account_number = $_POST['account_number'];
    $acc_type = $_POST['acc_type'];
    $acc_rates = $_POST['acc_rates'];
    $acc_status = $_POST['acc_status'];
    $acc_amount = $_POST['acc_amount'];
    $account_id  = $_GET['account_id'];
    $client_national_id = $_POST['client_national_id'];
    $client_name = $_POST['client_name'];
    $client_phone = $_POST['client_phone'];
    $client_number = $_POST['client_number'];
    $client_email  = $_POST['client_email'];
    $client_adr  = $_POST['client_adr'];

    // Memasukkan informasi yang ditangkap ke tabel database
    $query = "UPDATE  iB_bankAccounts  SET acc_name=?, account_number=?, acc_type=?, acc_rates=?, acc_status=?, acc_amount=?, client_name=?, client_national_id=?, client_phone=?, client_number=?, client_email=?, client_adr=? WHERE account_id =?";
    $stmt = $mysqli->prepare($query);
    // Mengikat parameter
    $rc = $stmt->bind_param('ssssssssssssi', $acc_name, $account_number, $acc_type, $acc_rates, $acc_status, $acc_amount,  $client_name, $client_national_id, $client_phone, $client_number, $client_email, $client_adr, $account_id);
    $stmt->execute();

    // Mendeklarasikan variabel yang akan dilewatkan ke fungsi peringatan
    if ($stmt) {
        $success = "Akun TASNUSA Diperbarui";
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
        $account_id = $_GET['account_id'];
        $ret = "SELECT * FROM  iB_bankAccounts WHERE account_id = ? ";
        $stmt = $mysqli->prepare($ret);
        $stmt->bind_param('i', $account_id);
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
                                <h1>Perbarui Akun iBanking <?php echo $row->client_name; ?></h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="pages_dashboard.php">Dasbor</a></li>
                                    <li class="breadcrumb-item"><a href="pages_open_acc.php">Akun iBanking</a></li>
                                    <li class="breadcrumb-item"><a href="pages_open_acc.php">Kelola</a></li>
                                    <li class="breadcrumb-item active"><?php echo $row->client_name; ?></li>
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
                                                <div class=" col-md-6 form-group">
                                                    <label for="exampleInputEmail1">Nama Klien</label>
                                                    <input type="text" readonly name="client_name" value="<?php echo $row->client_name; ?>" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                                <div class=" col-md-6 form-group">
                                                    <label for="exampleInputPassword1">Nomor Klien</label>
                                                    <input type="text" readonly name="client_number" value="<?php echo $row->client_number; ?>" class="form-control" id="exampleInputPassword1">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class=" col-md-6 form-group">
                                                    <label for="exampleInputEmail1">Nomor Telepon Klien</label>
                                                    <input type="text" readonly name="client_phone" value="<?php echo $row->client_phone; ?>" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                                <div class=" col-md-6 form-group">
                                                    <label for="exampleInputPassword1">Nomor Identitas Nasional Klien</label>
                                                    <input type="text" readonly value="<?php echo $row->client_national_id; ?>" name="client_national_id" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class=" col-md-6 form-group">
                                                    <label for="exampleInputEmail1">Email Klien</label>
                                                    <input type="email" readonly name="client_email" value="<?php echo $row->client_email; ?>" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                                <div class=" col-md-6 form-group">
                                                    <label for="exampleInputEmail1">Alamat Klien</label>
                                                    <input type="text" name="client_adr" readonly value="<?php echo $row->client_adr; ?>" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                            </div>
                                            <!-- ./End Detail Pribadi -->

                                            <!--Detail Akun Bank-->

                                            <div class="row">
                                                <div class=" col-md-6 form-group">
                                                    <label for="exampleInputEmail1">Nama Akun</label>
                                                    <input type="text" name="acc_name" value="<?php echo $row->acc_name; ?>" required class="form-control" id="exampleInputEmail1">
                                                </div>

                                                <div class=" col-md-6 form-group">
                                                    <label for="exampleInputEmail1">Nomor Akun</label>
                                                    <input type="text" name="account_number" value="<?php echo $row->account_number; ?>" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class=" col-md-6 form-group">
                                                    <label for="exampleInputEmail1">Tipe Akun</label>
                                                    <select class="form-control" onChange="getiBankAccs(this.value);" name="acc_type">
                                                        <option>Pilih Tipe Akun Apapun</option>
                                                        <?php
                                                        // Mengambil semua iB_Acc_types
                                                        $ret = "SELECT * FROM  iB_Acc_types ORDER BY RAND() ";
                                                        $stmt = $mysqli->prepare($ret);
                                                        $stmt->execute(); //ok
                                                        $res = $stmt->get_result();
                                                        $cnt = 1;
                                                        while ($row = $res->fetch_object()) {

                                                        ?>
                                                            <option value="<?php echo $row->name; ?> "> <?php echo $row->name; ?> </option>
                                                        <?php } ?>
                                                    </select>

                                                </div>
                                                <div class=" col-md-6 form-group">
                                                    <label for="exampleInputEmail1">Tingkat Tipe Akun (%)</label>
                                                    <input type="text" name="acc_rates" readonly required class="form-control" id="AccountRates">
                                                </div>

                                                <div class=" col-md-6 form-group" style="display:none">
                                                    <label for="exampleInputEmail1">Status Akun</label>
                                                    <input type="text" name="acc_status" value="Aktif" readonly required class="form-control">
                                                </div>

                                                <div class=" col-md-6 form-group" style="display:none">
                                                    <label for="exampleInputEmail1">Jumlah Akun</label>
                                                    <input type="text" name="acc_amount" value="0" readonly required class="form-control">
                                                </div>

                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                        <div class="card-footer">
                                            <button type="submit" name="update_account" class="btn btn-success">Perbarui Akun iBanking</button>
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
            <!-- Konten kontrol sidebar ada di sini -->
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
