<?php 
include_once 'header.php';
?>
<?php
    // Conectare la baza de date
    $conn = new mysqli("localhost", "root", "root", "roleplay");

    // Verificare conexiune
    if ($conn->connect_error) {
        die("Conexiunea la baza de date a eșuat: " . $conn->connect_error);
    }

    $caleFisierJson = dirname(__FILE__) . '/json-files/utilaje.json';
    $continutFisier = file_get_contents($caleFisierJson);

    if ($continutFisier === false) {
        die('Eroare la citirea fișierului JSON.');
    }

    $dateUtilaje = json_decode($continutFisier, true);

    if ($dateUtilaje === null && json_last_error() !== JSON_ERROR_NONE) {
        die('Eroare la decodarea fișierului JSON.');
    }

    $utilaje_amestecate = $dateUtilaje['utilaje_joc'];
    shuffle($utilaje_amestecate);

    $utilizator_curent = "FarmingSimulator";
    $sql = "SELECT nume_produs FROM comenzi WHERE proprietar = '$utilizator_curent'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $denumiri_gasite = array();
        $count_inventar_primarie=count($denumiri_gasite);
        echo '<div id="myNav" class="overlay">';
        echo '<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>';
        echo '<div class="container-fluid">';
        echo "<h5 class='info-nav'>Au fost găsite și afișate $result->num_rows elemente în inventar.</h5>";
        if($_SESSION['username']!='FarmingSimulator'){
            echo '<p class="atentionare-nav">Sunteti in calitate de cetatean . Puteti doar vizualiza inventarul primariei !</p>';
        }else{
            echo '<p class="atentionare-nav-succes">Sunteti in calitate de primar. Daca doriti , puteti modifica inventarul din sectiunea Registru Auto</p>';
        }
        echo '<div class="row">';
        while ($row = $result->fetch_assoc()) {
            $denumire_produs_db = $row['nume_produs'];
            foreach ($utilaje_amestecate as $utilaj) {
                if (isset($utilaj['denumire']) && $utilaj['denumire'] === $denumire_produs_db) {
                    $denumiri_gasite[] = $denumire_produs_db;
                    echo '<div class="col-md-4">';
                        ?>
                            <div class="header-inventar">
                                <img src="<?php if($utilaj['categorie']=='SmallTractors' || $utilaj['categorie']=="MediumTractors" || $utilaj['categorie']=="LargeTractors" || $utilaj['categorie']=="FrontLoaders" || $utilaj['categorie']=="WheelLoaders" || $utilaj['categorie']=="Telehanders" || $utilaj['categorie']=="Skidsteer" || $utilaj['categorie']=="Forklifts"){echo "media/shop/tractors/".$utilaj['cod_produs'];}else{$img_path = strtolower($utilaj['brand'].'-'.$utilaj['denumire']); echo "media/shop/atasamente/".$img_path;} ?>.png" alt="<?php echo "Poza neindentificata pentru utilajul : ".$utilaj['brand'] .$utilaj['denumire'] ?>" class="d-flex mx-auto">
                            </div>
                            <div class="body-inventar-nav text-center">
                                <span class="titlu-body-inventar-nav"><?php echo $utilaj['brand'].'-'.$utilaj['denumire']; ?></span>
                                <hr class="despartitor-nav">
                                <p><span class="titlu-item-inventar-nav">Categorie : </span><?php echo $utilaj['categorie']; ?></p>
                                <p><span class="titlu-item-inventar-nav">Pret initial : </span><?php echo $utilaj['pret']; ?></p>
                                <p><span class="titlu-item-inventar-nav"><?php echo $utilaj['descriere'];  ?></span></p>
                                <?php
                                    $queryAsigurare = "SELECT asigurare, data_asigurare , inregistrare , numar_inmatriculare FROM comenzi WHERE nume_produs = '" . $utilaj['denumire'] . "' AND proprietar = '".$_SESSION['username']."'";
                                    $resultAsigurare = $conn->query($queryAsigurare);

                                    if ($resultAsigurare && $resultAsigurare->num_rows > 0) {
                                        $rowAsigurare = $resultAsigurare->fetch_assoc();
                                        if ($rowAsigurare['asigurare'] == 1) {
                                            echo "<p><span class='titlu-item-inventar-nav'>Asigurat :</span><i class='fas fa-check-circle text-success'></i> [ ".$rowAsigurare['data_asigurare']." ]</p>";
                                        } else {
                                            echo "<p><span class='titlu-item-inventar-nav'>Asigurat :</span><i class='fas fa-times-circle text-danger'></i></p>";
                                        }
                                        if($rowAsigurare['inregistrare'] == 1){
                                            echo "<p><span class='titlu-item-inventar-nav'>Inmatriculat : </span><i class='fas fa-check-circle text-success'></i> [".$rowAsigurare['numar_inmatriculare']." ]</p>";
                                        }else{
                                            echo "<p><span class='titlu-item-inventar-nav'>Inmatriculat :</span><i class='fas fa-times-circle text-danger'></i></p>";
                                        }
                                    } else {
                                        echo "<p><span class='titlu-item-inventar-nav'>Asigurat :</span> Nu</p>";
                                    }
                                ?>
                            </div>
                        <?php
                    echo '</div>';
                }
            }
        }
        echo '</div></div></div>';
    } else {
        echo 'Nu s-au găsit rezultate în baza de date.';
    }

    // Închidere conexiune la baza de date
    $conn->close();
?>
<div class="navbar-secund">
    <div class="container-navbar mx-auto">
        <a href="index.php" class="douazecilasuta"><i class="fa fa-home"></i>Acasa</a>
        <a href="rar.php" class="douazecilasuta"><i class="fa fa-car"></i>Registru Auto</a>
        <a href="banca.php" class="douazecilasuta"><i class="fa fa-dollar"></i>Unitate Bancara</a>
        <a href="primarie.php" class="douazecilasuta  active"><i class="fa fa-building"></i>Primarie</a>
        <a href="dashboard.php" class="douazecilasuta"><i class="fa fa-wrench" aria-hidden="true"></i>Panou de control</a>
    </div>
</div>

<h4 class="titlu-primarie mt-3 mb-3">Primaria Farming Simulator 2022</h4>
<ul class="lista-primarie mt-3 mb-3">
    <li style="--accent-color:#0B374D" class="item-lista-primarie">
        <div class="icon"><i class="fa fa-file-text" aria-hidden="true"></i></div>
        <div class="title"><a href="#regulamente">Regulamente</a></div>
        <div class="descr">Orice cetatean are acces la aceste regulamente care trebuiesc cunoscute</div>
    </li>
    <li style="--accent-color:#1286A8" class="item-lista-primarie">
        <div class="icon"><i class="fa fa-folder" aria-hidden="true"></i></div>
        <div class="title"><span onclick="openNav()" style="cursor:pointer">Inventar Primarie</span></div>
        <div class="descr">Cetatenii pot doar vizualiza , iar primarul poate vinde utilaje</div>
    </li>
    <li style="--accent-color:#D2B53B" class="item-lista-primarie">
        <div class="icon"><i class="fa fa-briefcase" aria-hidden="true"></i></div>
        <div class="title"><a href="#joburi">Joburi</a></div>
        <div class="descr">Fiecare cetatean poate accesa un job </div>
    </li>
    <li style="--accent-color:#DA611E" class="item-lista-primarie">
        <div class="icon"><i class="fa fa-exclamation" aria-hidden="true"></i></div>
        <div class="title"><a href="#sanctiuni">Sanctiuni</a></div>
        <div class="descr">Poti vizualiza sanctiunile , poti plati sau vizualiza istoricul</div>
    </li>
    <li style="--accent-color:#AC2A1A" class="item-lista-primarie">
        <div class="icon"><i class="fa fa-info" aria-hidden="true"></i></div>
        <div class="title"><a href="#informatii-utile">Informatii Utile</a></div>
        <div class="descr">Regulament roleplay si alte informatii</div>
    </li>
</ul>
<div class="mt-5 mb-5"></div>
<h4 class="titlu-primarie mt-3 mb-3" id="regulamente">Regulamente</h4>
<button class="collapsible">Legislatie Auto</button>
<div class="content-regulament">
    <h5 class="titlu-regulament">Cod Rutier</h5>
    <span class="titlu-cuprins">Cuprins : </span>
    <ol type="1" class="lista-cuprins">
        <li><a href="rules/legislatie.php#limitare">Limite de viteza</a></li>
        <li><a href="rules/legislatie.php#indicatoare">Indicatoare</a></li>
        <li><a href="rules/legislatie.php#prioritate">Prioritate</a></li>
        <li><a href="rules/legislatie.php#transport_marfa">Transport Marfa</a></li>
        <li><a href="rules/legislatie.php#penal">Penal</a></li>
        <li><a href="rules/legislatie.php#dispozitii-generale">Dispozitii generale</a></li>
    </ol>
</div>
<button class="collapsible">Constitutie Fs22</button>
<div class="content-regulament">
    <h5 class="titlu-regulament">Constitutia Farming Simulator 2022</h5>
    <span class="titlu-cuprins">Cuprins : </span>
    <ol type="1" class="lista-cuprins">
        <li><a href="rules/constitutie.php#persoane_fizice">Persoane fizice</a></li>
        <li><a href="rules/amenzi.php#generale">Amenzi Generale</a></li>
        <li><a href="rules/amenzi.php#firma">Amenzi Firma</a></li>
        <li><a href="rules/amenzi.php#penale">Amenzi Penale</a></li>
    </ol>
</div>
<button class="collapsible">Taxe si impozite</button>
<div class="content-regulament">Aici vor fi taxele</div>
<button class="collapsible">Amenzi si sanctiuni</button>
<div class="content-regulament">
    <h5 class="titlu-regulament">Amenzi si sanctiuni</h5>
    <span class="titlu-cuprins">Cuprins : </span>
    <ol type="1" class="lista-cuprins">
        <li><a href="rules/amenzi.php#circulatie">Amenzi Circulatie</a></li>
        <li><a href="rules/amenzi.php#generale">Amenzi Generale</a></li>
        <li><a href="rules/amenzi.php#firma">Amenzi Firma</a></li>
        <li><a href="rules/amenzi.php#penale">Amenzi Penale</a></li>
    </ol>
</div>
<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "roleplay";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexiunea la baza de date a eșuat: " . $conn->connect_error);
}
$usernameToFetch = "FarmingSimulator";
$sql = "SELECT balanta FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("s", $usernameToFetch);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $balanta = $row["balanta"];
    } else {
        echo $balanta = "Nu s-a gasit o balanta a primariei";
    }
    $stmt->close();
} else {
    echo "Eroare la pregătirea interogării: " . $conn->error;
}
$sql_utilaje = "SELECT COUNT(*) AS numar_utilaje FROM comenzi WHERE proprietar = ?";
$stmt = $conn->prepare($sql_utilaje);
if ($stmt) {
    $stmt->bind_param("s", $usernameToFetch);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $numar_utilaje = $row["numar_utilaje"];
    } else {
        $numar_utilaje = "Nu s-au putut cauta utilajele primariei";
    }
    $stmt->close();
} else {
    echo "Eroare la pregătirea interogării: " . $conn->error;
}
$sql_job_count = "SELECT COUNT(*) AS numar_joburi FROM users WHERE job IS NOT NULL";
$result = $conn->query($sql_job_count);
if ($result) {
    $row = $result->fetch_assoc();
    $numar_angajati = $row["numar_joburi"];
} else {
    echo "Eroare la executarea interogării: " . $conn->error;
}

$conn->close();
$nume_fisier = 'json-files/joburi.json';
$continut_fisier = file_get_contents($nume_fisier);
$joburi = json_decode($continut_fisier, true);
if ($joburi === null) {
    echo "Eroare: Nu s-a putut decodifica JSON-ul.";
} else {
    $numar_joburi = count($joburi);
}
?>
<section class="dl-blurbs mt-5" id="informatii-utile">
<dl>
  <dt>BALANTA</dt>
  <dd>Primaria dispune in momentul actual de <?php echo $balanta; ?> $ ,reprezentand fondurile disponibile pentru cumparare de utilaje si dezvoltare economica</dd>
  <dt>UTILAJE</dt>
  <dd>Numarul de utilaje detinut de primarie este de <?php echo $numar_utilaje; ?> si poate varia in functie de deciziile primarului</dd>
  <dt>ANGAJATI</dt>
  <dd>In cadrul joburilor , sunt inregistrati <?php if($numar_angajati>=20){echo $numar_angajati." de angajati";}elseif($numar_angajati==1){echo $numar_angajati." angajat";}else{echo $numar_angajati." angajati";} ?></dd>
</dl>
<dl>
  <dt>Joburi</dt>
  <dd>Primaria pune la dispozitie <?php echo $numar_joburi." joburi diverse"; ?></dd>
  <dt>SHOP</dt>
  <dd>Primaria are parteneriat cu un shop ce detine toate utilajele , constructiile si alte lucruri regasite in Farming Simulator 2022</dd>

  <dt>ROLEPLAY</dt>
  <dd>Pentru a respecta regulile jocului si simularea cat mai precisa a realitatii in joc , trebuie sa citesti informatiile din documentul urmator</dd>
</dl>
</section>




<div class="container-joburi mt-5">
    <h1 class="text-center" id="joburi">Joburi disponibile</h1>
    <?php
            if(!isset($_SESSION['username'])){
                echo '<h6 class="text-center bg-warning w-50 d-flex mx-auto justify-content-center">Pentru a putea vedea joburile , trebuie sa fiti conectat</h6>';
            }else{
                $servername = "localhost";
                $username = "root";
                $password = "root";
                $database = "roleplay";
                $conn = new mysqli($servername, $username, $password, $database);
                if ($conn->connect_error) {
                    die("Conexiunea la baza de date a eșuat: " . $conn->connect_error);
                }

                // Username-ul utilizatorului autentificat
                $utilizator = $_SESSION['username'];

                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['demisioneaza'])) {
                    // Actualizăm înregistrarea utilizatorului pentru a șterge informațiile despre job
                    $stmt = $conn->prepare("UPDATE users SET job = NULL, nume_job = NULL WHERE username = ?");
                    $stmt->bind_param("s", $utilizator);
                    $stmt->execute();
                    echo '<script>alert("Ati demisionat ! Va puteti alege un alt job acum")</script>';
                }
                $stmt = $conn->prepare("SELECT job, nume_job FROM users WHERE username = ?");
                $stmt->bind_param("s", $utilizator);
                $stmt->execute();
                $stmt->store_result();
                if ($stmt->num_rows > 0) {
                    $stmt->bind_result($job, $nume_job);
                    $stmt->fetch();
                    if($job==0 || $job==NULL){
                        echo '<h5 class="text-center">Nu aveti niciun job! </h5>';
                    }else{
                        echo '<h5 class="text-center">Detineti jobul de '.$nume_job.'</h5>';
                        echo "<form method='post'>";
                        echo "<input type='submit' name='demisioneaza' value='Demisionează' class='btn btn-primary bg-danger buton-trimitere d-flex mx-auto mb-3'>";
                        echo "</form>";
                    }
                } else {
                    echo "Utilizatorul nu a fost găsit în baza de date.";
                }
                $stmt->close();
                $conn->close();
                $nume_fisier = 'json-files/joburi.json';
                $continut_fisier = file_get_contents($nume_fisier);
                $joburi = json_decode($continut_fisier, true);
                if($joburi !==NULL){
                    $i=1;
                    foreach($joburi as $job){
                        $denumire=$job['denumire_job'];
                        $salariu=$job['salariu_de_baza'];
                        $scoala=$job['scolarizare'];
                        $descriere=$job['descriere'];
                        echo '<article class="episode">';
                            echo '<div class="episode_number">'.$i.'</div>';
                            echo '<div class="episode_content">';
                                echo '<div class="title">'.$denumire.'</div>';
                                echo '<div class="story">';
                                    echo '<p><span class="titlu-item-inventar">Salariu : </span>'.$salariu.'</p>';
                                    if($scoala=='DA'){
                                        echo '<p><span class="titlu-item-inventar">Este nevoie de atestate </span></p>';
                                    }else{
                                        echo '<p><span class="titlu-item-inventar">Nu este nevoie de atestate </span></p>';
                                    }
                                    echo '<p><span class="titlu-item-inventar">Descriere : </span>'.$descriere.'</p>';
                                    echo '<form method="post" action="procesare.php">';
                                        echo '<input type="hidden" name="denumire_job" value="'.$denumire.'">';
                                        echo '<button type="submit">Aplică</button>';
                                    echo '</form>';
                                echo '</div>';
                            echo '</div>';
                        echo '</article>';
                        $i++;
                    }
                }
            }
        ?>
</div> 
<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "roleplay";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexiunea la baza de date a eșuat: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['plata_amenda'])) {
    $persoana_sanctionata = $_POST['persoana_sanctionata'];
    $tip_sanctiune = $_POST['tip_sanctiune'];
    $valoare_amenda = $_POST['valoare_amenda'];
    $mentiuni = $_POST['mentiuni'];

    $serie_sanctiune = generaSerieSanctiune();

    $sql_verificare = "SELECT * FROM sanctiuni WHERE serie_sanctiune = '$serie_sanctiune'";
    $result_verificare = $conn->query($sql_verificare);

    if ($result_verificare->num_rows > 0) {
        echo "Seria generată există deja în tabel.";
    } else {
        $sql_inserare = "INSERT INTO sanctiuni (serie_sanctiune, persoana_sanctionata, tip_sanctiune, valoare_amenda, mentiuni)
        VALUES ('$serie_sanctiune', '$persoana_sanctionata', '$tip_sanctiune', $valoare_amenda, '$mentiuni')";

        if ($conn->query($sql_inserare) === TRUE) {
            echo "Datele au fost introduse cu succes în tabel.";
        } else {
            echo "Eroare: " . $sql_inserare . "<br>" . $conn->error;
        }
    }

    $conn->close();
}

function generaSerieSanctiune() {
    $caractere_posibile = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $lungime_serie = 7;
    $serie_generata = '';

    for ($i = 0; $i < $lungime_serie; $i++) {
        $caracter_aleatoriu = $caractere_posibile[rand(0, strlen($caractere_posibile) - 1)];
        $serie_generata .= $caracter_aleatoriu;
    }

    return $serie_generata;
}
?>
<h1 class="text-center" id="sanctiuni">Sanctiuni</h1>
<?php
if(isset($_SESSION['username'])){
    if($_SESSION['username']=='FarmingSimulator'){
        ?>
        <div class="container-md"> <!-- Container Bootstrap pentru dimensiunea medie -->
            <div class="row justify-content-center form-container"> <!-- Centrare formular -->
                <div class="col-md-6">
                    <h2 class="text-center mb-4">Aplicare sancțiune</h2>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        <div class="form-group">
                            <label for="persoana_sanctionata">Persoana sancționată:</label>
                            <input type="text" class="form-control" id="persoana_sanctionata" name="persoana_sanctionata">
                        </div>
                        <div class="form-group">
                            <label for="tip_sanctiune">Tip sancțiune:</label>
                            <select class="form-control" id="tip_sanctiune" name="tip_sanctiune">
                                <option value="rutiera(contraventie)">Rutiera (contravenție)</option>
                                <option value="rutiera(penala)">Rutiera (penală)</option>
                                <option value="constitutie(contraventie)">Constituție (contravenție)</option>
                                <option value="constitutie(penal)">Constituție (penal)</option>
                                <option value="firma">Firmă</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="valoare_amenda">Valoare amendă:</label>
                            <input type="text" class="form-control" id="valoare_amenda" name="valoare_amenda">
                        </div>
                        <div class="form-group">
                            <label for="mentiuni">Mentiuni:</label>
                            <input type="text" class="form-control" id="mentiuni" name="mentiuni">
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Trimite</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php

        // Conectare la baza de date
        $servername = "localhost";
        $username = "root";
        $password = "root";
        $dbname = "roleplay";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Conexiunea la baza de date a eșuat: " . $conn->connect_error);
        }

        // Verificați dacă s-a făcut clic pe butonul de plată și procesați acțiunea
        if (isset($_POST['plata_amenda'])) {
            // Obțineți seria sancțiunii pentru care s-a făcut clic pe buton
            $serie_sanctiune = $_POST['serie_sanctiune'];

            // Obțineți informații despre sancțiune din baza de date
            $sql_select_sanctiune = "SELECT * FROM sanctiuni WHERE serie_sanctiune = '$serie_sanctiune'";
            $result_select_sanctiune = $conn->query($sql_select_sanctiune);

            if ($result_select_sanctiune->num_rows > 0) {
                $row_sanctiune = $result_select_sanctiune->fetch_assoc();
                $valoare_amenda = $row_sanctiune['valoare_amenda'];
                $persoana_sanctionata = $row_sanctiune['persoana_sanctionata'];

                // Verificați dacă utilizatorul are suficienți bani pentru a plăti amenda
                $sql_select_balanta = "SELECT balanta FROM users WHERE username = '$persoana_sanctionata'";
                $result_select_balanta = $conn->query($sql_select_balanta);
                if ($result_select_balanta->num_rows > 0) {
                    $row_balanta = $result_select_balanta->fetch_assoc();
                    $balanta_utilizator = $row_balanta['balanta'];

                    if ($balanta_utilizator >= $valoare_amenda) {
                        // Actualizați balanța utilizatorului
                        $noua_balanta = $balanta_utilizator - $valoare_amenda;
                        $sql_update_balanta = "UPDATE users SET balanta = '$noua_balanta' WHERE username = '$persoana_sanctionata'";
                        $conn->query($sql_update_balanta);

                        // Ștergeți înregistrarea din tabelul sanctiuni
                        $sql_delete_sanctiune = "DELETE FROM sanctiuni WHERE serie_sanctiune = '$serie_sanctiune'";
                        $conn->query($sql_delete_sanctiune);

                        echo "<script>alert('Amenda a fost plătită cu succes!');</script>";
                    } else {
                        echo "<script>alert('Nu aveți suficienți bani pentru a plăti amenda!');</script>";
                    }
                }
            }
        }

        // Interogare pentru a căuta sancțiunile pentru utilizatorul curent
        $username_curent = $_SESSION['username'];
        $sql_select = "SELECT * FROM sanctiuni WHERE persoana_sanctionata = '$username_curent'";
        $result = $conn->query($sql_select);

        if ($result->num_rows > 0) {
            ?>
            <div class="container-md">
                <h2 class="text-center my-4">Sancțiunile dumneavoastra</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Serie Sancțiune</th>
                            <th scope="col">Persoana Sancționată</th>
                            <th scope="col">Tip Sancțiune</th>
                            <th scope="col">Valoare Amendă</th>
                            <th scope="col">Mentiuni</th>
                            <th scope="col">Acțiuni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>".$row["serie_sanctiune"]."</td>";
                            echo "<td>".$row["persoana_sanctionata"]."</td>";
                            echo "<td>".$row["tip_sanctiune"]."</td>";
                            echo "<td>".$row["valoare_amenda"]."</td>";
                            echo "<td>".$row["mentiuni"]."</td>";
                            echo "<td><form method='post'><input type='hidden' name='serie_sanctiune' value='".$row["serie_sanctiune"]."'><button type='submit' name='plata_amenda' class='btn btn-primary'>Plătește amendă</button></form></td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <?php
        } else {
            echo "<div class='container-md'><h5 class='alert alert-success text-center w-50 mx-auto' role='alert'>Nu aveți sancțiuni !</h5></div>";
        }

        $conn->close();
    }else{
        ?>
        <div class="container-md">
            <h2 class="text-center my-4">Sancțiunile dumneavoastra</h2>
            <?php
            // Conectare la baza de date
            $servername = "localhost";
            $username = "root";
            $password = "root";
            $dbname = "roleplay";

            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Conexiunea la baza de date a eșuat: " . $conn->connect_error);
            }

            // Verificați dacă s-a făcut clic pe butonul de plată și procesați acțiunea
            if (isset($_POST['plata_amenda'])) {
                // Obțineți seria sancțiunii pentru care s-a făcut clic pe buton
                $serie_sanctiune = $_POST['serie_sanctiune'];
            
                // Începe tranzacția
                $conn->begin_transaction();
            
                try {
                    // Obțineți informații despre sancțiune din baza de date
                    $sql_select_sanctiune = "SELECT * FROM sanctiuni WHERE serie_sanctiune = ?";
                    $stmt_select_sanctiune = $conn->prepare($sql_select_sanctiune);
                    $stmt_select_sanctiune->bind_param("s", $serie_sanctiune);
                    $stmt_select_sanctiune->execute();
                    $result_select_sanctiune = $stmt_select_sanctiune->get_result();
            
                    if ($result_select_sanctiune->num_rows > 0) {
                        $row_sanctiune = $result_select_sanctiune->fetch_assoc();
                        $valoare_amenda = $row_sanctiune['valoare_amenda'];
                        $persoana_sanctionata = $row_sanctiune['persoana_sanctionata'];
            
                        // Verificați dacă utilizatorul are suficienți bani pentru a plăti amenda
                        $sql_select_balanta = "SELECT balanta FROM users WHERE username = ?";
                        $stmt_select_balanta = $conn->prepare($sql_select_balanta);
                        $stmt_select_balanta->bind_param("s", $persoana_sanctionata);
                        $stmt_select_balanta->execute();
                        $result_select_balanta = $stmt_select_balanta->get_result();
            
                        if ($result_select_balanta->num_rows > 0) {
                            $row_balanta = $result_select_balanta->fetch_assoc();
                            $balanta_utilizator = $row_balanta['balanta'];
            
                            if ($balanta_utilizator >= $valoare_amenda) {
                                // Actualizați balanța utilizatorului
                                $noua_balanta = $balanta_utilizator - $valoare_amenda;
                                $sql_update_balanta = "UPDATE users SET balanta = ? WHERE username = ?";
                                $stmt_update_balanta = $conn->prepare($sql_update_balanta);
                                $stmt_update_balanta->bind_param("ds", $noua_balanta, $persoana_sanctionata);
                                $stmt_update_balanta->execute();
            
                                // Ștergeți înregistrarea din tabelul sanctiuni
                                $sql_delete_sanctiune = "DELETE FROM sanctiuni WHERE serie_sanctiune = ?";
                                $stmt_delete_sanctiune = $conn->prepare($sql_delete_sanctiune);
                                $stmt_delete_sanctiune->bind_param("s", $serie_sanctiune);
                                $stmt_delete_sanctiune->execute();
            
                                // Inserare tranzacție pentru utilizator
                                $insert_transaction_query = "INSERT INTO tranzactii (nume, data_tranzactie, suma, motiv) VALUES (?, NOW(), ?, 'Amenda')";
                                $stmt_insert_transaction = $conn->prepare($insert_transaction_query);
                                $stmt_insert_transaction->bind_param("sd", $persoana_sanctionata, -$valoare_amenda);
                                if (!$stmt_insert_transaction->execute()) {
                                    throw new Exception("Eroare la inserarea tranzacției: " . $stmt_insert_transaction->error);
                                }
            
                                // Confirmă tranzacția
                                $conn->commit();
                                echo "<script>alert('Amenda a fost plătită cu succes!');</script>";
                            } else {
                                echo "<script>alert('Nu aveți suficienți bani pentru a plăti amenda!');</script>";
                            }
                        } else {
                            throw new Exception("Utilizatorul nu a fost găsit.");
                        }
                    } else {
                        throw new Exception("Sancțiunea nu a fost găsită.");
                    }
                } catch (Exception $e) {
                    // Anulează tranzacția în caz de eroare
                    $conn->rollback();
                    echo "<script>alert('Eroare: " . $e->getMessage() . "');</script>";
                }
            
                // Închide declarațiile preparate și conexiunea la baza de date
                $stmt_select_sanctiune->close();
                $stmt_select_balanta->close();
                $stmt_update_balanta->close();
                $stmt_delete_sanctiune->close();
                $stmt_insert_transaction->close();
                $conn->close();
            } 

            // Interogare pentru a căuta sancțiunile pentru utilizatorul curent
            $username_curent = $_SESSION['username'];
            $sql_select = "SELECT * FROM sanctiuni WHERE persoana_sanctionata = '$username_curent'";
            $result = $conn->query($sql_select);

            if ($result->num_rows > 0) {
                ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Serie Sancțiune</th>
                            <th scope="col">Persoana Sancționată</th>
                            <th scope="col">Tip Sancțiune</th>
                            <th scope="col">Valoare Amendă</th>
                            <th scope="col">Mentiuni</th>
                            <th scope="col">Acțiuni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>".$row["serie_sanctiune"]."</td>";
                            echo "<td>".$row["persoana_sanctionata"]."</td>";
                            echo "<td>".$row["tip_sanctiune"]."</td>";
                            echo "<td>".$row["valoare_amenda"]."</td>";
                            echo "<td>".$row["mentiuni"]."</td>";
                            echo "<td><form method='post'><input type='hidden' name='serie_sanctiune' value='".$row["serie_sanctiune"]."'><button type='submit' name='plata_amenda' class='btn btn-primary'>Plătește amendă</button></form></td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <?php
            } else {
                echo "<h5 class='alert alert-success text-center w-50 mx-auto' role='alert'>Nu aveți sancțiuni !</h5>";
            }
            $conn->close();
            ?>
        </div>
        <?php
    }
}else{
    echo '<h6 class="text-center bg-warning w-50 d-flex mx-auto justify-content-center">Pentru a putea vedea sancțiunile dumneavoastra, trebuie să fiți conectat !</h6>';
}

?>

<script>
var coll = document.getElementsByClassName("collapsible");
var i;

for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function() {
    this.classList.toggle("active-regulament");
    var content = this.nextElementSibling;
    if (content.style.maxHeight){
      content.style.maxHeight = null;
    } else {
      content.style.maxHeight = content.scrollHeight + "px";
    } 
  });
}
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Gestionare eveniment de clic pe ancoră
        $("#afisare_inventar").click(function(event) {
            event.preventDefault(); // Previne acțiunea implicită a ancoră

            // Face o cerere AJAX către server
            $.ajax({
                url: "afisare_inventar.php", // Locația fișierului PHP care conține codul de afișare a inventarului
                type: "GET",
                success: function(response) {
                    // Afisează rezultatul în div-ul cu id-ul "rezultat_inventar"
                    $("#rezultat_inventar").html(response);
                },
                error: function(xhr, status, error) {
                    console.error("Eroare la solicitarea AJAX: " + status + " - " + error);
                }
            });
        });
    });
</script>

<script>
function openNav() {
  document.getElementById("myNav").style.width = "100%";
}

function closeNav() {
  document.getElementById("myNav").style.width = "0%";
}
</script>
