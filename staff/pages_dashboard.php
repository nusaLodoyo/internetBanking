<?php
session_start();
include('conf/config.php');
include('conf/checklogin.php');
check_login();
$admin_id = $_SESSION['admin_id'];

// Membersihkan notifikasi dan memberi tahu pengguna bahwa mereka telah dihapus
if (isset($_GET['Clear_Notifications'])) {
  $id = intval($_GET['Clear_Notifications']);
  $adn = "DELETE FROM  iB_notifications  WHERE notification_id = ?";
  $stmt = $mysqli->prepare($adn);
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $stmt->close();

  if ($stmt) {
    $info = "Notifikasi Dihapus";
  } else {
    $err = "Coba Lagi Nanti";
  }
}
/*
    Dapatkan semua analitik dasbor 
    dan nilai numerik dari tabel-tabel berbeda
*/

// Mengembalikan jumlah total klien iBank
$result = "SELECT count(*) FROM iB_clients";
$stmt = $mysqli->prepare($result);
$stmt->execute();
$stmt->bind_result($iBClients);
$stmt->fetch();
$stmt->close();

// Mengembalikan jumlah total staf iBank
$result = "SELECT count(*) FROM iB_staff";
$stmt = $mysqli->prepare($result);
$stmt->execute();
$stmt->bind_result($iBStaffs);
$stmt->fetch();
$stmt->close();

// Mengembalikan jumlah total jenis akun iBank
$result = "SELECT count(*) FROM iB_Acc_types";
$stmt = $mysqli->prepare($result);
$stmt->execute();
$stmt->bind_result($iB_AccsType);
$stmt->fetch();
$stmt->close();

// Mengembalikan jumlah total akun iBank
$result = "SELECT count(*) FROM iB_bankAccounts";
$stmt = $mysqli->prepare($result);
$stmt->execute();
$stmt->bind_result($iB_Accs);
$stmt->fetch();
$stmt->close();

// Mengembalikan jumlah total deposit iBank
$result = "SELECT SUM(transaction_amt) FROM iB_Transactions WHERE  tr_type = 'Deposit' ";
$stmt = $mysqli->prepare($result);
$stmt->execute();
$stmt->bind_result($iB_deposits);
$stmt->fetch();
$stmt->close();

// Mengembalikan jumlah total penarikan iBank
$result = "SELECT SUM(transaction_amt) FROM iB_Transactions WHERE  tr_type = 'Penarikan' ";
$stmt = $mysqli->prepare($result);
$stmt->execute();
$stmt->bind_result($iB_withdrawal);
$stmt->fetch();
$stmt->close();

// Mengembalikan jumlah total transfer iBank
$result = "SELECT SUM(transaction_amt) FROM iB_Transactions WHERE  tr_type = 'Transfer' ";
$stmt = $mysqli->prepare($result);
$stmt->execute();
$stmt->bind_result($iB_Transfers);
$stmt->fetch();
$stmt->close();

// Mengembalikan jumlah total saldo awal iBank
$result = "SELECT SUM(transaction_amt) FROM iB_Transactions ";
$stmt = $mysqli->prepare($result);
$stmt->execute();
$stmt->bind_result($acc_amt);
$stmt->fetch();
$stmt->close();
// Dapatkan uang yang tersisa di akun
$TotalBalInAccount = ($iB_deposits)  - (($iB_withdrawal) + ($iB_Transfers));


// Uang iBank di dompet
$result = "SELECT SUM(transaction_amt) FROM iB_Transactions ";
$stmt = $mysqli->prepare($result);
$stmt->execute();
$stmt->bind_result($new_amt);
$stmt->fetch();
$stmt->close();
// Perhitungan Penarikan
?>
<!DOCTYPE html>
<html lang="id">
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
    <div class="content-wrapper">
      <!-- Header Konten (header halaman) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark">Dashboard Admin</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Beranda</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Konten Utama -->
      <section class="content">
        <div class="container-fluid">
          <!-- Kotak-kotak Informasi -->
          <div class="row">

            <!-- Kotak Klien iBank -->
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Klien</span>
                  <span class="info-box-number">
                    <?php echo $iBClients; ?>
                  </span>
                </div>
              </div>
            </div>
            <!-- Kotak Klien iBank -->

            <!-- Kotak Staf iBank -->
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-user-tie"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">Staf</span>
                  <span class="info-box-number">
                    <?php echo $iBStaffs; ?>
                  </span>
                </div>
              </div>
            </div>
            <!-- Kotak Staf iBank -->

            <!-- Perbaikan untuk perangkat kecil saja -->
            <div class="clearfix hidden-md-up"></div>

            <!-- Kotak Jenis Akun iBank -->
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-briefcase"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Jenis Akun</span>
                  <span class="info-box-number"><?php echo $iB_AccsType; ?></span>
                </div>
              </div>
            </div>
            <!-- /.Kotak Jenis Akun iBank -->

            <!-- Kotak Akun iBank -->
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-purple elevation-1"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Akun</span>
                  <span class="info-box-number"><?php echo $iB_Accs; ?></span>
                </div>
              </div>
            </div>
            <!-- Kotak Akun iBank -->
          </div>

          <div class="row">
            <!-- Kotak Deposit iBank -->
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-upload"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Deposit</span>
                  <span class="info-box-number">
                  Rp. <?php echo number_format($iB_deposits, 0, ',', '.'); ?></span>
                </div>
              </div>
            </div>
            <!-- Kotak Deposit iBank -->

            <!-- Kotak Penarikan iBank -->
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-download"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">Penarikan</span>
                  <span class="info-box-number">Rp. <?php echo number_format($iB_withdrawal, 0, ',', '.'); ?></span>
                </div>
              </div>
            </div>
            <!-- Kotak Penarikan iBank -->

            <!-- Perbaikan untuk perangkat kecil saja -->
            <div class="clearfix hidden-md-up"></div>

            <!-- Kotak Transfer iBank -->
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-random"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Transfer</span>
                  <span class="info-box-number">Rp. <?php echo number_format($iB_Transfers, 0, ',', '.'); ?></span>
                </div>
              </div>
            </div>
            <!-- /.Kotak Transfer iBank -->

            <!-- Kotak Saldo -->
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-purple elevation-1"><i class="fas fa-money-bill-alt"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Saldo Dompet</span>
                  <span class="info-box-number">Rp. <?php echo number_format($TotalBalInAccount, 0, ',', '.'); ?></span>
                </div>
              </div>
            </div>
            <!-- /.Kotak Saldo -->
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header">
                  <h5 class="card-title">Rekapitulasi Transaksi</h5>
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
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="table-responsive">
                        <table class="table table-hover table-bordered table-striped">
                          <thead>
                            <tr>
                              <th>Kode Transaksi</th>
                              <th>No. Akun</th>
                              <th>Jenis</th>
                              <th>Jumlah</th>
                              <th>Pemilik Akun</th>
                              <th>Timestamp</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            //Dapatkan transaksi terbaru 
                            $ret = "SELECT * FROM `iB_Transactions` ORDER BY `iB_Transactions`.`created_at` DESC ";
                            $stmt = $mysqli->prepare($ret);
                            $stmt->execute(); //ok
                            $res = $stmt->get_result();
                            $cnt = 1;
                            while ($row = $res->fetch_object()) {
                              /* Potong Timestamp Transaksi ke 
                                *  Format yang Dapat Dimengerti Pengguna DD-MM-YYYY :
                                */
                              $transTstamp = $row->created_at;
                              //Lakukan beberapa sihir kecil di sini
                              if ($row->tr_type == 'Deposit') {
                                $alertClass = "<span class='badge badge-success'>$row->tr_type</span>";
                              } elseif ($row->tr_type == 'Penarikan') {
                                $alertClass = "<span class='badge badge-danger'>$row->tr_type</span>";
                              } else {
                                $alertClass = "<span class='badge badge-warning'>$row->tr_type</span>";
                              }
                            ?>
                              <tr>
                                <td><?php echo $row->tr_code; ?></a></td>
                                <td><?php echo $row->account_number; ?></td>
                                <td><?php echo $alertClass; ?></td>
                                <td>Rp. <?php echo number_format($row->transaction_amt, 0, ',', '.'); ?></td>
                                <td><?php echo $row->client_name; ?></td>
                                <td><?php echo date("d-M-Y h:m:s ", strtotime($transTstamp)); ?></td>
                              </tr>

                            <?php } ?>

                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  <!-- /.row -->
                </div>
                <!-- /.card-body -->
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

    <!-- Sidebar Kontrol -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Konten sidebar kontrol di sini -->
    </aside>
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->

  <!-- SKRIP YANG DIBUTUHKAN -->
  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- overlayScrollbars -->
  <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.js"></script>

  <!-- SKRIP TAMBAHAN -->
  <script src="dist/js/demo.js"></script>

  <!-- PLUGINS HALAMAN -->
  <!-- jQuery Mapael -->
  <script src="plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
  <script src="plugins/raphael/raphael.min.js"></script>
  <script src="plugins/jquery-mapael/jquery.mapael.min.js"></script>
  <script src="plugins/jquery-mapael/maps/usa_states.min.js"></script>
  <!-- ChartJS -->
  <script src="plugins/chart.js/Chart.min.js"></script>

  <!-- SKRIP HALAMAN -->
  <script src="dist/js/pages/dashboard2.js"></script>

  <!-- Muat Canvas -->

  <!-- Footer -->
  <footer class="main-footer footer" style="text-align: center; padding: 10px 0;">
    <p style="margin: 0; font-size: 14px;">Dikembangkan dengan OpenAI</p>
    <p style="margin: 0; font-size: 14px;"><strong>&copy; 2023 <a href="#">iBanking TASNUSA</a> </strong> by @nandosensei</p>
  </footer>
  <!-- /.footer -->

</body>

</html>
