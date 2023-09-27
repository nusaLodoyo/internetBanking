<?php
    session_start();
    unset($_SESSION['admin_id']);
    session_destroy();

    // Mengalihkan pengguna ke halaman utama dengan pesan logout
    $_SESSION['success'] = "Anda telah berhasil keluar.";
    header("Location: pages_index.php");
    exit;
?>
