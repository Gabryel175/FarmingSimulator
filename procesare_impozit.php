<?php
session_start();
$conn = new mysqli("localhost", "root", "root", "roleplay");

if ($conn->connect_error) {
    die("Conexiune esuata: " . $conn->connect_error);
}

$category = $_POST['category'];
$impozit = 0;
$motiv = "";
$nume = $_SESSION['username'];
$user_id = $_SESSION['user_id'];
$data = date('Y-m-d H:i:s');

if ($category === 'vehicul_b') {
    $horsePower = $_POST['horsePower'];
    $impozit = $horsePower * 1;
    $motiv = "Vehicul Cat B";
} elseif ($category === 'vehicul_c') {
    $horsePower = $_POST['horsePower'];
    $impozit = $horsePower * 1.5;
    $motiv = "Vehicul Cat C";
} elseif ($category === 'tractor_utilaj') {
    $horsePower = $_POST['horsePower'];
    $impozit = $horsePower * 2;
    $motiv = "Tractor/Utilaj";
} elseif ($category === 'teren_intravilan') {
    $areaSize = $_POST['areaSize'];
    $impozit = $areaSize * 0.4;
    $motiv = "Teren Intravilan";
} elseif ($category === 'teren_extravilan') {
    $areaSize = $_POST['areaSize'];
    $impozit = $areaSize * 0.2;
    $motiv = "Teren Extravilan";
} elseif ($category === 'firma_transport') {
    $numEmployees = $_POST['numEmployees'];
    $annualRevenue = $_POST['annualRevenue'];
    $numEquipment = $_POST['numEquipment'];
    $impozit = 50 * $numEmployees + 0.09 * $annualRevenue + 50 * $numEquipment;
    $motiv = "Firmă Transport";
} elseif ($category === 'firma_ferma') {
    $annualRevenue = $_POST['annualRevenueFarmLumber'];
    $numEquipment = $_POST['numEquipmentFarmLumber'];
    $impozit = 50 * $numEquipment + 0.11 * $annualRevenue;
    $motiv = "Firmă Fermă";
} elseif ($category === 'firma_lemne') {
    $annualRevenue = $_POST['annualRevenueFarmLumber'];
    $numEquipment = $_POST['numEquipmentFarmLumber'];
    $impozit = 50 * $numEquipment + 0.1 * $annualRevenue;
    $motiv = "Firmă Lemne";
} elseif ($category === 'firma_productie') {
    $numEmployees = $_POST['numEmployees'];
    $annualRevenue = $_POST['annualRevenue'];
    $numEquipment = $_POST['numEquipment'];
    $impozit = 50 * $numEmployees + 0.1 * $annualRevenue + 50 * $numEquipment;
    $motiv = "Firmă Producție";
}
$motiv=$motiv . " Impozit";
// Verificăm balanta utilizatorului
$sql_balance = "SELECT balanta FROM users WHERE id = $user_id";
$result_balance = $conn->query($sql_balance);
$row_balance = $result_balance->fetch_assoc();
$balanta = $row_balance['balanta'];

if ($balanta >= $impozit) {
    // Scădem impozitul din balanta utilizatorului
    $noua_balanta = $balanta - $impozit;
    $sql_update_balance = "UPDATE users SET balanta = $noua_balanta WHERE id = $user_id";
    $conn->query($sql_update_balance);

    // Inregistrăm tranzacția
    $impozit=-$impozit;
    $sql_insert = "INSERT INTO tranzactii (nume, data_tranzactie, suma, motiv) VALUES ('$nume', '$data', $impozit, '$motiv')";
    $conn->query($sql_insert);

    echo "Tranzacție realizată cu succes!<br>";
    echo "Nume: $nume<br>";
    echo "Tip impozit: $motiv<br>";
    echo "Data: $data<br>";
    echo "Suma plătită: $impozit";
    echo "<a href='index.php'>Mergi inapoi</a>";
} else {
    echo "Fonduri insuficiente pentru a realiza tranzacția.";
}

$conn->close();
?>
