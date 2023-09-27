<?php
session_start();
include('conf/config.php');
include('conf/checklogin.php');
check_login();
$admin_id = $_SESSION['admin_id'];

if (isset($_POST['withdrawal'])) {
    $tr_code = $_POST['tr_code'];
    $account_id = $_GET['account_id'];
    $acc_name = $_POST['acc_name'];
    $account_number = $_GET['account_number'];
    $acc_type = $_POST['acc_type'];
    $tr_type  = $_POST['tr_type'];
    $tr_status = $_POST['tr_status'];
    $client_id  = $_GET['client_id'];
    $client_name  = $_POST['client_name'];
    $client_national_id  = $_POST['client_national_id'];
    $transaction_amt = $_POST['transaction_amt'];
    $client_phone = $_POST['client_phone'];
    $keterangan = $_POST['keterangan'];
    $notification_details = "$client_name Has Withdrawn $ $transaction_amt From Bank Account $account_number";

    // Handle withdrawal process
    $result = "SELECT SUM(transaction_amt) FROM iB_Transactions WHERE account_id=?";
    $stmt = $mysqli->prepare($result);
    $stmt->bind_param('i', $account_id);
    $stmt->execute();
    $stmt->bind_result($amt);
    $stmt->fetch();
    $stmt->close();

    if ($transaction_amt > $amt) {
        $err = "You Do Not Have Sufficient Funds In Your Account. Your Existing Amount is $ $amt";
    } else {
        // Insert Captured information to a database table
        $query = "INSERT INTO iB_Transactions (tr_code, account_id, acc_name, account_number, acc_type, tr_type, tr_status, client_id, client_name, client_national_id, transaction_amt, client_phone, keterangan) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $notification = "INSERT INTO iB_notifications (notification_details) VALUES (?)";
        $stmt = $mysqli->prepare($query);
        $notification_stmt = $mysqli->prepare($notification);

        // Bind parameters
        $rc = $stmt->bind_param('sssssssssssss', $tr_code, $account_id, $acc_name, $account_number, $acc_type, $tr_type, $tr_status, $client_id, $client_name, $client_national_id, $transaction_amt, $client_phone, $keterangan);
        $rc = $notification_stmt->bind_param('s', $notification_details);

        $stmt->execute();
        $notification_stmt->execute();

        // Declare a variable which will be passed to alert function
        if ($stmt && $notification_stmt) {
            $success = "Funds Withdrawn";
        } else {
            $err = "Please Try Again Or Try Later";
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

        <!-- Main Sidebar Container -->
        <?php include("dist/_partials/sidebar.php"); ?>

        <!-- Content Wrapper. Contains page content -->
        <?php
        $account_id = $_GET['account_id'];
        $ret = "SELECT * FROM iB_bankAccounts WHERE account_id = ? ";
        $stmt = $mysqli->prepare($ret);
        $stmt->bind_param('i', $account_id);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        $cnt = 1;
        while ($row = $res->fetch_object()) {
        ?>
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Penarikan Dana</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="pages_dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="pages_deposits">Keuangan TASNU</a></li>
                                    <li class="breadcrumb-item"><a href="pages_withdrawals">Penarikan</a></li>
                                    <li class="breadcrumb-item active"><?php echo $row->acc_name; ?></li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <!-- left column -->
                            <div class="col-md-12">
                                <!-- general form elements -->
                                <div class="card card-purple">
                                    <div class="card-header">
                                        <h3 class="card-title">Isi Semua Bidang</h3>
                                    </div>
                                    <!-- form start -->
                                    <form method="post" enctype="multipart/form-data" role="form">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4 form-group">
                                                    <label for="exampleInputEmail1">Nama Santri</label>
                                                    <input type="text" readonly name="client_name" value="<?php echo $row->client_name; ?>" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                                <div class="col-md-4 form-group">
                                                    <label for="exampleInputPassword1">Nomor Induk</label>
                                                    <input type="text" readonly value="<?php echo $row->client_national_id; ?>" name="client_national_id" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                                <div class="col-md-4 form-group">
                                                    <label for="exampleInputEmail1">Nomor Wali Santri</label>
                                                    <input type="text" readonly name="client_phone" value="<?php echo $row->client_phone; ?>" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4 form-group">
                                                    <label for="exampleInputEmail1">Nama Rekening</label>
                                                    <input type="text" readonly name="acc_name" value="<?php echo $row->acc_name; ?>" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                                <div class="col-md-4 form-group">
                                                    <label for="exampleInputPassword1">Nomor Rekening</label>
                                                    <input type="text" readonly value="<?php echo $row->account_number; ?>" name="account_number" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                                <div class="col-md-4 form-group">
                                                    <label for="exampleInputEmail1">Tipe Rekening | Kategori</label>
                                                    <input type="text" readonly name="acc_type" value="<?php echo $row->acc_type; ?>" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 form-group">
                                                    <label for="exampleInputEmail1">Kode Transaksi</label>
                                                    <?php
                                                    // PHP function to generate random account number
                                                    $length = 20;
                                                    $_transcode =  substr(str_shuffle('0123456789QWERgfdsazxcvbnTYUIOqwertyuioplkjhmPASDFGHJKLMNBVCXZ'), 1, $length);
                                                    ?>
                                                    <input type="text" name="tr_code" readonly value="<?php echo $_transcode; ?>" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label for="exampleInputPassword1">Jumlah Penarikan (Rp)</label>
                                                    <input type="text" name="transaction_amt" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                                <div class="col-md-4 form-group" style="display:none">
                                                    <label for="exampleInputPassword1">Tipe Transaksi</label>
                                                    <input type="text" name="tr_type" value="Withdrawal" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                                <div class="col-md-4 form-group" style="display:none">
                                                    <label for="exampleInputPassword1">Status Transaksi</label>
                                                    <input type="text" name="tr_status" value="Success " required class="form-control" id="exampleInputEmail1">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 form-group">
                                                    <label for="exampleInputPassword1">Keterangan Transaksi</label>
                                                    <textarea name="keterangan" required class="form-control" id="exampleInputPassword1"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                        <div class="card-footer">
                                            <button type="submit" name="withdrawal" class="btn btn-success">Tarik Dana</button>
                                            <a href="http://localhost:8080/internetbanking/admin/pages_withdrawals.php" class="btn btn-primary">Kembali</a>
                                        </div>
                                    </form>
                                </div>
                                <!-- /.card -->
                            </div>
                            <!-- /.container-fluid -->
                        </div>
                </section>
                <!-- /.content -->
            </div>
        <?php } ?>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
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
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            bsCustomFileInput.init();
        });
    </script>
</body>

</html>
