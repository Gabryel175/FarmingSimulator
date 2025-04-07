<?php 
    include_once 'header.php';
?>
<div class="navbar-secund">
  <div class="container-navbar mx-auto">
    <a href="index.php" class="douazecilasuta"><i class="fa fa-home"></i>Acasa</a>
    <a href="rar.php" class="douazecilasuta"><i class="fa fa-car"></i>Registru Auto</a>
    <a href="banca.php" class="douazecilasuta"><i class="fa fa-dollar"></i>Unitate Bancara</a>
    <a href="primarie.php" class="douazecilasuta"><i class="fa fa-building"></i>Primarie</a>
    <a href="dashboard.php" class="douazecilasuta"><i class="fa fa-wrench" aria-hidden="true"></i>Panou de control</a>
  </div>
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
$utilaje_amestecate=$dateUtilaje['utilaje_joc'];
shuffle($utilaje_amestecate);
$mysqli = new mysqli("localhost", "root", "root", "roleplay");

if ($mysqli->connect_error) {
    die("Conexiunea la baza de date a esuat: " . $mysqli->connect_error);
}
$checkTableQuery = "SHOW TABLES LIKE 'comenzi'";
$tableExists = $mysqli->query($checkTableQuery);

if ($tableExists->num_rows == 0) {
    $createTableQuery = "CREATE TABLE comenzi (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nume_produs VARCHAR(255) NOT NULL,
        pret VARCHAR(255) NOT NULL,
        categorie VARCHAR(255) NOT NULL,
        proprietar VARCHAR(255) NOT NULL,
        asigurare boolean not null,
        inregistrare boolean not null,
        data_asigurare VARCHAR(255)
    )";
    if (!$mysqli->query($createTableQuery)) {
        die("Eroare la crearea tabelului: " . $mysqli->error);
    }
}
$username = $_SESSION['username'];
$sql = "SELECT balanta FROM users WHERE username = '$username'";
$result = $mysqli->query($sql);
if ($result) {
    $row = $result->fetch_assoc();
    if ($row) {
        $balanta = $row['balanta'];
    } else {
        echo "Utilizatorul $username nu a fost găsit sau nu are balanta setată.";
    }
    $result->free();
} else {
    echo "Eroare la interogarea bazei de date: " . $mysqli->error;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add_to_cart') {
        $productName = $_POST['denumire'];
        $_SESSION['cart'][] = $productName;
    } elseif ($_POST['action'] === 'remove_from_cart') {
        $productName = $_POST['denumire'];
        $_SESSION['cart'] = array_diff($_SESSION['cart'], [$productName]);
    } elseif ($_POST['action'] === 'finalize_order') {
        if (isset($_SESSION['username'])) {
            if (!empty($_SESSION['cart'])) {
                $username = $_SESSION['username'];
                $orderName = 'Comanda_' . date('Ymd_His');
                $cost_total = 0;

                // Calculăm costul total al comenzii
                foreach ($_SESSION['cart'] as $productName) {
                    foreach ($utilaje_amestecate as $utilaj) {
                        if ($utilaj['denumire'] == $productName) {
                            $cost_total += $utilaj['pret'];
                        }
                    }
                }

                // Verificăm balanța utilizatorului
                $balantaQuery = "SELECT balanta FROM users WHERE username = '$username'";
                $balantaResult = $mysqli->query($balantaQuery);
                if ($balantaResult) {
                    $balantaRow = $balantaResult->fetch_assoc();
                    $balanta = $balantaRow['balanta'];
                    $balantaResult->free();
                } else {
                    die("Eroare la interogarea balanței: " . $mysqli->error);
                }

                if ($cost_total <= $balanta) {
                    $newBalance = $balanta - $cost_total;
                    $updateBalanceQuery = "UPDATE users SET balanta = '$newBalance' WHERE username = '$username'";
                    if (!$mysqli->query($updateBalanceQuery)) {
                        die("Eroare la actualizarea balanței: " . $mysqli->error);
                    }

                    // Inserăm produsele în comenzi
                    foreach ($_SESSION['cart'] as $productName) {
                        $insertOrderQuery = "INSERT INTO comenzi (nume_produs, pret, categorie, proprietar, asigurare, inregistrare, data_asigurare) VALUES ('$productName',";
                        foreach ($utilaje_amestecate as $utilaj) {
                            if ($utilaj['denumire'] == $productName) {
                                $pret = $utilaj['pret'];
                                $categorie = $utilaj['categorie'];
                                $insertOrderQuery .= "'$pret', '$categorie', '$username', 0, 0, '')";
                                break;
                            }
                        }

                        if (!$mysqli->query($insertOrderQuery)) {
                            die("Eroare la adăugarea produsului în comandă: " . $mysqli->error);
                        }
                    }

                    // Inserăm tranzacția în tabelul tranzactii
                    $insertTransactionQuery = "INSERT INTO tranzactii (nume, data_tranzactie, suma, motiv) 
                        VALUES (?, NOW(), ?, 'Comandă')";

                    $stmt = $mysqli->prepare($insertTransactionQuery);
                    $cost_total=-$cost_total;
                    $stmt->bind_param("sd", $username, $cost_total);
                    if ($stmt->execute()) {
                        echo "<script>alert('Comanda finalizată cu succes! Tranzacția a fost înregistrată.');</script>";
                        $_SESSION['cart'] = array(); // Golim cosul de cumpărături
                    } else {
                        echo "Eroare la inserarea tranzacției: " . $stmt->error;
                    }

                    $stmt->close();
                } else {
                    ?>
                    <script>
                        alert("Fonduri insuficiente");
                    </script>
                    <?php
                }
            } else {
                echo "<script>alert('Cosul tău este gol. Te rog să adaugi produse.');</script>";
            }
        } else {
            ?>
            <script>
                alert("Vă rugăm să vă autentificați înainte de finalizarea unei comenzi!");
            </script>
            <?php
        }
    }
}
?>
<?php
    $alegere=$_GET['choice'];
    echo '<h5>Rezultate pentru '.$alegere.'</h5>';
    
    if($alegere=='SmallTractors' || $alegere=="MediumTractors" || $alegere=="LargeTractors" || $alegere=="FrontLoaders" || $alegere=="WheelLoaders" || $alegere=="Telehanders" || $alegere=="Skidsteer" || $alegere=="Forklifts"){
                $count_toate=0;
                foreach($utilaje_amestecate as $utilaj){
                    if (isset($utilaj['categorie']) && $utilaj['categorie'] === $alegere){
                        $count_toate++;
                    }
                }
                echo '('.$count_toate.')';
                echo '<div class="row w-75 mx-auto">';
                foreach ($utilaje_amestecate as $utilaj) {
                    if (isset($utilaj['categorie']) && $utilaj['categorie'] === $alegere) {
                        echo '<div class="col-md-3 card">';
                            echo '<div class="imgBox">';
                                echo '<img src="media/shop/tractors/' . $utilaj['cod_produs'] . '.png" alt="Imagine produs">';
                            echo '</div>';
                            echo '<div class="contentBox">';
                                echo $utilaj['brand'].' '.$utilaj['denumire'].'<br>';
                                echo $utilaj['pret'].'$<br>';
                                ?>
                                <form method="post" class="buy">
                                    <input type="hidden" name="action" value="add_to_cart">
                                    <input type="hidden" name="denumire" value="<?php echo $utilaj['denumire']; ?>">
                                    <button type="submit">Add to cart</button>
                                </form>
                                <?php
                            echo '</div>';
                        echo '</div>';
                    }
                }
                echo '</div>';
            }else{
                $caleFisierJson = dirname(__FILE__) . '/json-files/utilaje.json';
                $continutFisier = file_get_contents($caleFisierJson);
                if ($continutFisier === false) {
                    die('Eroare la citirea fișierului JSON.');
                }
                $dateUtilaje = json_decode($continutFisier, true);
                if ($dateUtilaje === null && json_last_error() !== JSON_ERROR_NONE) {
                    die('Eroare la decodarea fișierului JSON.');
                }
                $utilaje_amestecate=$dateUtilaje['utilaje_joc'];
                shuffle($utilaje_amestecate);
                $count_toate=0;
                foreach($utilaje_amestecate as $utilaj){
                    if (isset($utilaj['categorie']) && $utilaj['categorie'] === $alegere){
                        $count_toate++;
                    }
                }
                echo '('.$count_toate.')';
                echo '<div class="row w-75 mx-auto">';
                foreach ($utilaje_amestecate as $utilaj) {
                    if (isset($utilaj['categorie']) && $utilaj['categorie'] === $alegere) {
                        echo '<div class="col-md-3 card">';
                            echo '<div class="imgBox">';
                                $img_path = strtolower($utilaj['brand'].'-'.$utilaj['denumire']);
                                // if($utilaj['categorie']=="Mods"){
                                //     $img_path="mod";
                                // }
                                echo '<img src="media/shop/atasamente/'.$img_path.'.png" alt="Imagine produs" width="200" height="200">';
                            echo '</div>';
                            echo '<div class="contentBox">';
                            echo $utilaj['brand'].' '.$utilaj['denumire'].'<br>';
                                echo $utilaj['pret'].'$<br>';
                                ?>
                                <form method="post" class="buy">
                                    <input type="hidden" name="action" value="add_to_cart">
                                    <input type="hidden" name="denumire" value="<?php echo $utilaj['denumire']; ?>">
                                    <button type="submit">Add to cart</button>
                                </form>
                                <?php
                            echo '</div>';
                        echo '</div>';
                    }
                }
                echo '</div>';
            }
            if($alegere=="ToateTractoare"){
                $caleFisierJson = dirname(__FILE__) . '/json-files/utilaje.json';
                $continutFisier = file_get_contents($caleFisierJson);
                if ($continutFisier === false) {
                    die('Eroare la citirea fișierului JSON.');
                }
                $dateUtilaje = json_decode($continutFisier, true);
                if ($dateUtilaje === null && json_last_error() !== JSON_ERROR_NONE) {
                    die('Eroare la decodarea fișierului JSON.');
                }
                $utilaje_amestecate=$dateUtilaje['utilaje_joc'];
                shuffle($utilaje_amestecate);
                $count_toate=count($utilaje_amestecate);
                echo '('.$count_toate.')';
                echo '<div class="row w-75 mx-auto">';
                foreach ($utilaje_amestecate as $utilaj) {
                    if (isset($utilaj['categorie']) && $utilaj['pentru'] === "Tractoare") {
                        echo '<div class="col-md-3 card">';
                            echo '<div class="imgBox">';
                                $img_path = strtolower($utilaj['brand'].'-'.$utilaj['denumire']);
                                $img_path_for_second = "media/shop/tractors/".$utilaj['cod_produs'];
                                if($utilaj['categorie']=="SmallTractors" || $utilaj['categorie']=="MediumTractors" || $utilaj['categorie']=="LargeTractors"){
                                    echo '<img src="media/shop/tractors/' . $utilaj['cod_produs'] . '.png" alt="Imagine produs">';
                                }else{
                                    echo '<img src="media/shop/atasamente/'.$img_path.'.png" alt="Imagine produs">';
                                }
                            echo '</div>';
                            echo '<div class="contentBox">';
                                echo $utilaj['brand'].' '.$utilaj['denumire'].'<br>';
                                echo $utilaj['pret'].'$<br>';
                                ?>
                                <form method="post" class="buy">
                                    <input type="hidden" name="action" value="add_to_cart">
                                    <input type="hidden" name="denumire" value="<?php echo $utilaj['denumire']; ?>">
                                    <button type="submit">Add to cart</button>
                                </form>
                                <?php
                            echo '</div>';
                        echo '</div>';
                    }
                }
                echo '</div>';
            }
        ?>
<?php $cost_total=0; ?>
<div class="cos-cumparaturi">
    <h2>Iteme alese :</h2>
    <?php
    echo 'Balanta ta este '.$balanta;
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $denumire) {
            $categorie = '';
            $pret_produs=0;
            foreach ($utilaje_amestecate as $utilaj) {
                if ($utilaj['denumire'] == $denumire) {
                    $categorie = $utilaj['categorie'];
                    $pret_produs=$utilaj['pret'];
                    break;
                }
            }
            $cost_total+=$pret_produs;
            echo '<div class="card-item">';
            echo '<span>Produs: ' . $denumire . ' - Categorie: ' . $categorie . '</span>';
            echo "<form method='post'>";
            echo "<input type='hidden' name='action' value='remove_from_cart'>";
            echo "<input type='hidden' name='denumire' value='$denumire'>";
            echo "<button type='submit'>Sterge</button>";
            echo "</form>";
            echo "</div>";
        }
        echo 'Cost total cos : ' . $cost_total;
        $preturi = array();
        foreach ($utilaje_amestecate as $utilaj) {
            if ($utilaj['denumire'] == $denumire) {
                $preturi[] = $utilaj['pret'];
            }
        }
        $preturi_serializate = json_encode($preturi);
        echo "<form method='post'>";
        echo "<input type='hidden' name='action' value='finalize_order'>";
        echo "<input type='hidden' name='pret' value='" . htmlspecialchars($preturi_serializate) . "'>";
        echo "<button type='submit'>Finalizeaza comanda</button>";
        echo "</form>";
    } else {
        echo "<p>Cosul tau este gol. Te rog sa adaugi produse.</p>";
    }
    ?>
</div>
<script>
function myFunction() {
  document.getElementById("myDropdown").classList.toggle("show");
}
window.onclick = function(event) {
  if (!event.target.matches('.dropbtn')) {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}
</script>
<script>
function myFunction() {
  document.getElementById("myDropdown").classList.toggle("show");
}
window.onclick = function(event) {
  if (!event.target.matches('.login-button')) {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}
</script>

<?php
    include_once 'functii.php';
?>