<?php
session_start();
include('conf/config.php');
include('conf/checklogin.php');
check_login();
if (isset($_POST['systemSettings'])) {
  // Penanganan Kesalahan dan Pencegahan Pengiriman Entri Ganda
  $error = 0;
  if (isset($_POST['sys_name']) && !empty($_POST['sys_name'])) {
    $sys_name = mysqli_real_escape_string($mysqli, trim($_POST['sys_name']));
  } else {
    $error = 1;
    $err = "Nama Sistem Tidak Boleh Kosong";
  }
  if (!$error) {
    $id = $_POST['id'];
    $sys_tagline = $_POST['sys_tagline'];
    $sys_logo = $_FILES['sys_logo']['name'];
    move_uploaded_file($_FILES["sys_logo"]["tmp_name"], "dist/img/" . $_FILES["sys_logo"]["name"]);

    $query = "UPDATE iB_SystemSettings SET sys_name =?, sys_logo =?, sys_tagline=? WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('ssss',  $sys_name,  $sys_logo, $sys_tagline, $id);
    $stmt->execute();
    if ($stmt) {
      $success = "Pengaturan Diperbarui" && header("refresh:1; url=pages_system_settings.php");
    } else {
      // Menampilkan pesan jika gagal memperbarui profil
      $info = "Silakan Coba Lagi atau Coba Nanti";
    }
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

    <!-- Main Sidebar Container -->
    <?php include("dist/_partials/sidebar.php"); ?>

    <!-- Konten Wrapper. Berisi konten halaman -->
    <div class="content-wrapper">
      <!-- Header Konten (Header Halaman) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Pengaturan Sistem</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="pages_dashboard.php">Dasbor</a></li>
                <li class="breadcrumb-item active">Pengaturan Sistem</li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->

      <!-- Konten Utama -->
      <section class="content">
        <div class="row">
          <div class="col-12">
            <div class="card card-purple card-outline">
              <div class="card-header">
                <h3 class="card-title">Konfigurasi Ulang Sistem Ini Sesuai Kebutuhan</h3>
              </div>
              <div class="card-body">
                <?php
                /* Menyimpan Pengaturan Sistem Pada Brand */
                $ret = "SELECT * FROM `iB_SystemSettings` ";
                $stmt = $mysqli->prepare($ret);
                $stmt->execute(); //ok
                $res = $stmt->get_result();
                while ($sys = $res->fetch_object()) {
                ?>
                  <form method="post" enctype="multipart/form-data" role="form">
                    <div class="card-body">
                      <div class="row">
                        <div class="form-group col-md-12">
                          <label for="">Nama Perusahaan</label>
                          <input type="text" required name="sys_name" value="<?php echo $sys->sys_name; ?>" class="form-control">
                          <input type="hidden" required name="id" value="<?php echo $sys->id ?>" class="form-control">
                        </div>
                        <div class="form-group col-md-12">
                          <label for="">Tagline Perusahaan</label>
                          <input type="text" required name="sys_tagline" value="<?php echo $sys->sys_tagline; ?>" class="form-control">
                        </div>
                        <div class="form-group col-md-12">
                          <label for="">Logo Sistem</label>
                          <div class="input-group">
                            <div class="custom-file">
                              <input required name="sys_logo" type="file" class="custom-file-input">
                              <label class="custom-file-label" for="exampleInputFile">Pilih file</label>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="text-right">
                      <button type="submit" name="systemSettings" class="btn btn-success">Kirim</button>
                    </div>
                  </form>
                <?php
                } ?>
              </div>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </section>
      <!-- /.content -->
    </div>
    <!-- Footer -->
  <footer class="main-footer footer">
        <div class="float-right d-none d-sm-inline">
            Dikembangkan dengan OpenAI
        </div>
        <strong>Nusa Media &copy; 2023 <a href="#">iBanking TASNUSA</a> </strong> by Pramudya Sensei.
    </footer>
    <!-- /.footer -->

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
  <!-- DataTables -->
  <script src="plugins/datatables/jquery.dataTables.js"></script>
  <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>
  <!-- AdminLTE untuk tujuan demo -->
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
    /* Unggahan File Kustom */
    $(document).ready(function() {
      bsCustomFileInput.init();
    });
  </script>
</body>

</html>
