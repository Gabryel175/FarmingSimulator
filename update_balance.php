<?php
session_start(); // Inițializarea sesiunii

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'Sesiune invalidă']);
    exit();
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
$betAmount = isset($_POST['betAmount']) ? floatval($_POST['betAmount']) : 0;
$winnings = isset($_POST['winnings']) ? floatval($_POST['winnings']) : 0;

if ($betAmount <= 0 || $winnings < 0) {
    echo json_encode(['success' => false, 'message' => 'Date invalide']);
    exit();
}

// Obținem balanța cazinoului
$sqlCasino = "SELECT balance FROM casino WHERE username = ?";
$stmtCasino = $conn->prepare($sqlCasino);
$stmtCasino->bind_param("s", $username);
$stmtCasino->execute();
$resultCasino = $stmtCasino->get_result();
$casinoBalance = $resultCasino->fetch_assoc()['balance'];

// Dacă nu există înregistrare în `casino`, setăm balansul la 0
if ($casinoBalance === null) {
    $casinoBalance = 0.00;
}

// Verificăm dacă există suficienți bani pentru pariuri
if ($casinoBalance < $betAmount) {
    echo json_encode(['success' => false, 'message' => 'Fonduri insuficiente']);
    exit();
}

// Actualizăm balanța cazinoului
$newCasinoBalance = $casinoBalance - $betAmount;
$sqlUpdateCasino = "UPDATE casino SET balance = ? WHERE username = ?";
$stmtUpdateCasino = $conn->prepare($sqlUpdateCasino);
$stmtUpdateCasino->bind_param("ds", $newCasinoBalance, $username);
$stmtUpdateCasino->execute();

// Actualizăm balanța utilizatorului
$sqlUser = "SELECT balanta FROM users WHERE username = ?";
$stmtUser = $conn->prepare($sqlUser);
$stmtUser->bind_param("s", $username);
$stmtUser->execute();
$resultUser = $stmtUser->get_result();
$userBalance = $resultUser->fetch_assoc()['balanta'];

$newUserBalance = $userBalance + $winnings;
$sqlUpdateUser = "UPDATE users SET balanta = ? WHERE username = ?";
$stmtUpdateUser = $conn->prepare($sqlUpdateUser);
$stmtUpdateUser->bind_param("ds", $newUserBalance, $username);
$stmtUpdateUser->execute();

echo json_encode(['success' => true, 'message' => 'Balanța actualizată']);
$conn->close();
?>
