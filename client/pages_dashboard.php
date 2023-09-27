<?php
session_start();
include('conf/config.php');
include('conf/checklogin.php');
check_login();
$client_id = $_SESSION['client_id'];

// Mendapatkan total jumlah klien iBank
$result = "SELECT count(*) FROM iB_clients";
$stmt = $mysqli->prepare($result);
$stmt->execute();
$stmt->bind_result($iBClients);
$stmt->fetch();
$stmt->close();

// Mendapatkan total jumlah staf iBank
$result = "SELECT count(*) FROM iB_staff";
$stmt = $mysqli->prepare($result);
$stmt->execute();
$stmt->bind_result($iBStaffs);
$stmt->fetch();
$stmt->close();

// Mendapatkan total jumlah tipe Rekening iBank
$result = "SELECT count(*) FROM iB_Acc_types";
$stmt = $mysqli->prepare($result);
$stmt->execute();
$stmt->bind_result($iB_AccsType);
$stmt->fetch();
$stmt->close();

// Mendapatkan total jumlah Rekening iBank
$result = "SELECT count(*) FROM iB_bankAccounts";
$stmt = $mysqli->prepare($result);
$stmt->execute();
$stmt->bind_result($iB_Accs);
$stmt->fetch();
$stmt->close();

// Mendapatkan total jumlah deposit iBank
$result = "SELECT SUM(transaction_amt) FROM iB_Transactions WHERE client_id = ? AND tr_type = 'Deposit'";
$stmt = $mysqli->prepare($result);
$stmt->bind_param('i', $client_id);
$stmt->execute();
$stmt->bind_result($iB_deposits);
$stmt->fetch();
$stmt->close();

// Mendapatkan total jumlah penarikan iBank
$result = "SELECT SUM(transaction_amt) FROM iB_Transactions WHERE client_id = ? AND tr_type = 'Withdrawal'";
$stmt = $mysqli->prepare($result);
$stmt->bind_param('i', $client_id);
$stmt->execute();
$stmt->bind_result($iB_withdrawal);
$stmt->fetch();
$stmt->close();

// Mendapatkan total jumlah saldo awal iBank
$result = "SELECT SUM(transaction_amt) FROM iB_Transactions WHERE client_id = ?";
$stmt = $mysqli->prepare($result);
$stmt->bind_param('i', $client_id);
$stmt->execute();
$stmt->bind_result($acc_amt);
$stmt->fetch();
$stmt->close();

// Dapatkan sisa uang di Rekening
$TotalBalInAccount = ($iB_deposits) - ($iB_withdrawal);

// Jumlah uang iBank di dompet
$result = "SELECT SUM(transaction_amt) FROM iB_Transactions WHERE client_id = ?";
$stmt = $mysqli->prepare($result);
$stmt->bind_param('i', $client_id);
$stmt->execute();
$stmt->bind_result($new_amt);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <?php include("dist/_partials/head.php"); ?>
    <style>
        /* Tambahkan CSS sesuai kebutuhan untuk penataan tampilan */
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #f8f9fa;
            text-align: center;
            padding: 10px 0;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php include("dist/_partials/nav.php"); ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php include("dist/_partials/sidebar.php"); ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Dasbor Santri</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Beranda</a></li>
                                <li class="breadcrumb-item active">Dasbor Santri</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <!--iBank Deposits -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-upload"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Setoran</span>
                                    <span class="info-box-number">
                                        Rp <?php echo number_format($iB_deposits, 0, '.', '.'); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!----./ iBank Deposits-->

                        <!--iBank Penarikan-->
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-download"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Penarikan</span>
                                    <span class="info-box-number">Rp <?php echo number_format($iB_withdrawal, 0, '.', '.'); ?> </span>
                                </div>
                            </div>
                        </div>
                        <!-- Penarikan-->

                        <!-- fix for small devices only -->
                        <div class="clearfix hidden-md-up"></div>


                        <!--Saldo-->
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-purple elevation-1"><i class="fas fa-money-bill-alt"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Sisa Saldo</span>
                                    <span class="info-box-number">Rp <?php echo number_format($TotalBalInAccount, 0, '.', '.'); ?></span>
                                </div>
                            </div>
                        </div>
                        <!-- ./Saldo-->
                    </div>


                    <!-- Main row -->
                    <div class="row">
                        <!-- Left col -->
                        <div class="col-md-12">
                            <!-- TABEL: Transaksi -->
                            <div class="card">
                                <div class="card-header border-transparent">
                                    <h3 class="card-title">Transaksi Terbaru</h3>

                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover m-0">
                                            <thead>
                                                <tr>
                                                    <th>Kode Transaksi</th>
                                                    <th>Nomor Rekening</th>
                                                    <th>Jenis</th>
                                                    <th>Jumlah</th>
                                                    <th>Pemilik Rekening</th>
                                                    <th>Keterangan</th>
                                                    <th>Waktu</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                //Dapatkan transaksi terbaru;
                                                $result = "SELECT * FROM iB_Transactions WHERE client_id = ? ORDER BY created_at DESC ";
                                                $stmt = $mysqli->prepare($result);
                                                $stmt->bind_param('i', $client_id);
                                                $stmt->execute();
                                                $res = $stmt->get_result();
                                                $cnt = 1;
                                                while ($row = $res->fetch_object()) {
                                                    /* Potong Timestamp Transaksi ke 
                                                    *  Format yang Dapat Dimengerti oleh Pengguna DD-MM-YYYY :
                                                    */
                                                    $transTstamp = $row->created_at;
                                                    //Lakukan sedikit pengolahan data di sini
                                                    if ($row->tr_type == 'Deposit') {
                                                        $alertClass = "<span class='badge badge-success'>$row->tr_type</span>";
                                                    } elseif ($row->tr_type == 'Withdrawal') {
                                                        $alertClass = "<span class='badge badge-danger'>$row->tr_type</span>";
                                                    } else {
                                                        $alertClass = "<span class='badge badge-warning'>$row->tr_type</span>";
                                                    }
                                                ?>
                                                    <tr>
                                                        <td><?php echo $row->tr_code; ?></td>
                                                        <td><?php echo $row->account_number; ?></td>
                                                        <td><?php echo $alertClass; ?></td>
                                                        <td>Rp <?php echo number_format($row->transaction_amt, 0, '.', '.'); ?></td>
                                                        <td><?php echo $row->client_name; ?></td>
                                                        <td><?php echo $row->keterangan; ?></td>
                                                        <td><?php echo date("d-M-Y h:m:s ", strtotime($transTstamp)); ?></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- /.table-responsive -->
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer clearfix">
                                    <a href="pages_transactions_engine.php" class="btn btn-sm btn-info float-left">Lihat Semua</a>
                                </div>
                                <!-- /.card-footer -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!--/. container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->

    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->
    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.js"></script>

    <!-- OPTIONAL SCRIPTS -->
    <script src="dist/js/demo.js"></script>

    <!-- PAGE PLUGINS -->
    <!-- jQuery Mapael -->
    <script src="plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
    <script src="plugins/raphael/raphael.min.js"></script>
    <script src="plugins/jquery-mapael/jquery.mapael.min.js"></script>
    <script src="plugins/jquery-mapael/maps/usa_states.min.js"></script>
    <!-- ChartJS -->
    <script src="plugins/chart.js/Chart.min.js"></script>

    <!-- PAGE SCRIPTS -->
    <script src="dist/js/pages/dashboard2.js"></script>

    <!--Muat Canvas JS -->
    <script src="plugins/canvasjs.min.js"></script>
    <!--Muat Beberapa Grafik-->
    <script>
        window.onload = function () {

            var Piechart = new CanvasJS.Chart("PieChart", {
                exportEnabled: false,
                animationEnabled: true,
                title: {
                    text: "Akun Per Jenis Akun"
                },
                legend: {
                    cursor: "pointer",
                    itemclick: explodePie
                },
                data: [{
                    type: "pie",
                    showInLegend: true,
                    toolTipContent: "{name}: <strong>{y}%</strong>",
                    indexLabel: "{name} - {y}%",
                    dataPoints: [{
                        y: <?php
                            //Mengembalikan jumlah total akun yang dibuka dalam jenis akun simpanan
                            $result = "SELECT count(*) FROM iB_bankAccounts WHERE  acc_type ='Savings' AND client_id =? ";
                            $stmt = $mysqli->prepare($result);
                            $stmt->bind_param('i', $client_id);
                            $stmt->execute();
                            $stmt->bind_result($savings);
                            $stmt->fetch();
                            $stmt->close();
                            echo $savings;
                            ?>,
                        name: "Akun Simpanan",
                        exploded: true
                    },

                    {
                        y: <?php
                            //Mengembalikan jumlah total akun yang dibuka dalam jenis akun pensiun
                            $result = "SELECT count(*) FROM iB_bankAccounts WHERE  acc_type ='Pensiun' AND client_id =? ";
                            $stmt = $mysqli->prepare($result);
                            $stmt->bind_param('i', $client_id);
                            $stmt->execute();
                            $stmt->bind_result($Pensiun);
                            $stmt->fetch();
                            $stmt->close();
                            echo $Pensiun;
                            ?>,
                        name: "Akun Pensiun",
                        exploded: true
                    },

                    {
                        y: <?php
                            //Mengembalikan jumlah total akun yang dibuka dalam jenis akun deposito berjangka
                            $result = "SELECT count(*) FROM iB_bankAccounts WHERE  acc_type ='Deposito Berjangka' AND client_id =? ";
                            $stmt = $mysqli->prepare($result);
                            $stmt->bind_param('i', $client_id);
                            $stmt->execute();
                            $stmt->bind_result($Deposito);
                            $stmt->fetch();
                            $stmt->close();
                            echo $Deposito;
                            ?>,
                        name: "Akun Deposito Berjangka",
                        exploded: true
                    },

                    {
                        y: <?php
                            //Mengembalikan jumlah total akun yang dibuka dalam jenis akun deposito berjangka
                            $result = "SELECT count(*) FROM iB_bankAccounts WHERE  acc_type ='Akun Giro' AND client_id = ? ";
                            $stmt = $mysqli->prepare($result);
                            $stmt->bind_param('i', $client_id);
                            $stmt->execute();
                            $stmt->bind_result($Giro);
                            $stmt->fetch();
                            $stmt->close();
                            echo $Giro;
                            ?>,
                        name: "Akun Giro",
                        exploded: true
                    },

                    {
                        y: <?php

                            //Mengembalikan jumlah total akun yang dibuka dalam jenis akun deposito berjangka
                            $result = "SELECT count(*) FROM iB_bankAccounts WHERE  acc_type ='Akun Saat' AND client_id =? ";
                            $stmt = $mysqli->prepare($result);
                            $stmt->bind_param('i', $client_id);
                            $stmt->execute();
                            $stmt->bind_result($Saat);
                            $stmt->fetch();
                            $stmt->close();
                            echo $Saat;
                            ?>,
                        name: "Akun Saat",
                        exploded: true
                    }
                    ]
                }]
            });

            var AccChart = new CanvasJS.Chart("AccountsPerAccountCategories", {
                exportEnabled: false
            });
        }
    </script>
    <!-- /.Muat Beberapa Grafik-->

<!-- Footer -->
<footer class="main-footer footer" style="text-align: center; padding: 10px 0;">
    <p style="margin: 0; font-size: 14px;">Dikembangkan dengan OpenAI</p>
    <p style="margin: 0; font-size: 14px;"><strong>&copy; 2023 <a href="#">iBanking TASNUSA</a> </strong> by @nandosensei</p>
</footer>
<!-- /.footer -->

</body>

</html>
