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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['betAmount'])) {
    if ($username) {
        $betAmount = floatval($_POST['betAmount']);
        $winnings = floatval($_POST['winnings']);

        // Interogare pentru a obține balanța din cazino din tabelul `casino`
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
        if ($casinoBalance >= $betAmount) {
            // Actualizăm balanța cazinoului
            $newCasinoBalance = $casinoBalance - $betAmount + $winnings;
            $sqlUpdateCasino = "UPDATE casino SET balance = ? WHERE username = ?";
            $stmtUpdateCasino = $conn->prepare($sqlUpdateCasino);
            $stmtUpdateCasino->bind_param("ds", $newCasinoBalance, $username);
            $stmtUpdateCasino->execute();
            $sqlBetHistory = "INSERT INTO c (username, bet_amount, winnings) VALUES (?, ?, ?)";
            $stmtBetHistory = $conn->prepare($sqlBetHistory);
            $stmtBetHistory->bind_param("sdd", $username, $betAmount, $winnings);
            $stmtBetHistory->execute();
            // Pregătim datele de răspuns
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

// Dacă nu există sesiune activă, redirecționăm utilizatorul către pagina de login
if (!$username) {
    header("Location: login.php");
    exit();
}

// Interogare pentru a obține balanța utilizatorului din tabelul `users`
$sqlUser = "SELECT balanta FROM users WHERE username = ?";
$stmtUser = $conn->prepare($sqlUser);
$stmtUser->bind_param("s", $username);
$stmtUser->execute();
$resultUser = $stmtUser->get_result();
$userBalance = $resultUser->fetch_assoc()['balanta'];

// Interogare pentru a obține balanța din cazino din tabelul `casino`
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
    <title>Pacanele</title>
    <link rel="icon" type="image/x-icon" href="https://cdn-icons-png.flaticon.com/128/445/445076.png">
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

        .content {
            margin-top: 60px; /* Pentru a evita suprapunerea cu navbarul fix */
            padding: 20px;
        }

        #slideshow {
            font-size: 70px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background-color: #333;
            padding: 20px;
            border-radius: 10px;
            border: 5px solid #ffd700;
            box-shadow: 0 0 15px rgba(255, 215, 0, 0.8);
            gap: 20px;
            width: 300px;
        }

        .line {
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .betButton {
            background-color: #ffd700;
            color: #1b1b1b;
            border: none;
            padding: 15px 30px;
            font-size: 20px;
            border-radius: 10px;
            cursor: pointer;
            margin: 10px;
            transition: background-color 0.3s;
        }

        .betButton:hover {
            background-color: #e0b800;
        }

        #result {
            font-size: 24px;
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            background-color: #333;
            border-radius: 10px;
            border: 2px solid #ffd700;
            width: 300px;
            box-shadow: 0 0 10px rgba(255, 215, 0, 0.8);
        }
        .winning-symbol {
            border: 3px solid #ffd700;
            border-radius: 50%;
            box-shadow: 0 0 10px rgba(255, 215, 0, 0.8);
            padding: 5px;
        }
    </style>
</head>
<body>

<div class="navbar">
    <span id="username"><?php echo "Slots - User : " . htmlspecialchars($username); ?></span>
    <span id="userBalance">Balanta: <?php echo number_format($userBalance, 2); ?> $</span>
    <span id="casinoBalance">Casino Balanta: <?php echo number_format($casinoBalance, 2); ?> Credite</span>
</div>

<div class="content">
    <div id="slideshow">
        <div class="line">
            <div class="symbol" data-rarity="1000">🏆</div>
            <div class="symbol" data-rarity="500">❤️</div>
            <div class="symbol" data-rarity="500">🚀</div>
        </div>
        <div class="line">
            <div class="symbol" data-rarity="1000">🏆</div>
            <div class="symbol" data-rarity="500">❤️</div>
            <div class="symbol" data-rarity="500">🚀</div>
        </div>
        <div class="line">
            <div class="symbol" data-rarity="1000">🏆</div>
            <div class="symbol" data-rarity="500">❤️</div>
            <div class="symbol" data-rarity="500">🚀</div>
        </div>
    </div>
    <button class="betButton" data-bet="100">Pariu 100</button>
    <button class="betButton" data-bet="500">Pariu 500</button>
    <div id="result"></div>

    <script>
        const symbols = [
            { symbol: '🏆', rarity: 300 },
            { symbol: '❤️', rarity: 120 },
            { symbol: '🚀', rarity: 90 },
            { symbol: '🌈', rarity: 80 },
            { symbol: '🎉', rarity: 70 },
            { symbol: '🌟', rarity: 65 },
            { symbol: '🔔', rarity: 60 },
            { symbol: '📚', rarity: 50 },
            { symbol: '🍎', rarity: 50 },
            { symbol: '🎶', rarity: 50 }
        ];

        const slideshow = document.getElementById('slideshow');
        const startButton = document.querySelectorAll('.betButton');
        const resultElement = document.getElementById('result');
        const lineElements = slideshow.getElementsByClassName('line');
        let intervalId = null;
        let isRunning = false;
        let betAmount = 0;
        function updateTotalBetAmount(betAmount) {
            let totalBetAmount = parseFloat(localStorage.getItem('totalBetAmount')) || 0;
            totalBetAmount += betAmount;
            localStorage.setItem('totalBetAmount', totalBetAmount);
            return totalBetAmount;
        }
        function getWeightedRandomSymbol() {
            const totalBetAmount = parseFloat(localStorage.getItem('totalBetAmount')) || 0;
            let winChanceFactor = 0.3; // Factor de bază

            // Creștem factorul de câștig dacă suma pariată depășește un anumit prag
            if (totalBetAmount > 1000) { 
                winChanceFactor = 2.0; // Câștiguri mai mari
            } else if (totalBetAmount > 500) {
                winChanceFactor = 1.5;
            }

            const adjustedSymbols = symbols.map(symbol => {
                return {
                    ...symbol,
                    adjustedRarity: symbol.rarity * winChanceFactor
                };
            });

            const totalInverseRarity = adjustedSymbols.reduce((total, symbol) => total + 1 / symbol.adjustedRarity, 0);
            const random = Math.random() * totalInverseRarity;
            let cumulative = 0;

            for (const symbol of adjustedSymbols) {
                cumulative += 1 / symbol.adjustedRarity;
                if (random < cumulative) {
                    return symbol;
                }
            }
        }



        function spin() {
    if (isRunning) return;

    isRunning = true;
    resultElement.textContent = '';
    const lines = Array.from(lineElements);

    // Actualizăm suma totală pariată
    const totalBetAmount = updateTotalBetAmount(betAmount);

    const finalSymbols = lines.flatMap(line =>
        Array.from(line.children).map(element => {
            const symbolObj = getWeightedRandomSymbol();
            element.textContent = symbolObj.symbol;
            return { ...symbolObj, element };
        })
    );

    const winnings = calculateWinnings(finalSymbols);
    updateCasinoBalance(betAmount, winnings);

    isRunning = false;
}



        function calculateWinnings(finalSymbols) {
    const [uno, doi, trei, patru, cinci, sase, sapte, opt, noua] = finalSymbols;
    let winning = 0;

    // Resetăm clasa câștigătoare pentru toate simbolurile
    finalSymbols.forEach(symbolObj => symbolObj.element.classList.remove('winning-symbol'));

    // Verificăm linii complete
    const isLineOne = uno.symbol === doi.symbol && doi.symbol === trei.symbol;
    const isLineTwo = patru.symbol === cinci.symbol && cinci.symbol === sase.symbol;
    const isLineThree = sapte.symbol === opt.symbol && opt.symbol === noua.symbol;

    // Verificăm diagonaale
    const isDiagonalOne = uno.symbol === cinci.symbol && cinci.symbol === noua.symbol;
    const isDiagonalTwo = sapte.symbol === cinci.symbol && cinci.symbol === trei.symbol;

    // Adăugăm clasele pentru simbolurile câștigătoare și calculăm câștigurile
    if (isLineOne) {
        winning += betAmount * uno.rarity;
        uno.element.classList.add('winning-symbol');
        doi.element.classList.add('winning-symbol');
        trei.element.classList.add('winning-symbol');
    }
    if (isLineTwo) {
        winning += betAmount * patru.rarity;
        patru.element.classList.add('winning-symbol');
        cinci.element.classList.add('winning-symbol');
        sase.element.classList.add('winning-symbol');
    }
    if (isLineThree) {
        winning += betAmount * sapte.rarity;
        sapte.element.classList.add('winning-symbol');
        opt.element.classList.add('winning-symbol');
        noua.element.classList.add('winning-symbol');
    }
    if (isDiagonalOne) {
        winning += betAmount * uno.rarity;
        uno.element.classList.add('winning-symbol');
        cinci.element.classList.add('winning-symbol');
        noua.element.classList.add('winning-symbol');
    }
    if (isDiagonalTwo) {
        winning += betAmount * sapte.rarity;
        sapte.element.classList.add('winning-symbol');
        cinci.element.classList.add('winning-symbol');
        trei.element.classList.add('winning-symbol');
    }

    // Verificăm linii sparte
    if (isLineOne && !isLineTwo && !isLineThree) {
        winning += betAmount * (uno.rarity / 50);
    }
    if (isLineTwo && !isLineOne && !isLineThree) {
        winning += betAmount * (patru.rarity / 50);
    }
    if (isLineThree && !isLineOne && !isLineTwo) {
        winning += betAmount * (sapte.rarity / 50);
    }

    // Verificăm combinații duble
    if (isLineOne && isLineTwo) {
        winning += betAmount * (uno.rarity / 20);
        uno.element.classList.add('winning-symbol');
        doi.element.classList.add('winning-symbol');
        trei.element.classList.add('winning-symbol');
        patru.element.classList.add('winning-symbol');
        cinci.element.classList.add('winning-symbol');
        sase.element.classList.add('winning-symbol');
    }
    if (isLineOne && isLineThree) {
        winning += betAmount * (uno.rarity / 20);
        uno.element.classList.add('winning-symbol');
        doi.element.classList.add('winning-symbol');
        trei.element.classList.add('winning-symbol');
        sapte.element.classList.add('winning-symbol');
        opt.element.classList.add('winning-symbol');
        noua.element.classList.add('winning-symbol');
    }
    if (isLineTwo && isLineThree) {
        winning += betAmount * (patru.rarity / 20);
        patru.element.classList.add('winning-symbol');
        cinci.element.classList.add('winning-symbol');
        sase.element.classList.add('winning-symbol');
        sapte.element.classList.add('winning-symbol');
        opt.element.classList.add('winning-symbol');
        noua.element.classList.add('winning-symbol');
    }
    const returnRate = 0.25;
    // Aplicăm factorul de returnare
    winning *= returnRate;

    // Afișăm rezultatul câștigului
    if (winning > 0) {
        resultElement.textContent = `Câștiguri: ${winning.toFixed(2)} $`;
    } else {
        resultElement.textContent = 'Nu ai câștigat nimic.';
    }

    return winning;
}





        function updateCasinoBalance(betAmount, winnings) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        document.getElementById('casinoBalance').textContent = `Casino Balanta: ${response.newCasinoBalance.toFixed(2)} Credite`;
                    } else {
                        alert(response.message);
                    }
                } else {
                    alert('Eroare la actualizarea balanței.');
                }
            };
            xhr.send(`betAmount=${betAmount}&winnings=${winnings}`);
        }

        startButton.forEach(button => {
            button.addEventListener('click', function () {
                betAmount = parseFloat(this.getAttribute('data-bet'));
                spin();
            });
        });
    </script>
</div>
</body>
</html>
