<?php
session_start();
include('conf/config.php');
include('conf/checklogin.php');
check_login();
$admin_id = $_SESSION['admin_id'];
if (isset($_GET['deleteBankAccType'])) {
  $id = intval($_GET['deleteBankAccType']);
  $adn = "DELETE FROM  iB_Acc_types  WHERE acctype_id = ?";
  $stmt = $mysqli->prepare($adn);
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $stmt->close();

  if ($stmt) {
    $info = "Jenis Akun iBanking Dihapus";
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
              <h1>Jenis Akun iBanking</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="pages_dashboard.php">Dasbor</a></li>
                <li class="breadcrumb-item"><a href="pages_manage_accs.php">Jenis Akun iBank</a></li>
                <li class="breadcrumb-item active">Kelola Jenis Akun</li>
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
                <h3 class="card-title">Pilih salah satu opsi tindakan untuk mengelola jenis akun Anda</h3>
              </div>
              <div class="card-body">
                <table id="example1" class="table table-hover table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Nama</th>
                      <th>Rate</th>
                      <th>Kode</th>
                      <th>Tindakan</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // Ambil semua Jenis Akun iBank
                    $ret = "SELECT * FROM  iB_Acc_types ORDER BY RAND() ";
                    $stmt = $mysqli->prepare($ret);
                    $stmt->execute(); //ok
                    $res = $stmt->get_result();
                    $cnt = 1;
                    while ($row = $res->fetch_object()) {

                    ?>

                      <tr>
                        <td><?php echo $cnt; ?></td>
                        <td><?php echo $row->name; ?></td>
                        <td><?php echo $row->rate; ?>%</td>
                        <td><?php echo $row->code; ?></td>

                        <td>
                          <a class="btn btn-success btn-sm" href="pages_update_accs.php?code=<?php echo $row->code; ?>">
                            <i class="fas fa-cogs"></i>
                            <!-- <i class="fas fa-briefcase"></i> -->
                            Kelola
                          </a>

                          <a class="btn btn-danger btn-sm" href="pages_manage_accs.php?deleteBankAccType=<?php echo $row->acctype_id; ?>">
                            <i class="fas fa-trash"></i>
                            <!-- <i class="fas fa-briefcase"></i> -->
                            Hapus
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
