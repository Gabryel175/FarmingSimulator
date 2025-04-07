<?php
// Start session pentru a prelua username-ul din sesiune
session_start();

// Verificăm dacă utilizatorul este autentificat
if (!isset($_SESSION['username'])) {
    die("Eroare: Nu sunteți autentificat. <a href='login.php'>Loghează-te</a>");
}

// Conectarea la baza de date
$servername = "localhost";
$dbusername = "root";
$dbpassword = "root";
$dbname = "roleplay";

// Creăm conexiunea
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Verificăm conexiunea
if ($conn->connect_error) {
    die("Conexiune eșuată: " . $conn->connect_error);
}

// Preluăm datele din formular
$nume_firma = $_POST['nume_firma'];
$tip_firma = $_POST['tip_firma'];
$username = $_SESSION['username'];

// Prețurile firmelor
$preturi_firme = [
    'transport' => 3500,
    'ferma' => 5000,
    'lemn' => 4000,
    'productie' => 6500
];

// Calculăm prețul firmei selectate
$pret_firma = $preturi_firme[$tip_firma];

// Start transaction
$conn->begin_transaction();

try {
    // Preparam interogarea SQL pentru adăugarea firmei
    $sql_insert = "INSERT INTO firme (nume_firma, proprietar_firma, tip_firma) VALUES (?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("sss", $nume_firma, $username, $tip_firma);
    $stmt_insert->execute();
    $stmt_insert->close();

    // Actualizăm soldul utilizatorului
    $sql_update_balance = "UPDATE users SET balanta = balanta - ? WHERE username = ?";
    $stmt_update = $conn->prepare($sql_update_balance);
    $stmt_update->bind_param("is", $pret_firma, $username);
    $stmt_update->execute();
    $stmt_update->close();

    // Inserăm tranzacția în tabelul tranzactii
    $motiv = "Deschidere firma " . $tip_firma;
    $sql_insert_tranzactie = "INSERT INTO tranzactii (nume, suma, motiv, data_tranzactie) VALUES (?, ?, ?, CURRENT_TIMESTAMP)";
    $stmt_tranzactie = $conn->prepare($sql_insert_tranzactie);
    $stmt_tranzactie->bind_param("sds", $username, $pret_firma, $motiv);
    $stmt_tranzactie->execute();
    $stmt_tranzactie->close();

    // Commit transaction
    $conn->commit();

    echo "Firma a fost adăugată și soldul a fost actualizat cu succes! Tranzacția a fost înregistrată.";
} catch (Exception $e) {
    // Rollback transaction in case of error
    $conn->rollback();
    echo "Eroare la procesarea tranzacției: " . $e->getMessage();
}

$conn->close();
?>
