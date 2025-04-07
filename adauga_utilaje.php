<?php
session_start();
if (!isset($_SESSION['username'])) {
    die("Eroare: Nu sunteți autentificat. <a href='login.php'>Loghează-te</a>");
}

$servername = "localhost";
$dbusername = "root";
$dbpassword = "root";
$dbname = "roleplay";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) {
    die("Conexiune eșuată: " . $conn->connect_error);
}

$username = $_SESSION['username'];
$firma_id = $_POST['firma_id'];
$utilaje_nou = trim($_POST['utilaje']);

// Verificăm dacă firma aparține utilizatorului
$sql_check_firma = "SELECT * FROM firme WHERE id = ? AND proprietar_firma = ?";
$stmt_check_firma = $conn->prepare($sql_check_firma);
$stmt_check_firma->bind_param("is", $firma_id, $username);
$stmt_check_firma->execute();
$result = $stmt_check_firma->get_result();
$firma_existenta = $result->fetch_assoc();
$stmt_check_firma->close();

if ($firma_existenta) {
    $utilaje_curente = $firma_existenta['utilaje_firma'];
    if ($utilaje_curente) {
        $utilaje_actualizate = $utilaje_curente . ", " . $utilaje_nou;
    } else {
        $utilaje_actualizate = $utilaje_nou;
    }

    // Actualizăm utilajele în baza de date
    $sql_update_utilaje = "UPDATE firme SET utilaje_firma = ? WHERE id = ?";
    $stmt_update_utilaje = $conn->prepare($sql_update_utilaje);
    $stmt_update_utilaje->bind_param("si", $utilaje_actualizate, $firma_id);
    
    if ($stmt_update_utilaje->execute()) {
        echo "Utilajele au fost adăugate cu succes!";
    } else {
        echo "Eroare la adăugarea utilajelor: " . $stmt_update_utilaje->error;
    }

    $stmt_update_utilaje->close();
} else {
    echo "Eroare: Nu aveți permisiunea de a adăuga utilaje la această firmă.";
}

$conn->close();
?>
