<?php
session_start();
include('conf/config.php');
include('conf/checklogin.php');
check_login();
$staff_id = $_SESSION['staff_id'];
// Menghapus staf
if (isset($_GET['deleteClient'])) {
  $id = intval($_GET['deleteClient']);
  $adn = "DELETE FROM  iB_clients  WHERE client_id = ?";
  $stmt = $mysqli->prepare($adn);
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $stmt->close();

  if ($stmt) {
    $info = "Akun Santri Dihapus";
  } else {
    $err = "Coba Lagi Nanti";
  }
}
?>
<!DOCTYPE html>
<html>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<?php include("dist/_partials/head.php"); ?>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->
    <?php include("dist/_partials/nav.php"); ?>
    <!-- /.navbar -->

    <!-- Kontainer Sidebar Utama -->
    <?php include("dist/_partials/sidebar.php"); ?>

    <!-- Content Wrapper. Berisi konten halaman -->
    <div class="content-wrapper">
      <!-- Header Konten (Header Halaman) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Santri NUSA</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="pages_dashboard.php">Dasbor</a></li>
                <li class="breadcrumb-item"><a href="pages_manage_clients.php">Santri</a></li>
                <li class="breadcrumb-item active">Kelola Santri</li>
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
                <h3 class="card-title">Pilih salah satu opsi tindakan untuk mengelola data santri</h3>
              </div>
              <div class="card-body">
                <table id="example1" class="table table-hover table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Nama</th>
                      <th>Kode Unik</th>
                      <th>Nomor Induk</th>
                      <th>Kontak</th>
                      <th>Email</th>
                      <th>Kelas</th>
                      <th>Tindakan</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // Mengambil semua klien iBank
                    $ret = "SELECT * FROM  iB_clients ORDER BY RAND() ";
                    $stmt = $mysqli->prepare($ret);
                    $stmt->execute(); //ok
                    $res = $stmt->get_result();
                    $cnt = 1;
                    while ($row = $res->fetch_object()) {

                    ?>

                      <tr>
                        <td><?php echo $cnt; ?></td>
                        <td><?php echo $row->name; ?></td>
                        <td><?php echo $row->client_number; ?></td>
                        <td><?php echo $row->national_id; ?></td>
                        <td><?php echo $row->phone; ?></td>
                        <td><?php echo $row->email; ?></td>
                        <td><?php echo $row->address; ?></td>
                        <td>
                          <a class="btn btn-success btn-sm" href="pages_view_client.php?client_number=<?php echo $row->client_number; ?>">
                            <i class="fas fa-cogs"></i>
                            Kelola
                          </a>

                          <a class="btn btn-danger btn-sm" href="pages_manage_clients.php?deleteClient=<?php echo $row->client_id; ?>">
                            <i class="fas fa-trash"></i>
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
    <!-- /.content-wrapper -->
    <!-- Footer -->
    <footer class="main-footer">
      <div class="float-left">
        &copy; 2023 - NUSA Media
      </div>
      <div class="float-right d-none d-sm-inline">
        Developed by Pramudya Sensei
      </div>
    </footer>
    <!-- /.footer -->

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
