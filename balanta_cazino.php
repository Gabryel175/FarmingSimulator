<?php
session_start(); // Inițializarea sesiunii

// Conectarea la baza de date
$servername = "localhost";
$dbusername = "root";
$dbpassword = "root";
$dbname = "roleplay";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) {
    die("Conexiune eșuată: " . $conn->connect_error);
}
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    $sqlUser = "SELECT balanta FROM users WHERE username = ?";
    $stmtUser = $conn->prepare($sqlUser);
    $stmtUser->bind_param("s", $username);
    $stmtUser->execute();
    $resultUser = $stmtUser->get_result();
    $userBalance = $resultUser->fetch_assoc()['balanta'];

    $sqlCasino = "SELECT balance FROM casino WHERE username = ?";
    $stmtCasino = $conn->prepare($sqlCasino);
    $stmtCasino->bind_param("s", $username);
    $stmtCasino->execute();
    $resultCasino = $stmtCasino->get_result();
    $casinoBalance = $resultCasino->fetch_assoc()['balance'];

    if ($casinoBalance === null) {
        $casinoBalance = 0.00;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $amount = $_POST['amount'];
        $currentDateTime = date('Y-m-d H:i:s'); 

        if (isset($_POST['add'])) {
            // Adăugare fonduri în cazino
            if ($userBalance >= $amount) {
                $userBalance -= $amount;
                $casinoBalance += $amount;
                $sqlUpdateUser = "UPDATE users SET balanta = ? WHERE username = ?";
                $sqlUpdateCasino = "UPDATE casino SET balance = ? WHERE username = ?";
                $stmtUpdateUser = $conn->prepare($sqlUpdateUser);
                $stmtUpdateCasino = $conn->prepare($sqlUpdateCasino);
                $stmtUpdateUser->bind_param("ds", $userBalance, $username);
                $stmtUpdateCasino->bind_param("ds", $casinoBalance, $username);
                $stmtUpdateUser->execute();
                $stmtUpdateCasino->execute();

                // Inserare în tabelul `tranzactii`
                $sqlInsertTransaction = "INSERT INTO tranzactii (nume, data_tranzactie, suma, motiv) VALUES (?, ?, ?, 'Adaugare cazino')";
                $stmtInsertTransaction = $conn->prepare($sqlInsertTransaction);
                $stmtInsertTransaction->bind_param("ssd", $username, $currentDateTime, $amount);
                $stmtInsertTransaction->execute();
            }
        } elseif (isset($_POST['withdraw'])) {
            // Retragere fonduri din cazino
            if ($casinoBalance >= $amount) {
                $casinoBalance -= $amount;
                $userBalance += $amount;
                $sqlUpdateUser = "UPDATE users SET balanta = ? WHERE username = ?";
                $sqlUpdateCasino = "UPDATE casino SET balance = ? WHERE username = ?";
                $stmtUpdateUser = $conn->prepare($sqlUpdateUser);
                $stmtUpdateCasino = $conn->prepare($sqlUpdateCasino);
                $stmtUpdateUser->bind_param("ds", $userBalance, $username);
                $stmtUpdateCasino->bind_param("ds", $casinoBalance, $username);
                $stmtUpdateUser->execute();
                $stmtUpdateCasino->execute();

                // Inserare în tabelul `tranzactii`
                $sqlInsertTransaction = "INSERT INTO tranzactii (nume, data_tranzactie, suma, motiv) VALUES (?, ?, ?, 'Retragere cazino')";
                $stmtInsertTransaction = $conn->prepare($sqlInsertTransaction);
                $stmtInsertTransaction->bind_param("ssd", $username, $currentDateTime, $amount);
                $stmtInsertTransaction->execute();
            }
        }
    }
} else {
    
    header("Location: login.php");
    exit();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adauga/Retrage bani</title>
    <link rel="icon" type="image/x-icon" href="https://cdn-icons-png.flaticon.com/128/445/445076.png">
</head>
<style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        background-image: url("media/8018766.jpg");
        background-position: top;
        background-size: cover;
        background-repeat: no-repeat;
        height: 100%;
    }

    .navbar {
        position: fixed;
        top: 0;
        width: 100%;
        background-color: #333;
        color: #fff;
        padding: 10px;
        text-align: center;
        z-index: 1000;
    }

    .navbar span {
        margin: 0 15px;
        font-size: 18px;
    }

    .casino-link {
        text-decoration: none;
        font-size: 24px;
        font-weight: bold;
        color: #ffcc00; /* Aurie */
        text-shadow: 0 0 10px #ffcc00, 0 0 20px #ffcc00, 0 0 30px #ffcc00;
        transition: transform 0.3s ease, color 0.3s ease;
    }

    .casino-link:hover {
        color: #ffffff;
        transform: scale(1.2);
        text-shadow: 0 0 15px #ffffff, 0 0 30px #ffffff, 0 0 45px #ffcc00;
    }

    .container {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        padding-top: 120px;
        gap: 20px;
    }

    .card {
        background-color: #222;
        color: #fff;
        border-radius: 10px;
        padding: 20px;
        width: 300px;
        max-height: 200px;
        text-align: center;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
    }

    .card h2 {
        font-size: 24px;
        margin-bottom: 20px;
        color: #ffcc00;
    }

    .card input[type="number"] {
        width: 80%;
        padding: 10px;
        margin-bottom: 20px;
        border: none;
        border-radius: 5px;
        font-size: 16px;
    }

    .card button {
        background-color: #ffcc00;
        color: #222;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .card button:hover {
        background-color: #ffffff;
    }

</style>
<body>
    <div class="navbar">
        <span><?php echo "Slots - User : ".$username; ?></span>
        <span>Balanta: <?php echo number_format($userBalance, 2); ?> $</span>
        <span>Casino Balanta: <?php echo number_format($casinoBalance, 2); ?> Credite</span>
        <span><a href="cazino.php" class="casino-link">🎰</a></span>
    </div>

    <div class="container">
        <div class="card">
            <h2>Adaugă Fonduri</h2>
            <form method="POST">
                <input type="number" name="amount" placeholder="Sumă" required>
                <button type="submit" name="add">Adaugă</button>
            </form>
        </div>

        <div class="card">
            <h2>Retrage Fonduri</h2>
            <form method="POST">
                <input type="number" name="amount" placeholder="Sumă" required>
                <button type="submit" name="withdraw">Retrage</button>
            </form>
        </div>
    </div>
</body>
</html>
