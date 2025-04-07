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

$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;

// Gestionarea pariurilor
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['betAmount'])) {
    if ($username) {
        $betAmount = floatval($_POST['betAmount']);
        $winnings = floatval($_POST['winnings']);

        // Interogare pentru a obține balanța din cazino
        $sqlCasino = "SELECT balance FROM casino WHERE username = ?";
        $stmtCasino = $conn->prepare($sqlCasino);
        $stmtCasino->bind_param("s", $username);
        $stmtCasino->execute();
        $resultCasino = $stmtCasino->get_result();
        $casinoBalance = $resultCasino->fetch_assoc()['balance'];

        if ($casinoBalance === null) {
            $casinoBalance = 0.00;
        }

        // Verificăm dacă există suficienți bani pentru pariuri
        if ($casinoBalance >= $betAmount) {
            // Actualizăm balanța cazinoului
            $newCasinoBalance = $casinoBalance - $betAmount + $winnings;
            $sqlUpdateCasino = "UPDATE casino SET balance = ? WHERE username = ?";
            $stmtUpdateCasino = $conn->prepare($sqlUpdateCasino);
            $stmtUpdateCasino->bind_param("ds", $newCasinoBalance, $username);
            $stmtUpdateCasino->execute();

            // Salvăm istoria pariurilor
            $sqlBetHistory = "INSERT INTO c (username, bet_amount, winnings) VALUES (?, ?, ?)";
            $stmtBetHistory = $conn->prepare($sqlBetHistory);
            $stmtBetHistory->bind_param("sdd", $username, $betAmount, $winnings);
            $stmtBetHistory->execute();

            $response = [
                'success' => true,
                'message' => 'Balanța cazinoului actualizată',
                'newCasinoBalance' => $newCasinoBalance
            ];
        } else {
            $response = ['success' => false, 'message' => 'Fonduri insuficiente'];
        }

        echo json_encode($response);
        exit();
    } else {
        echo json_encode(['success' => false, 'message' => 'Sesiune invalidă']);
        exit();
    }
}

// Verificăm sesiunea
if (!$username) {
    header("Location: login.php");
    exit();
}

// Interogăm balanța utilizatorului
$sqlUser = "SELECT balanta FROM users WHERE username = ?";
$stmtUser = $conn->prepare($sqlUser);
$stmtUser->bind_param("s", $username);
$stmtUser->execute();
$resultUser = $stmtUser->get_result();
$userBalance = $resultUser->fetch_assoc()['balanta'];

// Interogăm balanța cazinoului
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

$conn->close();
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ruletă de Cazino</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #1b1b1b;
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
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

        .roulette-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .wheel {
            width: 200px;
            height: 200px;
            border: 10px solid #fff;
            border-radius: 50%;
            position: relative;
            background: conic-gradient(
                red 0% 10%,
                black 10% 20%,
                red 20% 30%,
                black 30% 40%,
                red 40% 50%,
                black 50% 60%,
                red 60% 70%,
                black 70% 80%,
                red 80% 90%,
                black 90% 100%
            );
        }

        #spinButton {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #f00;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
        }

        #result {
            margin-top: 20px;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <span id="username"><?php echo "Slots - User : " . htmlspecialchars($username); ?></span>
        <span id="userBalance">Balanta: <?php echo number_format($userBalance, 2); ?> $</span>
        <span id="casinoBalance">Casino Balanta: <?php echo number_format($casinoBalance, 2); ?> Credite</span>
    </div>
    
    <div class="roulette-container">
        <div id="wheel" class="wheel"></div>
        <button id="spinButton">Rotește</button>
        <div id="result" class="result"></div>
        <input type="number" id="betAmount" placeholder="Suma pariului" />
        <button id="placeBetButton">Pariază</button>
    </div>
    
    <script>
        document.getElementById('spinButton').addEventListener('click', function() {
            const wheel = document.getElementById('wheel');
            const result = document.getElementById('result');
            const randomDegree = Math.floor(Math.random() * 360);
            wheel.style.transition = 'transform 3s ease-out';
            wheel.style.transform = `rotate(${randomDegree + 3600}deg)`;
            
            setTimeout(() => {
                const winningNumber = Math.floor((randomDegree % 360) / 36) + 1;
                result.textContent = `Ai câștigat numărul ${winningNumber}`;
            }, 3000);
        });

        document.getElementById('placeBetButton').addEventListener('click', function() {
            const betAmount = parseFloat(document.getElementById('betAmount').value);
            const result = document.getElementById('result');

            if (isNaN(betAmount) || betAmount <= 0) {
                result.textContent = 'Introduceți o sumă validă pentru pariu.';
                return;
            }

            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    betAmount: betAmount,
                    winnings: betAmount * 2 // Exemplu simplu de câștig
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    result.textContent = `Pariul a fost acceptat! Noua balanță a cazinoului: ${data.newCasinoBalance} credite.`;
                } else {
                    result.textContent = data.message;
                }
            });
        });
    </script>
</body>
</html>
