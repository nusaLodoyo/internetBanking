<?php
session_start();
include('conf/config.php');
include('conf/checklogin.php');
check_login();
$staff_id = $_SESSION['staff_id'];
// Nonaktifkan rekening bank
if (isset($_GET['deleteBankAcc'])) {
  $id = intval($_GET['deleteBankAcc']);
  $adn = "DELETE FROM  iB_bankAccounts  WHERE account_id = ?";
  $stmt = $mysqli->prepare($adn);
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $stmt->close();

  if ($stmt) {
    $info = "Akun Tasnusa Ditutup";
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

    <!-- Kontainer Sidebar Utama -->
    <?php include("dist/_partials/sidebar.php"); ?>

    <!-- Konten Wrapper. Berisi konten halaman -->
    <div class="content-wrapper">
      <!-- Header Konten (Header Halaman) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Atur Akun Tasnusa</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="pages_dashboard.php">Dasbor</a></li>
                <li class="breadcrumb-item"><a href="pages_manage_acc_openings.php">Akun iBank</a></li>
                <li class="breadcrumb-item active">Kelola Akun</li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Konten Utama -->
      <section class="content">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Pilih salah satu opsi tindakan untuk mengelola akun Anda</h3>
              </div>
              <div class="card-body">
                <table id="example1" class="table table-hover table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Nama</th>
                      <th>Nomor Rekening</th>
                      <th>Rate</th>
                      <th>Jenis Akun</th>
                      <th>Pemilik Akun</th>
                      <th>Tanggal Dibuka</th>
                      <th>Tindakan</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // Ambil semua Akun iBank
                    $ret = "SELECT * FROM  iB_bankAccounts ORDER BY RAND() ";
                    $stmt = $mysqli->prepare($ret);
                    $stmt->execute(); //ok
                    $res = $stmt->get_result();
                    $cnt = 1;
                    while ($row = $res->fetch_object()) {
                      // Trim Timestamp ke DD-MM-YYYY : H-M-S
                      $dateOpened = $row->created_at;

                    ?>

                      <tr>
                        <td><?php echo $cnt; ?></td>
                        <td><?php echo $row->acc_name; ?></td>
                        <td><?php echo $row->account_number; ?></td>
                        <td><?php echo $row->acc_rates; ?>%</td>
                        <td><?php echo $row->acc_type; ?></td>
                        <td><?php echo $row->client_name; ?></td>
                        <td><?php echo date("d-M-Y", strtotime($dateOpened)); ?></td>
                        <td>
                          <a class="btn btn-success btn-sm" href="pages_update_client_accounts.php?account_id=<?php echo $row->account_id; ?>">
                            <i class="fas fa-cogs"></i>
                            <!-- <i class="fas fa-briefcase"></i> -->
                            Kelola
                          </a>

                          <a class="btn btn-danger btn-sm" href="pages_manage_acc_openings.php?deleteBankAcc=<?php echo $row->account_id; ?>">
                            <i class="fas fa-times"></i>
                            <!-- <i class="fas fa-briefcase"></i> -->
                            Tutup Akun
                          </a>


                        </td>

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

    <!-- Sidebar Kontrol -->
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
  <!-- DataTables -->
  <script src="plugins/datatables/jquery.dataTables.js"></script>
  <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>
  <!-- AdminLTE untuk demo -->
  <script src="dist/js/demo.js"></script>
  <!-- Skrip halaman -->
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
</body>

</html>
