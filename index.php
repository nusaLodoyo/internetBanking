<?php
include("admin/conf/config.php");
/* Persisit Pengaturan Sistem Pada Brand */
$ret = "SELECT * FROM `iB_SystemSettings` ";
$stmt = $mysqli->prepare($ret);
$stmt->execute();
$res = $stmt->get_result();
while ($sys = $res->fetch_object()) {
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?php echo $sys->sys_name; ?> - <?php echo $sys->sys_tagline; ?></title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tambahkan stylesheet CSS untuk palet warna tema gelap Material Design -->
    <style>
        body {
            background-color: #121212; /* Warna latar belakang gelap sesuai tema Material Design */
            color: #fff; /* Teks putih untuk kontras */
            width: 100%; /* Ukuran body lebih lebar */
        }
        .navbar {
            background-color: #212121; /* Warna latar belakang navbar yang lebih gelap */
        }
        .navbar a.navbar-brand,
        .navbar a.nav-link {
            color: #fff; /* Teks putih */
        }
        .btn-success {
            background-color: #4CAF50; /* Warna hijau sesuai tema Material Design */
            border-color: #4CAF50;
            margin-bottom: 10px; /* Jarak bawah tombol */
        }
        .btn-success:hover {
            background-color: #45a049; /* Warna hijau lebih tua saat dihover */
            border-color: #45a049;
        }
        .logo-container {
            text-align: center;
            margin-top: 50px;
        }
        .logo-container img {
            max-width: 150px; /* Ubah ukuran logo sesuai kebutuhan */
        }
        .marquee {
            font-size: 16px;
            text-align: center;
            padding: 10px;
            background-color: #333; /* Latar belakang abu-abu lebih gelap untuk kontras */
            margin-top: 20px; /* Turunkan posisi marquee */
        }
        /* Tambahkan gaya untuk footer dengan latar belakang hijau tua */
        .footer {
            background-color: #092215; /* Warna hijau tua */
            color: #fff; /* Teks putih */
            padding: 20px 0; /* Ruang padding pada bagian atas dan bawah footer */
            font-size: 14px; /* Perkecil font footer */
            margin-top: 20px; /* Turunkan posisi footer */
        }
        /* Gaya untuk tombol Konfirmasi Transfer */
        .btn-konfirmasi {
            background-color: #FFA500; /* Warna oranye */
            border-color: #FFA500;
            color: #fff; /* Teks putih */
            margin-bottom: 10px; /* Jarak bawah tombol */
        }
        .btn-konfirmasi:hover {
            background-color: #FF8C00; /* Warna oranye lebih tua saat dihover */
            border-color: #FF8C00;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-absolute w-100">
    <div class="container">
        <a class="navbar-brand" href="index.php"><?php echo $sys->sys_name; ?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" target="_blank" href="admin/pages_index.php">Portal Admin</a>
                </li>
                <li class="nav-item active">
                            <a class="nav-link" target="_blank" href="staff/pages_staff_index.php">Portal Pendamping</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" target="_blank" href="client/pages_client_index.php">Portal Santri</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="intro py-5 py-lg-9 position-relative">
    <div class="bg-overlay-dark"></div>
    <div class="intro-content py-6 text-center">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-sm-10 col-md-8 col-lg-6 mx-auto text-center">
                    <div class="logo-container">
                        <img src="nusa.png" alt="Logo">
                    </div>
                    <h1 class="my-3 display-4">TASNUSA</h1>
                    <p class="lead mb-3">
                        "Tabungan Santri Nurus Salam"
                    </p>
                    <!-- Tombol Cek Saldo -->
                    <a class="btn btn-success" href="http://localhost:8080/internetbanking/client/pages_client_index.php">Cek Saldo Santri</a>
                    <!-- /.Tombol Cek Saldo -->
                    <!-- Tombol Konfirmasi Transfer -->
                    <a class="btn btn-konfirmasi" href="https://wa.me/6285878300000" target="_blank">Konfirmasi Transfer</a>
                    <!-- /.Tombol Konfirmasi Transfer -->
                </div>
            </div>
        </div>
    </div>
</div>

<div class="marquee">
    <marquee behavior="scroll" direction="left">
        Transfer uang saku santri melalui Bank BRI 0009-01-000971-56-2 atas nama Pontren Nurul Ulum, simpan bukti transfer lalu kirimkan ke Nomor WA 085-878-300-000 (sertakan nama santri & kelasnya) untuk konfirmasi.
    </marquee>
</div>

<!-- Footer -->
<footer class="main-footer footer" style="text-align: center; padding: 10px 0;">
    <p style="margin: 0; font-size: 14px;">Dikembangkan dengan OpenAI</p>
    <p style="margin: 0; font-size: 14px;"><strong>&copy; 2023 <a href="#">iBanking TASNUSA</a> </strong> by @nandosensei</p>
</footer>
<!-- /.footer -->


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
}
?>
