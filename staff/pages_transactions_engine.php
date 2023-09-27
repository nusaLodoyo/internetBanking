<?php
session_start();
include('conf/config.php');
include('conf/checklogin.php');
check_login();
$staff_id = $_SESSION['staff_id'];

// Roll back transaction
if (isset($_GET['RollBack_Transaction'])) {
  $id = intval($_GET['RollBack_Transaction']);
  $adn = "DELETE FROM  iB_Transactions  WHERE tr_id = ?";
  $stmt = $mysqli->prepare($adn);
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $stmt->close();

  if ($stmt) {
    $info = "Transaksi Dibatalkan";
  } else {
    $err = "Coba Lagi Nanti";
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
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Riwayat Transaksi</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="pages_dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="pages_transactions_engine.php">Riwayat Transaksi</a></li>
                <li class="breadcrumb-item active">Transaksi</li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Pilih opsi tindakan apa pun untuk mengelola Transaksi.</h3>
              </div>
              <div class="card-body">
                <table id="example1" class="table table-hover table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Kode Transaksi</th>
                      <th>Nomor Akun</th>
                      <th>Tipe</th>
                      <th>Jumlah</th>
                      <th>Pemilik Akun</th>
                      <th>Timestamp</th>
                      <th>Keterangan</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // Get latest transactions 
                    $ret = "SELECT * FROM `iB_Transactions` ORDER BY `iB_Transactions`.`created_at` DESC ";
                    $stmt = $mysqli->prepare($ret);
                    $stmt->execute(); // Ok
                    $res = $stmt->get_result();
                    $cnt = 1;
                    while ($row = $res->fetch_object()) {
                      /* Trim Transaction Timestamp to 
                            * User Understandable Format  DD-MM-YYYY :
                            */
                      $transTstamp = $row->created_at;
                      // Perform some formatting here
                      if ($row->tr_type == 'Deposit') {
                        $alertClass = "<span class='badge badge-success'>$row->tr_type</span>";
                      } elseif ($row->tr_type == 'Withdrawal') {
                        $alertClass = "<span class='badge badge-danger'>$row->tr_type</span>";
                      } else {
                        $alertClass = "<span class='badge badge-warning'>$row->tr_type</span>";
                      }
                    ?>

                      <tr>
                        <td><?php echo $cnt; ?></td>
                        <td><?php echo $row->tr_code; ?></a></td>
                        <td><?php echo $row->account_number; ?></td>
                        <td><?php echo $alertClass; ?></td>
                        <td>Rp. <?php echo number_format($row->transaction_amt, 0, ',', '.'); ?></td>
                        <td><?php echo $row->client_name; ?></td>
                        <td><?php echo date("d-M-Y h:m:s", strtotime($transTstamp)); ?></td>
                        <td><?php echo $row->keterangan; ?></td>
                      </tr>
                    <?php $cnt = $cnt + 1;
                    } ?>
                    </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </section>
      <!-- /.content -->
    </div>

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
  <!-- DataTables -->
  <script src="plugins/datatables/jquery.dataTables.js"></script>
  <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="dist/js/demo.js"></script>
  <!-- page script -->
  <script>
    $(function() {
      $("#example1").DataTable();
      $('#example2').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
      });
    });
  </script>
  <!-- Footer -->
  <footer class="main-footer footer" style="text-align: center; padding: 10px 0;">
    <p style="margin: 0; font-size: 14px;">Dikembangkan dengan OpenAI</p>
    <p style="margin: 0; font-size: 14px;"><strong>&copy; 2023 <a href="#">iBanking TASNUSA</a> </strong> by @nandosensei</p>
  </footer>
  <!-- /.footer -->
</body>

</html>
