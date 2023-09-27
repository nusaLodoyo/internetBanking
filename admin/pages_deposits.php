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

    <!-- Kontainer Sidebar Utama -->
    <?php include("dist/_partials/sidebar.php"); ?>

    <!-- Content Wrapper. Berisi konten halaman -->
    <div class="content-wrapper">
      <!-- Header Konten (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Setoran</h1>
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
                <h3 class="card-title">Pilih rekening untuk melakukan setoran</h3>
              </div>
              <div class="card-body">
                <table id="example1" class="table table-hover table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Nama</th>
                      <th>Nomor Rekening</th>
                      <th>Kelas</th> <!-- Mengganti "Rate" menjadi "Kelas" -->
                      <th>Jenis Rekening</th>
                      <th>Pemilik Rekening</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // Mengambil semua data iB_Accs
                    $ret = "SELECT * FROM iB_bankAccounts ";
                    $stmt = $mysqli->prepare($ret);
                    $stmt->execute();
                    $res = $stmt->get_result();
                    $cnt = 1;
                    while ($row = $res->fetch_object()) {
                      // Memotong timestamp menjadi DD-MM-YYYY: H-M-S
                      $dateOpened = $row->created_at;
                    ?>

                      <tr>
                        <td><?php echo $cnt; ?></td>
                        <td><?php echo $row->acc_name; ?></td>
                        <td><?php echo $row->account_number; ?></td>
                        <td><?php echo $row->client_adr; ?></td> <!-- Mengganti "Rate" menjadi "Kelas" -->
                        <td><?php echo $row->acc_type; ?></td>
                        <td><?php echo $row->client_name; ?></td>
                        <td>
                          <a class="btn btn-success btn-sm" href="pages_deposit_money.php?account_id=<?php echo $row->account_id; ?>&account_number=<?php echo $row->account_number; ?>&client_id=<?php echo $row->client_id; ?>">
                            <i class="fas fa-money-bill-alt"></i>
                            <i class="fas fa-upload"></i>
                            Setoran
                          </a>
                        </td>
                      </tr>
                    <?php $cnt = $cnt + 1;
                    } ?>
                  </tbody>
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

    <!-- Kontrol Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Konten sidebar kontrol di sini -->
    </aside>
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
  <!-- Script halaman -->
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
