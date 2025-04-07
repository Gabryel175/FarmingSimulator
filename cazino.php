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

// Presupunem că username-ul utilizatorului este stocat în sesiune
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

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

} else {
    // Dacă nu există sesiune activă, redirecționăm utilizatorul către pagina de login
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
    <title>Cazino</title>
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

        .cards-container {
            display: flex;
            justify-content: center; /* Aliniere orizontală centrată */
            gap: 20px; /* Spațiu între carduri */
            padding: 20px;
            margin-top: 60px; /* Pentru a evita suprapunerea cu navbarul fix */
        }

        .nft {
            user-select: none;
            max-width: 300px;
            border: 1px solid #ffffff22;
            background-color: #282c34;
            background: linear-gradient(0deg, #282c34 0%, rgba(17, 0, 32, 0.5) 100%);
            box-shadow: 0 7px 20px 5px #00000088;
            border-radius: 0.7rem;
            backdrop-filter: blur(7px);
            -webkit-backdrop-filter: blur(7px);
            overflow: hidden;
            transition: 0.5s all;
        }

        .nft hr {
            width: 100%;
            border: none;
            border-bottom: 1px solid #88888855;
            margin-top: 0;
        }

        .nft ins {
            text-decoration: none;
        }

        .nft .main {
            display: flex;
            flex-direction: column;
            width: 90%;
            padding: 1rem;
        }

        .nft .main .tokenImage {
            border-radius: 0.5rem;
            max-width: 100%;
            height: 250px;
            object-fit: cover;
        }

        .nft .main .description {
            margin: 0.5rem 0;
            color: #a89ec9;
        }
        .nft a{
            color: #a89ec9;
            text-decoration: none;
        }

        .nft .main .tokenInfo {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nft .main .tokenInfo .price {
            display: flex;
            align-items: center;
            color: #ee83e5;
            font-weight: 700;
        }

        .nft .main .tokenInfo .price ins {
            margin-left: -0.3rem;
            margin-right: 0.5rem;
        }

        .nft .main .tokenInfo .duration {
            display: flex;
            align-items: center;
            color: #a89ec9;
            margin-right: 0.2rem;
        }

        .nft .main .tokenInfo .duration ins {
            margin: 0.5rem;
            margin-bottom: 0.4rem;
        }

        .nft .main .creator {
            display: flex;
            align-items: center;
            margin-top: 0.2rem;
            margin-bottom: -0.3rem;
        }

        .nft .main .creator ins {
            color: #a89ec9;
            text-decoration: none;
        }

        .nft .main .creator .wrapper {
            display: flex;
            align-items: center;
            border: 1px solid #ffffff22;
            padding: 0.3rem;
            margin: 0;
            margin-right: 0.5rem;
            border-radius: 100%;
            box-shadow: inset 0 0 0 4px #000000aa;
        }

        .nft .main .creator .wrapper img {
            border-radius: 100%;
            border: 1px solid #ffffff22;
            width: 2rem;
            height: 2rem;
            object-fit: cover;
            margin: 0;
        }

        .nft ::before {
            position: fixed;
            content: "";
            box-shadow: 0 0 100px 40px #ffffff08;
            top: -10%;
            left: -100%;
            transform: rotate(-45deg);
            height: 60rem;
            transition: 0.7s all;
        }

        .nft:hover {
            border: 1px solid #ffffff44;
            box-shadow: 0 7px 50px 10px #000000aa;
            transform: scale(1.015);
            filter: brightness(1.3);
        }

        .nft:hover ::before {
            filter: brightness(0.5);
            top: -100%;
            left: 200%;
        }

        .bg {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .bg h1 {
            font-size: 20rem;
            filter: opacity(0.5);
        }
        .dollar-link {
    text-decoration: none;
    font-size: 24px;
    font-weight: bold;
    color: #00ff00; /* Verde neon */
    text-shadow: 0 0 10px #00ff00, 0 0 20px #00ff00, 0 0 30px #00ff00;
    transition: transform 0.3s ease, color 0.3s ease;
}

.dollar-link:hover {
    color: #ffffff;
    transform: scale(1.2);
    text-shadow: 0 0 15px #ffffff, 0 0 30px #ffffff, 0 0 45px #00ff00;
}
</style>
<body>
    <div class="navbar">
        <span><?php echo "Slots - User : ".$username; ?></span>
        <span>Balanta: <?php echo number_format($userBalance, 2); ?> $</span>
        <span>Casino Balanta: <?php echo number_format($casinoBalance, 2); ?> Credite</span>
        <span><a href="balanta_cazino.php" class="dollar-link">$</a></span>
    </div>

    <div class="cards-container">
        <div class="nft">
            <div class="main">
                <img class="tokenImage" src="https://cdn-icons-png.flaticon.com/128/1055/1055864.png" alt="NFT" />
                <h2><a href="ruleta.php">Ruleta Cazino</a></h2>
                <p class="description">Pariaza pe numere , paritate sau culoare si fi unul dintre marii castigatori</p>
                
                <hr />
                <div class="creator">
                    <div class="wrapper">
                        <img src="https://cdn-icons-png.flaticon.com/128/445/445076.png" alt="Creator" />
                    </div>
                    <p><ins>Sustinut de</ins> Farming Simulator</p>
                </div>
            </div>
        </div>

        <div class="nft">
            <div class="main">
                <img class="tokenImage" src="https://cdn-icons-png.flaticon.com/128/8336/8336949.png" alt="NFT" />
                <h2><a href="pacanele.php">Pacanele</a></h2>
                <p class="description">Sistem unic de slots realizat de Farming Simulator Cazino</p>
                
                <hr />
                <div class="creator">
                    <div class="wrapper">
                        <img src="https://cdn-icons-png.flaticon.com/128/445/445076.png" alt="Creator" />
                    </div>
                    <p><ins>Sustinut de</ins> Farming Simulator</p>
                </div>
            </div>
        </div>

        <div class="nft">
            <div class="main">
                <img class="tokenImage" src="https://cdn-icons-png.flaticon.com/128/2656/2656484.png" alt="NFT" />
                <h2><a href="">Barbut</a></h2>
                <p class="description">Joc de zaruri . Arunca zarurile si poti castiga daca numarul de pe zarul tau este mai mare</p>
                
                <hr />
                <div class="creator">
                    <div class="wrapper">
                        <img src="https://cdn-icons-png.flaticon.com/128/445/445076.png" alt="Creator" />
                    </div>
                    <p><ins>Sustinut de</ins> Farming Simulator</p>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>
