<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contracte - Firma transport</title>
</head>
<body>
    <h1 style="text-align: center">Generare contract firma</h1>

    <form method="post" action="">
        <div style="text-align: center">
            <button type="submit" name="generate">Generează contract</button>
        </div>
    </form>

    <?php
    if (isset($_POST['generate'])) {
        $items = [
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
            "Lime" => 650,
            "Sare" => 200,
            "Seminte" => 530,
            "Rumegus" => 300,
            "Pin" => 400,
            "Brad" => 410,
            "AltLemn" => 280,
            "Motorina" => 8000,
            "AddBlue" => 15000,
            "Lapte" => 800,
            "Oua" => 600,
            "Lana" => 1500,
            "Zahar" => 600,
            "Piatra" => 140,
            "Paine" => 3270,
            "Faina" => 1800,
            "Ciocolata" => 1900,
            "Branza" => 1650,
            "Unt" => 1450,
            "Tort" => 12600,
            "Transport" => 120,
            "Planks" => 550,
            "Furniture" => 600
        ];

        // Selectează aleatoriu un item și un număr între 10,000 și 150,000
        $randomItem = array_rand($items);
        $randomPrice = rand(10, 150) * 1000;
        $priceContract = $randomPrice / 1000 * 25;
        date_default_timezone_set('Europe/Bucharest');

        // Obținem data și ora curentă
        $timestamp = time(); // obținem timestamp-ul curent
        $date = date('Y-m-d', $timestamp); // format pentru data: an-luna-ziua
        $time = date('H:i:s', $timestamp); // format pentru ora: ora:minutele:secundele
        // Afișează rezultatele
        echo "<h1 style='text-align: center'>Item Random Generat</h1>";
        echo "<p style='text-align: center'>Item: $randomItem</p>";
        echo "<p style='text-align: center'>Preț la 1000L: {$items[$randomItem]}</p>";
        echo "<p style='text-align: center'>Cantitate generată: $randomPrice L</p>";
        echo "<p style='text-align: center'>Preț transport: $priceContract</p>";
        echo "<p style='text-align: center'> $date - $time</p>";
    }
    ?>
</body>
</html>
