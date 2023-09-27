<?php
session_start();
include('conf/config.php'); // Mengambil file konfigurasi

if (isset($_POST['login'])) {
  $email = $_POST['email'];
  $password = sha1(md5($_POST['password'])); // Enkripsi password ganda untuk keamanan
  $stmt = $mysqli->prepare("SELECT email, password, client_id  FROM iB_clients   WHERE email=? AND password=?"); // SQL untuk login pengguna
  $stmt->bind_param('ss', $email, $password); // Membind parameter yang diambil
  $stmt->execute(); // Eksekusi bind
  $stmt->bind_result($email, $password, $client_id); // Membind hasil
  $rs = $stmt->fetch();
  $_SESSION['client_id'] = $client_id; // Menetapkan sesi ke client_id

  if ($rs) { // Jika berhasil
    header("location:pages_dashboard.php");
  } else {
    $err = "Access Denied Please Check Your Credentials";
  }
}

// Mendapatkan pengaturan sistem dari database
$ret = "SELECT * FROM `iB_SystemSettings` ";
$stmt = $mysqli->prepare($ret);
$stmt->execute(); // OK
$res = $stmt->get_result();
while ($auth = $res->fetch_object()) {
?>

<!DOCTYPE html>
<html>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<?php include("dist/_partials/head.php"); ?>
<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo">
      <p><?php echo $auth->sys_name; ?></p>
    </div>

    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">Tabungan Santri Nurus Salam</p>

        <form method="post">
          <div class="input-group mb-3">
            <input type="email" name="email" class="form-control" placeholder="Email">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" id="password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
              <div class="input-group-text">
                <span class="fas fa-eye" id="togglePassword"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-8">
              <div class="icheck-primary">
                <input type="checkbox" id="remember">
                <label for="remember">
                  Ingat Saya
                </label>
              </div>
            </div>
            <div class="col-4">
              <button type="submit" name="login" class="btn btn-success btn-block">Log In</button>
            </div>
          </div>
        </form>
        <div class="mt-3">
          <a href="http://localhost:8080/internetbanking/" class="btn btn-primary btn-block">Kembali ke Halaman Utama</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Tulisan Berjalan -->
  <marquee behavior="scroll" direction="left" style="text-align: center; padding: 10px 0; background-color: #f8f9fa; font-size: 14px;">
    LAYANAN KHUSUS CEK SALDO TABUNGAN SANTRI PONDOK PESANTREN NURUS SALAM LODOYO | PASTIKAN ANDA SUDAH MENGISI FORM KONFIRMASI (BERISI NAMA SANTRI, KELAS, & BUKTI TRANSFER) KEPADA ADMIN SETELAH MENGIRIMKAN UANG SAKU VIA TRANSFER BANK
  </marquee>
  <!-- /.Tulisan Berjalan -->

  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>

  <script>
    const passwordField = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');

    togglePassword.addEventListener('click', function () {
      const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordField.setAttribute('type', type);
      togglePassword.classList.toggle('fa-eye-slash');
      togglePassword.classList.toggle('fa-eye');
    });
  </script>
</body>
</html>

<?php
} ?>
