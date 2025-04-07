<?php
include_once 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit'])) {
        if (isset($_POST['dropdown'])) {
            $servername = "localhost";
            $username = "root";
            $password = "root";
            $dbname = "roleplay";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Conexiunea la baza de date a eșuat: " . $conn->connect_error);
            }

            $selectedVehicle = $conn->real_escape_string($_POST['dropdown']);
            $proprietar = $_SESSION['username'];

            // Verificăm dacă mașina are deja asigurare
            $checkQuery = "SELECT asigurare, pret FROM comenzi WHERE nume_produs = '$selectedVehicle' AND proprietar = '$proprietar'";
            $checkResult = $conn->query($checkQuery);

            if ($checkResult->num_rows > 0) {
                $row = $checkResult->fetch_assoc();
                if ($row['asigurare'] == 1) {
                    echo "<script>alert('Mașina are deja asigurare!');</script>";
                } else {
                    $zi1 = $conn->real_escape_string($_POST['select1']);
                    $anotimp1 = $conn->real_escape_string($_POST['select2']);
                    $an1 = $conn->real_escape_string($_POST['increase']);
                    $durata_asigurare = $conn->real_escape_string($_POST['select3']);
                    $pret_masina = $row['pret'];
                    $pret_asigurare = 0.0015 * floatval($pret_masina);

                    // Extract the numeric part from $durata_asigurare
                    $durata_numeric = intval($durata_asigurare);
                    $numar_luni = 0;
                    if ($durata_numeric == 1) {
                        $numar_luni = 12;
                    } elseif ($durata_numeric == 2) {
                        $numar_luni = 24;
                    } else {
                        $numar_luni = 60;
                    }
                    $pret_asigurare = $pret_asigurare * $numar_luni;
                    // Add the numeric part to $an1
                    $an_nou = $an1 + $durata_numeric;
                    $insuranceDate = $zi1 . "/" . $anotimp1 . "/" . $an_nou;
                    $username = $_SESSION['username'];
                    $balanta = 0;
                    $sql = "SELECT balanta FROM users WHERE username = '$username'";
                    $result = $conn->query($sql);
                    if ($result) {
                        $row = $result->fetch_assoc();
                        if ($row) {
                            $balanta = $row['balanta'];
                        } else {
                            echo "Utilizatorul $username nu a fost găsit sau nu are balanta setată.";
                        }
                        $result->free();
                    } else {
                        echo "Eroare la interogarea bazei de date: " . $conn->error;
                    }
                    if ($balanta >= $pret_asigurare) {
                        $newBalance = $balanta - $pret_asigurare;
                        $updateBalanceQuery = "UPDATE users SET balanta = '$newBalance' WHERE username = '$username'";
                        if (!$conn->query($updateBalanceQuery)) {
                            die("Eroare la actualizarea balantei: " . $conn->error);
                        }
                        $updateQuery = "UPDATE comenzi 
                            SET asigurare = 1, data_asigurare = '$insuranceDate' , pret_asigurare = '$pret_asigurare'
                            WHERE nume_produs = '$selectedVehicle' AND proprietar = '$proprietar'";
                        if ($conn->query($updateQuery) === TRUE) {
                            // Inserăm tranzacția în tabelul tranzactii
                            $insertTransactionQuery = "INSERT INTO tranzactii (nume, data_tranzactie, suma, motiv) 
                                VALUES (?, NOW(), ?, 'Asigurare')";

                            $stmt = $conn->prepare($insertTransactionQuery);
                            $pret_asigurare=-$pret_asigurare;
                            $stmt->bind_param("sd", $username, $pret_asigurare);
                            if ($stmt->execute()) {
                                echo "<script>alert('Asigurarea a fost actualizată cu succes și tranzacția a fost înregistrată.');</script>";
                            } else {
                                echo "Eroare la inserarea tranzacției: " . $stmt->error;
                            }

                            $stmt->close();
                        } else {
                            echo "Eroare la actualizare: " . $conn->error;
                        }
                    } else {
                        ?>
                        <script>
                            alert("Fonduri insuficiente");
                        </script>
                        <?php
                    }
                }
            }

            $conn->close();
        } else {
            echo "<script>alert('Eroare! Cheia \"dropdown\" lipsește în formular.');</script>";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit2'])) {
        if (isset($_POST['dropdown'])) {
            $servername = "localhost";
            $username = "root";
            $password = "root";
            $dbname = "roleplay";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Conexiunea la baza de date a eșuat: " . $conn->connect_error);
            }

            $selectedVehicle = $conn->real_escape_string($_POST['dropdown']);
            $proprietar = $_SESSION['username'];

            // Verificăm dacă mașina are deja înmatriculare
            $checkQuery = "SELECT inregistrare, pret FROM comenzi WHERE nume_produs = '$selectedVehicle' AND proprietar = '$proprietar'";
            $checkResult = $conn->query($checkQuery);

            if ($checkResult->num_rows > 0) {
                $row = $checkResult->fetch_assoc();
                if ($row['inregistrare'] == 1) {
                    echo "<script>alert('Vehiculul este deja înmatriculat!');</script>";
                } else {
                    $pret_inmatriculare = 0.04 * $row['pret'];
                    
                    // Verificăm balanța utilizatorului
                    $balantaQuery = "SELECT balanta FROM users WHERE username = '$proprietar'";
                    $balantaResult = $conn->query($balantaQuery);
                    
                    if ($balantaResult->num_rows > 0) {
                        $balantaRow = $balantaResult->fetch_assoc();
                        if ($balantaRow['balanta'] >= $pret_inmatriculare) {
                            $judet = $conn->real_escape_string($_POST['judet']);
                            $cifre = $conn->real_escape_string($_POST['cifre']);
                            $litere = $conn->real_escape_string($_POST['litere']);
                            $judet = strtoupper($judet);
                            $litere = strtoupper($litere);
                            $numar_inmatriculare = $judet . $cifre . $litere;

                            $checkNumarQuery = "SELECT * FROM comenzi WHERE numar_inmatriculare = '$numar_inmatriculare'";
                            $checkNumarResult = $conn->query($checkNumarQuery);
                            
                            if ($checkNumarResult->num_rows > 0) {
                                echo "<script>alert('Numărul de înmatriculare există deja în baza de date!');</script>";
                            } else {
                                if (ctype_alpha($litere) && strlen($litere) === 3) {
                                    // Actualizăm baza de date cu informațiile de înmatriculare
                                    $updateQuery = "UPDATE comenzi 
                                                    SET inregistrare = 1, numar_inmatriculare = '$numar_inmatriculare'
                                                    WHERE nume_produs = '$selectedVehicle' AND proprietar = '$proprietar'";
                                    
                                    if ($conn->query($updateQuery) === TRUE) {
                                        // Actualizăm balanța
                                        $updateBalantaQuery = "UPDATE users SET balanta = balanta - $pret_inmatriculare WHERE username = '$proprietar'";
                                        if ($conn->query($updateBalantaQuery) === TRUE) {
                                            // Inserăm tranzacția în tabelul tranzactii
                                            $insertTransactionQuery = "INSERT INTO tranzactii (nume, data_tranzactie, suma, motiv) 
                                                VALUES (?, NOW(), ?, 'Inmatriculare')";

                                            $stmt = $conn->prepare($insertTransactionQuery);
                                            $pret_inmatriculare = -$pret_inmatriculare;
                                            $stmt->bind_param("sd", $proprietar, $pret_inmatriculare);
                                            if ($stmt->execute()) {
                                                echo "<script>alert('Actualizare cu succes! Balanța a fost actualizată.');</script>";
                                            } else {
                                                echo "Eroare la inserarea tranzacției: " . $stmt->error;
                                            }

                                            $stmt->close();
                                        } else {
                                            echo "Eroare la actualizarea balanței: " . $conn->error;
                                        }
                                    } else {
                                        echo "Eroare la actualizare: " . $conn->error;
                                    }
                                } else {
                                    echo "<script>alert('Numărul format din trei litere nu respectă formatul dorit!');</script>";
                                }
                            }
                        } else {
                            echo "<script>alert('Fonduri insuficiente pentru înmatriculare!');</script>";
                        }
                    }
                }
            }

            $conn->close();
        } else {
            echo "<script>alert('Eroare! Cheia \"dropdown\" lipsește în formular.');</script>";
        }
    }
}
?>


<?php include_once 'header.php'; ?>
<div class="navbar-secund">
    <div class="container-navbar mx-auto">
        <a href="index.php" class="douazecilasuta"><i class="fa fa-home"></i>Acasa</a>
        <a href="rar.php" class="douazecilasuta active"><i class="fa fa-car"></i>Registru Auto</a>
        <a href="banca.php" class="douazecilasuta"><i class="fa fa-dollar"></i>Unitate Bancara</a>
        <a href="primarie.php" class="douazecilasuta"><i class="fa fa-building"></i>Primarie</a>
        <a href="dashboard.php" class="douazecilasuta"><i class="fa fa-wrench" aria-hidden="true"></i>Panou de control</a>
    </div>
</div>

<?php
    if(isset($_SESSION['username'])){
        ?>
            <div class="banner justify-content-center align-item-center d-flex mb-5">
                <div class="continut-banner">
                    <img src="https://cdn-icons-png.flaticon.com/128/4304/4304009.png" alt="">
                    <?php
                    echo "<h4>" . $_SESSION['username'] . " , bine ai venit la Registrul Auto</h4>";
                    ?>

                    <div class="container">
                        <div class="row">
                            <div class="col-md-6">
                                <button class="btn-rar1" href="">Regulamente Auto</button>
                            </div>
                            <div class="col-md-6">
                                <button class="btn-rar1" href="">Licente si Permise</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <h4 class="titlu-h4">Polita Asigurare Obligatorie</h4>
            <button type="button" class="btn btn-primary buton-trimitere mx-auto justify-content-center d-flex" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Vezi informatii utile
            </button>
            <div class="modal fade modal-fullscreen" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Polita Asigurare</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Închide"></button>
                        </div>
                        <div class="modal-body">
                            <p><i class="fas fa-check-circle text-success"></i>Aigurarea este obligatorie si daca un autovehicul este pus in circulatie fara , constituie infractiune si se sanctioneaza</p>
                            <p><i class="fas fa-check-circle text-success"></i>Vehiculele si utilaje care se incadreaza si trebuie sa aibe polita de asigurare fac parte din urmatoarele categorii :</p>
                            <ol role="list" class="list-none">
                                <li style="--li-bg: #4CAF50;"><i class="fa fa-angle-right" aria-hidden="true"></i>Tractoarele</li>
                                <li style="--li-bg: #45A049;"><i class="fa fa-angle-right" aria-hidden="true"></i>Combinele</li>
                                <li style="--li-bg: #3D8B40;"><i class="fa fa-angle-right" aria-hidden="true"></i>Remorci Header</li>
                                <li style="--li-bg: #389737;"><i class="fa fa-angle-right" aria-hidden="true"></i>Balotiere</li>
                                <li style="--li-bg: #2E722E;"><i class="fa fa-angle-right" aria-hidden="true"></i>Crop Protection</li>
                                <li style="--li-bg: #276827;"><i class="fa fa-angle-right" aria-hidden="true"></i>Transport Animale</li>
                                <li style="--li-bg: #215D20;"><i class="fa fa-angle-right" aria-hidden="true"></i>Transport Padure</li>
                                <li style="--li-bg: #1C521C;"><i class="fa fa-angle-right" aria-hidden="true"></i>Tafuri</li>
                                <li style="--li-bg: #173D17;"><i class="fa fa-angle-right" aria-hidden="true"></i>Remorci</li>
                                <li style="--li-bg: #123812;"><i class="fa fa-angle-right" aria-hidden="true"></i>Manipulatoare</li>
                                <li style="--li-bg: #0D270D;"><i class="fa fa-angle-right" aria-hidden="true"></i>Categoria B + C</li>
                            </ol>
                            <p><i class="fas fa-check-circle text-success"></i>Fiecare posesor de autovehicule si utilaje este responsabil pentru data de expirare a asigurarii.</p>
                            <p><i class="fas fa-check-circle text-success"></i>Pretul asigurarii se calculeaza automat si reprezinta 2% din pretul autovehiculului/utilajului pe 2 zile de asigurare</p>
                            <p><i class="fas fa-check-circle text-success"></i>Asigurarea despagubeste inculpatul daca s-a produs un accident in circumstante normale, fara incalcare de regulamente sau din cauza neatentiei din punct de vedere al atentionarilor. Procentajul pe care polita il poate despagubi este intre 10% si 85% in functie de circumstante</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container mt-5">
                <form method="POST" action="">
                    <div class="form-row mb-3 mx-auto">
                        <div class="col">
                            <label for="dropdown">Utilaje detinute pentru asigurare:</label>
                            <select class="form-control" id="dropdown" name="dropdown">
                                <?php
                                $servername = "localhost";
                                $username = "root";
                                $password = "root";
                                $dbname = "roleplay";

                                $conn = new mysqli($servername, $username, $password, $dbname);

                                if ($conn->connect_error) {
                                    die("Conexiunea la baza de date a eșuat: " . $conn->connect_error);
                                }
                                $proprietar = $_SESSION['username'];
                                echo $proprietar;
                                $sql = "SELECT * FROM comenzi WHERE proprietar = '$proprietar'";
                                $result = $conn->query($sql);
                                $lista_categorii_pentru_asigurare = array("SmallTractors", "MediumTractors", "LargeTractors", "CombinaG", "CombinaF", "CombinaP", "CombinaB", "CombinaS", "CombinaC", "CombinaO", "TrailerHeaders", "Balotiera", "CropProtection", "AnimalsTransport", "ForestryTransporting", "ForestryTaf", "FrontLoaders", "WheelLoaders", "Telehanders", "Skidsteers", "Trailers", "LowLoaders", "CatB", "CatC", "Autobuz", "Gabaritic","Mods","Animals","ForestryChop");
                                $count_check = 0;
                                if ($result->num_rows > 0) {
                                    $count_check = 0;
                                    while ($row = $result->fetch_assoc()) {
                                        if (in_array($row['categorie'], $lista_categorii_pentru_asigurare)) {
                                            echo '<option>' . $row['nume_produs'] . '</option>';
                                            $count_check++;
                                        }
                                    }
                                    if ($count_check == 0) {
                                        echo '<option>Niciun vehicul disponibil pentru asigurare</option>';
                                    }
                                } else {
                                    echo '<option>Niciun vehicul disponibil pentru asigurare</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3 form-row mx-auto">
                        <div class="col-md-4">
                            <label for="select1">Zi curenta :</label>
                            <select class="form-control" name="select1" id="select1">
                                <?php for ($i = 1; $i <= 6; $i++) { ?>
                                    <option><?php echo $i; ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="select2">Anotimp curent :</label>
                            <select class="form-control" name="select2" id="select2">
                                <?php for ($i = 1; $i <= 4; $i++) { ?>
                                    <option><?php echo $i; ?></option>
                                <?php } ?>
                            </select>

                        </div>

                        <div class="col-md-4">
                            <label for="increase">An curent:</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="increase" id="increase" min="0" max="100" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="form-row mb-3 mx-auto">
                        <div class="col-md">
                            <label for="select3">Perioada asigurare :</label>
                            <select class="form-control" name="select3" id="select3">
                                <option value="1an">1 AN</option>
                                <option value="2ani">2 ANI</option>
                                <option value="5ani">5 ANI</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" name="submit" class="btn buton-trimitere mx-auto justify-content-center d-flex">Trimite</button>
                </form>
            </div>
            
            <h2 class="divider donotcross mt-3" contenteditable><i class="fa fa-chevron-circle-down mr-2"></i> Inventarul tau <i class="fa fa-chevron-circle-down ml-2"></i></h2>
            <?php
                if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete_product'){
                    $conn=new mysqli("localhost","root","root","roleplay");
                    if($conn->connect_error){
                        die("Conexiune nereusita . Va rugam contactai staff-ul" . $conn->connect_error);
                    }
                    $id_produs=$_POST['id_produs'];
                    $pret_produs=$_POST['pret_produs'];
                    $denumire_produs=$_POST['denumire_produs'];
                    $username=$_SESSION['username'];
                    $sql_delete="DELETE FROM comenzi WHERE id = ?";
                    $stmt_delete=$conn->prepare($sql_delete);
                    $stmt_delete->bind_param("i",$id_produs);
                    if ($stmt_delete->execute()){
                        ?>
                            <script>
                                allert("Utilaj vandut , balanta actualizata!");
                            </script>
                        <?php
                    }else{
                        echo 'Eroare la stergere : '. $stmt_delete->error;
                    }
                    $sql_update="UPDATE users SET balanta = balanta + ? WHERE username = ?";
                    $stmt_update=$conn->prepare($sql_update);
                    $stmt_update->bind_param("ds",$pret_produs,$username);
                    if($stmt_update->execute()){
                        ?>
                            <script>
                                allert("Utilaj vandut , balanta actualizata!");
                            </script>
                        <?php
                    }else{
                        echo 'Eroare la actualizarea balantei!'.$stmt_update->error;
                    }
                    $stmt_delete->close();
                    $stmt_update->close();
                    $conn->close();
                }
            ?>
            <div class="container-inventar mt-5 mb-5">
                <button id="showBtn" class="btn buton-trimitere ">Afișează Inventar</button>
                <div id="content">
                    <div id="closeBtnContainer">
                        <button id="closeBtn"><i class="fa fa-times"></i></button>
                    </div>
                    <?php
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

                        $utilizator_curent = $_SESSION['username'];
                        $sql = "SELECT nume_produs,id FROM comenzi WHERE proprietar = '$utilizator_curent'";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            $denumiri_gasite = array();
                            echo '<div class="row">';
                            while ($row = $result->fetch_assoc()) {
                                $denumire_produs_db = $row['nume_produs'];
                                $id_produs_db = $row['id'];

                                foreach ($utilaje_amestecate as $utilaj) {
                                    if (isset($utilaj['denumire']) && $utilaj['denumire'] === $denumire_produs_db) {
                                        $denumiri_gasite[] = $denumire_produs_db;
                                        echo '<div class="col-md-4">';
                                            ?>
                                                <div class="header-inventar">
                                                    <img src="<?php if($utilaj['categorie']=='SmallTractors' || $utilaj['categorie']=="MediumTractors" || $utilaj['categorie']=="LargeTractors" || $utilaj['categorie']=="FrontLoaders" || $utilaj['categorie']=="WheelLoaders" || $utilaj['categorie']=="Telehanders" || $utilaj['categorie']=="Skidsteer" || $utilaj['categorie']=="Forklifts"){echo "media/shop/tractors/".$utilaj['cod_produs'];}else{$img_path = strtolower($utilaj['brand'].'-'.$utilaj['denumire']); echo "media/shop/atasamente/".$img_path;} ?>.png" alt="<?php echo "Poza neindentificata pentru utilajul : ".$utilaj['brand'] .$utilaj['denumire'] ?>" width="200" height="200">
                                                </div>
                                                <div class="body-inventar">
                                                    <span class="titlu-body-inventar"><?php echo $utilaj['brand'].'-'.$utilaj['denumire']; ?></span>
                                                    <hr class="despartitor">
                                                    <p><span class="titlu-item-inventar">Inregistrare : </span><?php echo $id_produs_db; ?></p>
                                                    <p><span class="titlu-item-inventar">Categorie : </span><?php echo $utilaj['categorie']; ?></p>
                                                    <p><span class="titlu-item-inventar">Pret initial : </span><?php echo $utilaj['pret']; ?></p>
                                                    <p><span class="titlu-item-inventar"><?php echo $utilaj['descriere'];  ?></span></p>
                                                    <?php
                                                        $queryAsigurare = "SELECT asigurare, data_asigurare , inregistrare , numar_inmatriculare FROM comenzi WHERE nume_produs = '" . $utilaj['denumire'] . "' AND proprietar = '".$_SESSION['username']."'";
                                                        $resultAsigurare = $conn->query($queryAsigurare);

                                                        if ($resultAsigurare && $resultAsigurare->num_rows > 0) {
                                                            $rowAsigurare = $resultAsigurare->fetch_assoc();
                                                            if ($rowAsigurare['asigurare'] == 1) {
                                                                echo "<p><span class='titlu-item-inventar'>Asigurat :</span><i class='fas fa-check-circle text-success'></i> [ ".$rowAsigurare['data_asigurare']." ]</p>";
                                                            } else {
                                                                echo "<p><span class='titlu-item-inventar'>Asigurat :</span><i class='fas fa-times-circle text-danger'></i></p>";
                                                            }
                                                            if($rowAsigurare['inregistrare'] == 1){
                                                                echo "<p><span class='titlu-item-inventar'>Inmatriculat : </span><i class='fas fa-check-circle text-success'></i> [".$rowAsigurare['numar_inmatriculare']." ]</p>";
                                                            }else{
                                                                echo "<p><span class='titlu-item-inventar'>Inmatriculat :</span><i class='fas fa-times-circle text-danger'></i></p>";
                                                            }
                                                        } else {
                                                            echo "<p><span class='titlu-item-inventar'>Asigurat :</span> Nu</p>";
                                                        }
                                                    ?>
                                                    <p><span class="titlu-item-inventar">Vinde utilaj : 
                                                    <form method="post">
                                                        <input type="hidden" name="action" value="delete_product">
                                                        <input type="hidden" name="denumire_produs" value="<?php echo $utilaj['denumire']; ?>">
                                                        <input type="hidden" name="pret_produs" value="<?php echo $utilaj['pret']; ?>">
                                                        <input type="hidden" name="id_produs" value="<?php echo $id_produs_db; ?>">
                                                        <button type="submit" class="btn btn-danger">Sterge produsul</button>
                                                    </form>
                                                    </p>
                                                </div>
                                            <?php
                                        echo '</div>';
                                    }
                                }
                            }
                            echo '</div>';
                        } else {
                            echo 'Nu s-au găsit rezultate în baza de date.';
                        }
                    ?>
                </div>
            </div>
            <h4 class="titlu-h4">Inmatriculare vehicule si remorci</h4>
            <button type="button" class="btn btn-primary buton-trimitere mx-auto justify-content-center d-flex" data-bs-toggle="modal" data-bs-target="#exampleModal1">
                Vezi informatii utile
            </button>
            <div class="modal fade modal-fullscreen" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Inmatriculare vehicule si remorci</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Închide"></button>
                        </div>
                        <div class="modal-body">
                            <p><i class="fas fa-check-circle text-success"></i>Punerea in circulatie a vehiculelor pe drumurile publice si forestiere este permisa doar daca acesta este inmatriculat</p>
                            <p><i class="fas fa-check-circle text-success"></i>Vehiculele si utilajele care se incadreaza si trebuie sa fie inmatriculate sunt:</p>
                            <ol role="list" class="list-none">
                                <li style="--li-bg: #4CAF50;"><i class="fa fa-angle-right" aria-hidden="true"></i>Tractoarele</li>
                                <li style="--li-bg: #45A049;"><i class="fa fa-angle-right" aria-hidden="true"></i>Combinele</li>
                                <li style="--li-bg: #2E722E;"><i class="fa fa-angle-right" aria-hidden="true"></i>Crop Protection</li>
                                <li style="--li-bg: #276827;"><i class="fa fa-angle-right" aria-hidden="true"></i>Transport Animale</li>
                                <li style="--li-bg: #215D20;"><i class="fa fa-angle-right" aria-hidden="true"></i>Transport Padure</li>
                                <li style="--li-bg: #1C521C;"><i class="fa fa-angle-right" aria-hidden="true"></i>Tafuri</li>
                                <li style="--li-bg: #173D17;"><i class="fa fa-angle-right" aria-hidden="true"></i>Remorci</li>
                                <li style="--li-bg: #123812;"><i class="fa fa-angle-right" aria-hidden="true"></i>Manipulatoare</li>
                                <li style="--li-bg: #0D270D;"><i class="fa fa-angle-right" aria-hidden="true"></i>Categoria B + C</li>
                            </ol>
                            <p><i class="fas fa-check-circle text-success"></i>In momentul asigurarii , se achita automat o taxa de asigurare care este egala cu 4% din pretul utilajului</p>
                            <p><i class="fas fa-check-circle text-success"></i>Se va alege un numar de inmatriculare sugestiv , alcatuit din indicativ de judet , numar personalizat , si un text din 3 litere la alegere</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container mt-5">
                <form method="POST" action="">
                    <div class="form-row mb-3 mx-auto">
                        <div class="col">
                            <label for="dropdown">Utilaje detinute pentru inmatriculare:</label>
                            <select class="form-control" id="dropdown" name="dropdown">
                                <?php
                                $servername = "localhost";
                                $username = "root";
                                $password = "root";
                                $dbname = "roleplay";

                                $conn = new mysqli($servername, $username, $password, $dbname);

                                if ($conn->connect_error) {
                                    die("Conexiunea la baza de date a eșuat: " . $conn->connect_error);
                                }
                                $proprietar = $_SESSION['username'];
                                echo $proprietar;
                                $sql = "SELECT * FROM comenzi WHERE proprietar = '$proprietar'";
                                $result = $conn->query($sql);
                                $lista_categorii_pentru_asigurare = array("SmallTractors", "MediumTractors", "LargeTractors", "CombinaG", "CombinaF", "CombinaP", "CombinaB", "CombinaS", "CombinaC", "CombinaO", "CropProtection", "AnimalsTransport", "ForestryTransporting", "ForestryTaf", "FrontLoaders", "WheelLoaders", "Telehanders", "Skidsteers", "Trailers", "LowLoaders", "CatB", "CatC", "Autobuz", "Gabaritic","Mods","Animals","ForestryChop");
                                $count_check = 0;
                                if ($result->num_rows > 0) {
                                    $count_check = 0;
                                    while ($row = $result->fetch_assoc()) {
                                        if (in_array($row['categorie'], $lista_categorii_pentru_asigurare)) {
                                            echo '<option>' . $row['nume_produs'] . '</option>';
                                            $count_check++;
                                        }
                                    }
                                    if ($count_check == 0) {
                                        echo '<option>Niciun vehicul disponibil pentru asigurare</option>';
                                    }
                                } else {
                                    echo '<option>Niciun vehicul disponibil pentru asigurare</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3 form-row mx-auto">
                        <div class="col-md-4">
                            <label for="judet">Judet :</label>
                            <select class="form-control" name="judet" id="judet">
                                <?php
                                    $file_path = 'json-files/judete.json';
                                    $json_data = file_get_contents($file_path);
                                    $judete = json_decode($json_data, true);
                                    if ($judete === null) {
                                        die('Eroare la decodificarea fișierului JSON.');
                                    }
                                    if(!empty($judete)){
                                        foreach($judete as $judet){
                                            if(isset($judet['abr'])&&isset($judet['nume'])){
                                                echo '<option value="' . $judet['abr'] . '">' . $judet['nume'] . '</option>';
                                            }else{
                                                echo '<option value="">Nu exista date disponibile</option>';
                                            }
                                        }
                                    }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="cifre">Cifre numar:</label>
                            <input type="text" class="form-control" id="cifre" name="cifre" maxlength="2">
                        </div>

                        <div class="col-md-4">
                            <label for="litere">Text personalizat (3 litere):</label>
                            <input type="text" class="form-control" id="litere" name="litere" maxlength="3">
                        </div>
                    </div>

                    <button type="submit" name="submit2" class="btn buton-trimitere mx-auto justify-content-center d-flex">Trimite</button>
                </form>
            </div>
            <script>
            document.getElementById('showBtn').addEventListener('click', function() {
                document.getElementById('content').style.display = 'block';
                this.style.display = 'none';
            });

            document.getElementById('closeBtn').addEventListener('click', function() {
                // Ascunde conținutul și afișează butonul inițial
                document.getElementById('content').style.display = 'none';
                document.getElementById('showBtn').style.display = 'block';
            });
            </script>
        <?php
    }else{
        echo '<h6 class="text-center bg-warning w-50 d-flex mx-auto justify-content-center mt-5">Nu sunteti conectat . Nu aveti acces la nimic din Registrul Auto fara o autentificare . Va rugam sa va logati , sau sa va faceti un cont daca nu aveti !</h6>';
        ?>
            <a href="login.php"><button class="cont text-center bg-info w-25 d-flex mx-auto justify-content-center text-decoration-none"><i class="fa fa-sign-in">Conectare</i></button></a>
            <a href="register.php"><button class="cont text-center bg-info w-25 d-flex mx-auto justify-content-center mt-1 text-decoration-none"><i class="fa fa-user-plus">Inregistrare</i></button></a>
        <?php
    }
?>
                    
<?php
include_once 'footer.php';
?>
