<?php
session_start();
include('conf/config.php');
include('conf/checklogin.php');
check_login();
$admin_id = $_SESSION['admin_id'];
// Mendaftarkan akun baru

if (isset($_POST['deposit'])) {
    $tr_code = $_POST['tr_code'];
    $account_id = $_GET['account_id'];
    $acc_name = $_POST['acc_name'];
    $account_number = $_GET['account_number'];
    $acc_type = $_POST['acc_type'];
    //$acc_amount  = $_POST['acc_amount'];
    $tr_type  = $_POST['tr_type'];
    $tr_status = $_POST['tr_status'];
    $client_id  = $_GET['client_id'];
    $client_name  = $_POST['client_name'];
    $client_national_id  = $_POST['client_national_id'];
    $transaction_amt = $_POST['transaction_amt'];
    $client_phone = $_POST['client_phone'];

    // Beberapa field untuk menyimpan transfer dana
    $receiving_acc_no = $_POST['receiving_acc_no'];
    $receiving_acc_name = $_POST['receiving_acc_name'];
    $receiving_acc_holder = $_POST['receiving_acc_holder'];

    // Pemberitahuan
    $notification_details = "$client_name Telah Mentransfer $ $transaction_amt Dari Rekening Bank $account_number Ke Rekening Bank $receiving_acc_no";


    /*
    * Anda tidak dapat mentransfer uang dari rekening bank yang tidak memiliki saldo, jadi
    * Mari atasi di sini.
    */
    $result = "SELECT SUM(transaction_amt) FROM  iB_Transactions  WHERE account_id=?";
    $stmt = $mysqli->prepare($result);
    $stmt->bind_param('i', $account_id);
    $stmt->execute();
    $stmt->bind_result($amt);
    $stmt->fetch();
    $stmt->close();

    if ($transaction_amt > $amt) {
        $transaction_error  =  "Anda Tidak Memiliki Saldo Cukup Di Rekening Anda Untuk Transfer. Saldo Rekening Anda Saat Ini Adalah $ $amt";
    } else {

        // Memasukkan informasi yang telah ditangkap ke dalam tabel database
        $query = "INSERT INTO iB_Transactions (tr_code, account_id, acc_name, account_number, acc_type,  tr_type, tr_status, client_id, client_name, client_national_id, transaction_amt, client_phone, receiving_acc_no, receiving_acc_name, receiving_acc_holder) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $notification = "INSERT INTO  iB_notifications (notification_details) VALUES (?)";

        $stmt = $mysqli->prepare($query);
        $notification_stmt = $mysqli->prepare($notification);

        // Mengikat parameter
        $rc = $stmt->bind_param('sssssssssssssss', $tr_code, $account_id, $acc_name, $account_number, $acc_type, $tr_type, $tr_status, $client_id, $client_name, $client_national_id, $transaction_amt, $client_phone, $receiving_acc_no, $receiving_acc_name, $receiving_acc_holder);
        $rc = $notification_stmt->bind_param('s', $notification_details);

        $stmt->execute();
        $notification_stmt->execute();

        // Mendeklarasikan variabel yang akan digunakan dalam fungsi peringatan
        if ($stmt && $notification_stmt) {
            $success = "Dana Telah Ditransfer";
        } else {
            $err = "Silakan Coba Lagi atau Coba Nanti";
        }
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
        <?php
        $account_id = $_GET['account_id'];
        $ret = "SELECT * FROM  iB_bankAccounts WHERE account_id = ? ";
        $stmt = $mysqli->prepare($ret);
        $stmt->bind_param('i', $account_id);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        $cnt = 1;
        while ($row = $res->fetch_object()) {
            // Menampilkan saldo rekening
            $result = "SELECT SUM(transaction_amt) FROM  iB_Transactions  WHERE account_id=?";
            $stmt = $mysqli->prepare($result);
            $stmt->bind_param('i', $account_id);
            $stmt->execute();
            $stmt->bind_result($amt);
            $stmt->fetch();
            $stmt->close();
        ?>
            <div class="content-wrapper">
                <!-- Header Konten (Header Halaman) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Transfer Uang</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="pages_dashboard.php">Dasbor</a></li>
                                    <li class="breadcrumb-item"><a href="pages_transfer_money.php">Keuangan</a></li>
                                    <li class="breadcrumb-item"><a href="pages_transfer_money.php">Transfer</a></li>
                                    <li class="breadcrumb-item active"><?php echo $row->acc_name; ?></li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <!-- Konten Utama -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <!-- Kolom Kiri -->
                            <div class="col-md-12">
                                <!-- Elemen Form Umum -->
                                <div class="card card-purple">
                                    <div class="card-header">
                                        <h3 class="card-title">Isi Semua Field</h3>
                                    </div>
                                    <!-- Mulai Form -->
                                    <form method="post" enctype="multipart/form-data" role="form">
                                        <div class="card-body">

                                            <div class="row">
                                                <div class=" col-md-4 form-group">
                                                    <label for="exampleInputEmail1">Nama Klien</label>
                                                    <input type="text" readonly name="client_name" value="<?php echo $row->client_name; ?>" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                                <div class=" col-md-4 form-group">
                                                    <label for="exampleInputPassword1">Nomor ID Nasional Klien</label>
                                                    <input type="text" readonly value="<?php echo $row->client_national_id; ?>" name="client_national_id" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                                <div class=" col-md-4 form-group">
                                                    <label for="exampleInputEmail1">Nomor Telepon Klien</label>
                                                    <input type="text" readonly name="client_phone" value="<?php echo $row->client_phone; ?>" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class=" col-md-4 form-group">
                                                    <label for="exampleInputEmail1">Nama Akun</label>
                                                    <input type="text" readonly name="acc_name" value="<?php echo $row->acc_name; ?>" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                                <div class=" col-md-4 form-group">
                                                    <label for="exampleInputPassword1">Nomor Akun</label>
                                                    <input type="text" readonly value="<?php echo $row->account_number; ?>" name="account_number" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                                <div class=" col-md-4 form-group">
                                                    <label for="exampleInputEmail1">Tipe Akun | Kategori</label>
                                                    <input type="text" readonly name="acc_type" value="<?php echo $row->acc_type; ?>" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class=" col-md-4 form-group">
                                                    <label for="exampleInputEmail1">Kode Transaksi</label>
                                                    <?php
                                                    // Fungsi PHP untuk menghasilkan kode transaksi acak
                                                    $length = 20;
                                                    $_transcode =  substr(str_shuffle('0123456789QWERgfdsazxcvbnTYUIOqwertyuioplkjhmPASDFGHJKLMNBVCXZ'), 1, $length);
                                                    ?>
                                                    <input type="text" name="tr_code" readonly value="<?php echo $_transcode; ?>" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                                <div class=" col-md-4 form-group">
                                                    <label for="exampleInputPassword1">Saldo Akun Saat Ini</label>
                                                    <input type="text" readonly value="<?php echo $amt; ?>" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                                <div class=" col-md-4 form-group">
                                                    <label for="exampleInputPassword1">Jumlah yang Ditransfer($)</label>
                                                    <input type="text" name="transaction_amt" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class=" col-md-4 form-group">
                                                    <label for="exampleInputPassword1">Nomor Rekening Penerima</label>
                                                    <select name="receiving_acc_no" onChange="getiBankAccs(this.value);" required class="form-control">
                                                        <option>Pilih Rekening Penerima</option>
                                                        <?php
                                                        // Mengambil semua akun iB_Accs
                                                        $ret = "SELECT * FROM  iB_bankAccounts ";
                                                        $stmt = $mysqli->prepare($ret);
                                                        $stmt->execute(); //ok
                                                        $res = $stmt->get_result();
                                                        $cnt = 1;
                                                        while ($row = $res->fetch_object()) {
                                                        ?>
                                                            <option><?php echo $row->account_number; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class=" col-md-4 form-group">
                                                    <label for="exampleInputPassword1">Nama Rekening Penerima</label>
                                                    <input type="text" name="receiving_acc_name" required class="form-control" id="ReceivingAcc">
                                                </div>
                                                <div class=" col-md-4 form-group">
                                                    <label for="exampleInputPassword1">Pemegang Rekening Penerima</label>
                                                    <input type="text" name="receiving_acc_holder" required class="form-control" id="AccountHolder">
                                                </div>

                                                <div class=" col-md-4 form-group" style="display:none">
                                                    <label for="exampleInputPassword1">Tipe Transaksi</label>
                                                    <input type="text" name="tr_type" value="Transfer" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                                <div class=" col-md-4 form-group" style="display:none">
                                                    <label for="exampleInputPassword1">Status Transaksi</label>
                                                    <input type="text" name="tr_status" value="Berhasil" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                        <div class="card-footer">
                                            <button type="submit" name="deposit" class="btn btn-success">Transfer Dana</button>
                                        </div>
                                    </form>
                                </div>
                                <!-- /.card -->
                            </div><!-- /.container-fluid -->
                        </div>
                    </div>
                </section>
            </div>
        <?php } ?>
        <!-- /.content-wrapper -->
        <?php include("dist/_partials/footer.php"); ?>

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
</body>

</html>
