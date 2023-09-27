<?php
session_start();
include('conf/config.php');
include('conf/checklogin.php');
check_login();
$admin_id = $_SESSION['admin_id'];
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
        /*  Im About to do something stupid buh lets do it
         *  get the sumof all deposits(Money In) then get the sum of all
         *  Transfers and Withdrawals (Money Out).
         * Then To Calculate Balance and rate,
         * Take the rate, compute it and then add with the money in account and 
         * Deduce the Money out
         *
         */

        //get the total amount deposited
        $account_id = $_GET['account_id'];
        $result = "SELECT SUM(transaction_amt) FROM iB_Transactions WHERE  account_id = ? AND  tr_type = 'Deposit' ";
        $stmt = $mysqli->prepare($result);
        $stmt->bind_param('i', $account_id);
        $stmt->execute();
        $stmt->bind_result($deposit);
        $stmt->fetch();
        $stmt->close();

        //get total amount withdrawn
        $account_id = $_GET['account_id'];
        $result = "SELECT SUM(transaction_amt) FROM iB_Transactions WHERE  account_id = ? AND  tr_type = 'Withdrawal' ";
        $stmt = $mysqli->prepare($result);
        $stmt->bind_param('i', $account_id);
        $stmt->execute();
        $stmt->bind_result($withdrawal);
        $stmt->fetch();
        $stmt->close();

        //get total amount transferred
        $account_id = $_GET['account_id'];
        $result = "SELECT SUM(transaction_amt) FROM iB_Transactions WHERE  account_id = ? AND  tr_type = 'Transfer' ";
        $stmt = $mysqli->prepare($result);
        $stmt->bind_param('i', $account_id);
        $stmt->execute();
        $stmt->bind_result($Transfer);
        $stmt->fetch();
        $stmt->close();

        $account_id = $_GET['account_id'];
        $ret = "SELECT * FROM  iB_bankAccounts WHERE account_id =? ";
        $stmt = $mysqli->prepare($ret);
        $stmt->bind_param('i', $account_id);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        $cnt = 1;
        while ($row = $res->fetch_object()) {
            //compute rate
            $banking_rate = ($row->acc_rates) / 100;
            //compute Money out
            $money_out = $withdrawal + $Transfer;
            //compute the balance
            $money_in = $deposit - $money_out;
            //get the rate
            $rate_amt = $banking_rate * $money_in;
            //compute the intrest + balance 
            $totalMoney = $rate_amt + $money_in;
        ?>
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Saldo Rekening iBanking <?php echo $row->client_name; ?></h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="pages_dashboard.php">Dasbor</a></li>
                                    <li class="breadcrumb-item"><a href="pages_balance_enquiries.php">Keuangan</a></li>
                                    <li class="breadcrumb-item"><a href="pages_balance_enquiries.php">Saldo</a></li>
                                    <li class="breadcrumb-item active">Rekening <?php echo $row->client_name; ?></li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <!-- Main content -->
                                <div id="balanceSheet" class="invoice p-3 mb-3">
                                    <!-- title row -->
                                    <div class="row">
                                        <div class="col-12">
                                            <h4>
                                                <i class="fas fa-bank"></i> Perusahaan iBanking - Pemeriksaan Saldo
                                                <small class="float-right">Tanggal: <?php echo date('d/m/Y'); ?></small>
                                            </h4>
                                        </div>
                                        <!-- /.col -->
                                    </div>
                                    <!-- info row -->
                                    <div class="row invoice-info">
                                        <div class="col-sm-6 invoice-col">
                                            Pemegang Rekening
                                            <address>
                                                <strong><?php echo $row->client_name; ?></strong><br>
                                                <?php echo $row->client_number; ?><br>
                                                <?php echo $row->client_email; ?><br>
                                                Telepon: <?php echo $row->client_phone; ?><br>
                                                No. KTP: <?php echo $row->client_national_id; ?>
                                            </address>
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-sm-6 invoice-col">
                                            Detail Rekening
                                            <address>
                                                <strong><?php echo $row->acc_name; ?></strong><br>
                                                No. Rekening: <?php echo $row->account_number; ?><br>
                                                Jenis Rekening: <?php echo $row->acc_type; ?><br>
                                                Tarif Rekening: <?php echo $row->acc_rates; ?> %
                                            </address>
                                        </div>

                                    </div>
                                    <!-- /.row -->

                                    <!-- Table row -->
                                    <div class="row">
                                        <div class="col-12 table-responsive">
                                            <table class="table table-hover table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Setoran</th>
                                                        <th>Penarikan</th>
                                                        <th>Transfer</th>
                                                        <th>Subtotal</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <tr>
                                                        <td>$ <?php echo $deposit; ?></td>
                                                        <td>$ <?php echo $withdrawal; ?></td>
                                                        <td>$ <?php echo $Transfer; ?></td>
                                                        <td>$ <?php echo $money_in; ?></td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                        <!-- /.col -->
                                    </div>
                                    <!-- /.row -->

                                    <div class="row">
                                        <!-- accepted payments column -->
                                        <div
