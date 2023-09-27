<?php
session_start();
include('conf/config.php');
include('conf/checklogin.php');
check_login();
$admin_id = $_SESSION['admin_id'];

// Include PhpSpreadsheet library
require_once 'vendor/autoload.php';

// Initialize PHPSpreadsheet
$excel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

// Register new accounts
if (isset($_POST['create_staff_account'])) {
    // ... (Kode untuk menambahkan akun santri seperti sebelumnya)
}

// Upload Excel file and add data to the table
if (isset($_POST['upload_excel'])) {
    // Check if a file was uploaded
    if ($_FILES['excel_file']['error'] == 0) {
        $file_name = $_FILES['excel_file']['name'];
        $file_tmp = $_FILES['excel_file']['tmp_name'];

        // Check if the uploaded file is an Excel file
        $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
        if (in_array($file_ext, ['xls', 'xlsx'])) {
            // Load the Excel file
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($file_tmp);
            $spreadsheet = $reader->load($file_tmp);
            $worksheet = $spreadsheet->getActiveSheet();

            // Initialize row count
            $rowCount = 1;

            // Initialize success and error arrays for tracking
            $successData = [];
            $errorData = [];
            $duplicateData = [];

            // Iterate through the rows and add data to the table
            foreach ($worksheet->getRowIterator() as $row) {
                if ($row->getRowIndex() == 1) {
                    // Skip the header row
                    continue;
                }

                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);

                $data = [];
                foreach ($cellIterator as $cell) {
                    $data[] = $cell->getValue();
                }

                // Check for duplicate data in the database (Nomor Induk and Email)
                $query = "SELECT COUNT(*) as count FROM iB_clients WHERE client_number = ? OR email = ?";
                $stmt = $mysqli->prepare($query);
                $stmt->bind_param('ss', $data[1], $data[4]);
                $stmt->execute();
                $result = $stmt->get_result();
                $count = $result->fetch_assoc()['count'];

                if ($count > 0) {
                    $duplicateData[] = $data[0];
                    continue; // Skip inserting duplicate data
                }

                // Insert data into the database
                $query = "INSERT INTO iB_clients (name, national_id, client_number, phone, email, password, address, profile_pic) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $mysqli->prepare($query);

                $hashed_password = sha1(md5($data[5])); // Hash the password
                $default_profile_pic = "pp.png";

                $rc = $stmt->bind_param(
                    'ssssssss',
                    $data[0], // Name
                    $data[3], // National ID
                    $data[1], // Client Number
                    $data[2], // Phone
                    $data[4], // Email
                    $hashed_password,
                    $data[6], // Address
                    $default_profile_pic
                );

                $stmt->execute();

                if ($stmt) {
                    $rowCount++;
                    $successData[] = $data[0];
                } else {
                    $errorData[] = $data[0];
                }
            }

            // Status Upload
            $statusUpload = "Status Upload:<br>";
            $statusUpload .= "Jumlah data berhasil diupload: " . count($successData) . " data.<br>";
            $statusUpload .= "Jumlah data gagal diupload karena duplikat Nomor Induk atau Email: " . count($duplicateData) . " data.<br>";
            if (count($duplicateData) > 0) {
                $statusUpload .= "Data yang gagal diupload karena duplikat: " . implode(', ', $duplicateData) . ".<br>";
            }
        } else {
            $err = "File yang diunggah bukan file Excel.";
        }
    } else {
        $err = "Terjadi kesalahan saat mengunggah file.";
    }
}

// Generate Excel template with headers
if (isset($_POST['download_template'])) {
    // Define Excel headers
    $headers = ['Nama Santri', 'Nomor Induk', 'Kontak', 'NIK', 'Email', 'Password', 'Alamat', 'Foto Profil Santri'];

    // Set header row in the Excel
    $excel->getActiveSheet()->fromArray($headers, NULL, 'A1');

    // Protect the header row
    $excel->getActiveSheet()->getStyle('A1:H1')->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_PROTECTED);

    // Output Excel to the browser
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Santri_Template.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($excel, 'Xlsx');
    $writer->save('php://output');
    exit;
}
?>

<!DOCTYPE html>
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
                            <h1>Buat Akun Santri</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="pages_dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="pages_add_client.php">Santri</a></li>
                                <li class="breadcrumb-item active">Tambahkan</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <!-- left column -->
                        <div class="col-md-6">
                            <!-- general form elements -->
                            <div class="card card-purple">
                                <div class="card-header">
                                    <h3 class="card-title">Isi Semua Bidang</h3>
                                </div>
                                <!-- form start -->
                                <form method="post" enctype="multipart/form-data" role="form">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="excel_file">Unggah Excel:</label>
                                            <input type="file" name="excel_file" id="excel_file" accept=".xls,.xlsx" required>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                    <div class="card-footer">
                                        <button type="submit" name="upload_excel" class="btn btn-success">Upload Excel</button>
                                        <button type="submit" name="download_template" class="btn btn-primary">Download Template</button>
                                    </div>
                                </form>
                            </div>
                            <!-- /.card -->
                        </div>

                        <!-- right column -->
                        <div class="col-md-6">
                            <!-- Status Upload -->
                            <?php if (isset($statusUpload)) : ?>
                                <div class="card card-info">
                                    <div class="card-header">
                                        <h3 class="card-title">Status Upload</h3>
                                    </div>
                                    <div class="card-body">
                                        <?php echo $statusUpload; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
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
    <!-- bs-custom-file-input -->
    <script src="plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
</body>

</html>
