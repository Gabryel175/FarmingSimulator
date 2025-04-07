<link rel="stylesheet" href="style.css">
<?php
session_start();
$conn = new mysqli("localhost", "root", "root", "roleplay");

if ($conn->connect_error) {
    die("Conexiune esuata: " . $conn->connect_error);
}

$productPrices = [
    "Grau" => 875,
    "Ovaz" => 880,
    "Secara" => 900,
    "Rapita" => 1300,
    "Sorghum" => 970,
    "Struguri" => 1170,
    "Masline" => 1400,
    "Floare" => 1450,
    "Soia" => 1460,
    "Porumb" => 980,
    "Cartofi" => 1500,
    "SugarBeet" => 1750,
    "SugarCane" => 1100,
    "Cotton" => 1675,
    "Paie" => 250,
    "Fan" => 230,
    "Iarba" => 200,
    "Sillage" => 350,
    "FertilizantSolid" => 500,
    "FertilizantLichid" => 420,
    "IerbicidLichid" => 410,
    "Lime" => 525,
    "Sare" => 200,
    "Seminte" => 530,
    "Rumegus" => 150,
    "Pin" => 400,
    "Brad" => 410,
    "AltLemn" => 280,
    "Motorina" => 8000,
    "AddBlue" => 15000,
    "Lapte" => 800,
    "Oua" => 600,
    "Lana" => 1500,
    "Piatra" => 290,
    "Paine" => 3270,
    "Faina" => 1800,
    "Ciocolata" => 1900,
    "Branza" => 1650,
    "Unt" => 1450,
    "Tort" => 12600,
    "Transport" => 120,
    "Planks" => 550,
    "Furniture" => 600,
    "Rye" => 860,
    "Alfalfa" => 300,
    "AlfalfaHay" => 330,
    "Manure" => 220,
    "Slurry" => 280,
    "Tomato" => 600,
    "Lettuce" => 600,
    "Honey" => 1000,
    "Salt" => 250,
    "Sillage" => 400,
    "TransportFirma" => 35,
    "Salata" => 410,
    "Rosii" => 300,
    "Capsuni" => 350,
    "Mere"=> 320,
    "SucMere"=> 1000,
    "Miere" => 980,
    "ConservePeste" => 320,
    "MineralFeed" => 200,
    "Stafide" => 300,
    "Cereale" => 2900,
    "Fabric" => 3150,
    "Firewood" => 650,
    "Crib" => 1900,
    "UleiFloare/Rapita" => 3400,
    "UleiMasline" => 4000,
    "Peleti" => 1000,
    "Clipboard" => 1300,
    "Casute" => 8000,
    "kW" => 2400

];

$product = $_POST['product2'];
$quantity = $_POST['quantity2'];
$price = $productPrices[$product];
$total = ($quantity / 1000) * $price;

$nume = $_SESSION['username'];
$user_id = $_SESSION['user_id'];
$data = date('Y-m-d H:i:s');

// Verificăm balanta utilizatorului
$sql_balance = "SELECT balanta FROM users WHERE id = $user_id";
$result_balance = $conn->query($sql_balance);
$row_balance = $result_balance->fetch_assoc();
$balanta = $row_balance['balanta'];

if ($balanta) {
    // Scădem totalul din balanta utilizatorului
    $noua_balanta = $balanta + $total;
    $sql_update_balance = "UPDATE users SET balanta = $noua_balanta WHERE id = $user_id";
    $conn->query($sql_update_balance);

    // Inregistrăm tranzacția
    $motiv = $product."-".$quantity."-"."ShopSaler";
    $total_minus=$total;
    $sql_insert = "INSERT INTO tranzactii (nume, data_tranzactie, suma, motiv) VALUES ('$nume', '$data', $total_minus, '$motiv')";
    $conn->query($sql_insert);
    ?>
        <div class="bon-fiscal">
            <div class="header">
                <h4>Bon fiscal - Dovada platii</h4>
            </div>
            <div class="body-bon-fiscal">
                <?php echo $data;  ?>
                <hr>
                <strong>Vanzator : </strong><?php echo $nume; ?>
                <strong>Produs : </strong><?php echo $product; ?>
                <strong>Cantitate : </strong><?php echo $quantity; ?>
                <hr>
                <strong>Suma incasata : </strong><?php echo $total;  ?>
            </div>
            <div class="footer-bon-fiscal"> 
                <strong>Farming Simulator Shop Product - Dovada platii , pastrati bonul . Fara acest bon , se considera evaziune fiscala</strong>
            </div>
        </div>
    <?php
} else {
    echo "Fonduri insuficiente pentru a realiza tranzacția.";
}

$conn->close();
?>
