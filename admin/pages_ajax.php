<?php
include('conf/pdoconfig.php');
if (!empty($_POST["iBankAccountType"])) {
    // Dapatkan tarif rekening bank
    $id = $_POST['iBankAccountType'];
    $stmt = $DB_con->prepare("SELECT * FROM iB_Acc_types WHERE  name = :id");
    $stmt->execute(array(':id' => $id));
?>
<?php
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
?>
<?php echo htmlentities($row['rate']); ?>
<?php
    }
}

if (!empty($_POST["iBankAccNumber"])) {
    // Dapatkan nama rekening bank yang dapat ditransfer
    $id = $_POST['iBankAccNumber'];
    $stmt = $DB_con->prepare("SELECT * FROM iB_bankAccounts WHERE  account_number= :id");
    $stmt->execute(array(':id' => $id));
?>
<?php
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
?>
<?php echo htmlentities($row['acc_name']); ?>
<?php
    }
}

if (!empty($_POST["iBankAccHolder"])) {
    // Dapatkan nama rekening bank yang dapat ditransfer
    $id = $_POST['iBankAccHolder'];
    $stmt = $DB_con->prepare("SELECT * FROM iB_bankAccounts WHERE  account_number= :id");
    $stmt->execute(array(':id' => $id));
?>
<?php
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
?>
<?php echo htmlentities($row['client_name']); ?>
<?php
    }
}
?>
