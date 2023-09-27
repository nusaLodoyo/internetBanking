<?php
session_start();
include('conf/config.php');
include('conf/checklogin.php');
check_login();
$admin_id = $_SESSION['admin_id'];

// Mendaftarkan akun baru
if (isset($_POST['open_account'])) {
    // Klien membuka akun
    $acc_name = $_POST['acc_name'];
    $account_number = $_POST['account_number'];
    $acc_type = $_POST['acc_type'];
    $acc_rates = $_POST['acc_rates'];
    $acc_status = $_POST['acc_status'];
    $acc_amount = $_POST['acc_amount'];
    $client_id  = $_GET['client_id'];
    $client_national_id = $_POST['client_national_id'];
    $client_name = $_POST['client_name'];
    $client_phone = $_POST['client_phone'];
    $client_number = $_POST['client_number'];
    $client_email  = $_POST['client_email'];
    $client_adr  = $_POST['client_adr'];

    // Memasukkan informasi yang ditangkap ke tabel database
    $query = "INSERT INTO iB_bankAccounts (acc_name, account_number, acc_type, acc_rates, acc_status, acc_amount, client_id, client_name, client_national_id, client_phone, client_number, client_email, client_adr) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $stmt = $mysqli->prepare($query);
    // Menyimpan parameter
    $rc = $stmt->bind_param('sssssssssssss', $acc_name, $account_number, $acc_type, $acc_rates, $acc_status, $acc_amount, $client_id, $client_name, $client_national_id, $client_phone, $client_number, $client_email, $client_adr);
    $stmt->execute();

    // Mendeklarasikan variabel yang akan digunakan untuk pesan peringatan
    if ($stmt) {
        $success = "Akun iBank Terbuka";
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

        <!-- Kontainer Utama Sidebar -->
        <?php include("dist/_partials/sidebar.php"); ?>

        <!-- Konten Wrapper. Berisi konten halaman -->
        <?php
        $client_id = $_GET['client_id'];
        $ret = "SELECT * FROM  iB_clients WHERE client_id = ? ";
        $stmt = $mysqli->prepare($ret);
        $stmt->bind_param('i', $client_id);
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
                                <h1>Buka Akun iBank <?php echo $row->name; ?></h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="pages_dashboard.php">Dasbor</a></li>
                                    <li class="breadcrumb-item"><a href="pages_open_acc.php">Akun iBank</a></li>
                                    <li class="breadcrumb-item"><a href="pages_open_acc.php">Buka</a></li>
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
                                        <h3 class="card-title">Isi Semua Bidang</h3>
                                    </div>
                                    <!-- Mulai formulir -->
                                    <form method="post" enctype="multipart/form-data" role="form">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class=" col-md-6 form-group">
                                                    <label for="exampleInputEmail1">Nama Klien</label>
                                                    <input type="text" readonly name="client_name" value="<?php echo $row->name; ?>" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                                <div class=" col-md-6 form-group">
                                                    <label for="exampleInputPassword1">Nomor Klien</label>
                                                    <input type="text" readonly name="client_number" value="<?php echo $row->client_number; ?>" class="form-control" id="exampleInputPassword1">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class=" col-md-6 form-group">
                                                    <label for="exampleInputEmail1">Nomor Telepon Klien</label>
                                                    <input type="text" readonly name="client_phone" value="<?php echo $row->phone; ?>" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                                <div class=" col-md-6 form-group">
                                                    <label for="exampleInputPassword1">Nomor ID Nasional Klien</label>
                                                    <input type="text" readonly value="<?php echo $row->national_id; ?>" name="client_national_id" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class=" col-md-6 form-group">
                                                    <label for="exampleInputEmail1">Email Klien</label>
                                                    <input type="email" readonly name="client_email" value="<?php echo $row->email; ?>" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                                <div class=" col-md-6 form-group">
                                                    <label for="exampleInputEmail1">Alamat Klien</label>
                                                    <input type="text" name="client_adr" readonly value="<?php echo $row->address; ?>" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                            </div>
                                            <!-- Akhir Detail Personal -->

                                            <!-- Detail Akun Bank -->
                                            <div class="row">
                                                <div class=" col-md-6 form-group">
                                                    <label for="exampleInputEmail1">Jenis Akun</label>
                                                    <select class="form-control" onChange="getiBankAccs(this.value);" name="acc_type">
                                                        <option value="TASNUSA" selected>TASNUSA</option>
                                                    </select>
                                                </div>
                                                <div class=" col-md-6 form-group">
                                                    <label for="exampleInputEmail1">Tarif Jenis Akun (%)</label>
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
                                            <!-- Akhir Detail Akun Bank -->

                                            <div class="row">
                                                <div class=" col-md-6 form-group">
                                                    <label for="exampleInputEmail1">Nama Akun</label>
                                                    <input type="text" name="acc_name" required class="form-control" id="exampleInputEmail1" value="<?php echo $row->name; ?>">
                                                </div>

                                                <div class=" col-md-6 form-group">
                                                    <label for="exampleInputEmail1">Nomor Akun</label>
                                                    <?php
                                                    // Fungsi PHP untuk menghasilkan nomor akun acak
                                                    $length = 12;
                                                    $_accnumber =  substr(str_shuffle('0123456789'), 1, $length);
                                                    ?>
                                                    <input type="text" name="account_number" value="<?php echo $_accnumber; ?>" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                        <div class="card-footer">
                                            <button type="submit" name="open_account" class="btn btn-success">Buka Akun iBank</button>
                                        </div>
                                    </form>
                                </div>
                                <!-- /.card -->
                            </div><!-- /.container-fluid -->
                </section>
                <!-- /.content -->
            </div>
        <?php } ?>

        <!-- Kontrol Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Konten sidebar kontrol di sini -->
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
    <!-- AdminLTE untuk demo -->
    <script src="dist/js/demo.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            bsCustomFileInput.init();
        });
    <!-- Footer -->
    <footer class="main-footer footer">
        <div class="float-right d-none d-sm-inline">
            Dikembangkan dengan OpenAI
        </div>
        <strong>Nusa Media &copy; 2023 <a href="#">iBanking TASNUSA</a> </strong> by Pramudya Sensei.
    </footer>
    <!-- /.footer -->
    </script>
    
</body>

</html>
