<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <style>

    .hidden{
      display: none;
    }
    #myInput {
  background-image: url('/css/searchicon.png');
  background-position: 10px 10px;
  background-repeat: no-repeat;
  width: 100%;
  font-size: 16px;
  padding: 12px 20px 12px 40px;
  border: 1px solid #ddd;
  margin-bottom: 12px;
}

#myTable {
  border-collapse: collapse;
  width: 100%;
  border: 1px solid #ddd;
  font-size: 10px;
}

#myTable th, #myTable td {
  text-align: left;
  padding: 10px;
}

#myTable tr {
  border-bottom: 1px solid #ddd;
}

#myTable tr.header, #myTable tr:hover {
  background-color: #f1f1f1;
}
.titlu-firma { 
  font-family:Verdana, Geneva, Tahoma, sans-serif;
}
  </style>
<?php include_once 'header.php'; ?>
<div class="navbar-secund">
  <div class="container-navbar mx-auto">
    <a href="index.php" class="douazecilasuta"><i class="fas fa-home"></i>Acasa</a>
    <a href="rar.php" class="douazecilasuta"><i class="fa fa-car"></i>Registru Auto</a>
    <a href="banca.php" class="douazecilasuta"><i class="fa fa-dollar"></i>Unitate Bancara</a>
    <a href="primarie.php" class="douazecilasuta"><i class="fa fa-building"></i>Primarie</a>
    <a href="dashboard.php" class="douazecilasuta active"><i class="fa fa-wrench" aria-hidden="true"></i>Panou de control</a>
  </div>
</div>


<div class="container-fluid butoane">
  <button type="button" class="btn btn-primary buton-dashboard" data-toggle="modal" data-target="#myModal1">
    Tranzactii
  </button>
  <button type="button" class="btn btn-primary buton-dashboard" data-toggle="modal" data-target="#myModal2">
    Impozite
  </button>
  <button type="button" class="btn btn-primary buton-dashboard" data-toggle="modal" data-target="#myModal3">
    Vanzare / cumparare
  </button>
  <button type="button" class="btn btn-primary buton-dashboard" data-toggle="modal" data-target="#myModal4">
    Taxe permise si atestate
  </button>
    

  <!-- The Modal -->
  <div class="modal fade" id="myModal1">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Lista tranzactii</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
        <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Cauta dupa nume..." title="Cauta dupa nume...">

        <table id="myTable">
          <tr class="header">
            <th>Id</th>
            <th>Nume</th>
            <th>Data</th>
            <th>Suma</th>
            <th>Motiv</th>
          </tr>
          <?php
          $conn = new mysqli("localhost", "root", "root", "roleplay");

          if ($conn->connect_error) {
              die("Conexiune esuata: " . $conn->connect_error);
          }

          $sql = "SELECT * FROM tranzactii";
          $result = $conn->query($sql);

          if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                  echo '<tr>';
                  echo '<td>' . $row['id_tranzactie'] . '</td>';
                  echo '<td>' . $row['nume'] . '</td>';
                  echo '<td>' . $row['data_tranzactie'] . '</td>';
                  echo '<td>' . $row['suma'] . '</td>';
                  echo '<td>' . $row['motiv'] . '</td>';
                  echo '</tr>';
              }
          } else {
              echo '<tr><td colspan="5">0 rezultate</td></tr>';
          }

          $conn->close();
          ?>
        </table>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>
  <div class="modal fade" id="myModal2">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Achitare impozite</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
        <form id="transactionForm" action="procesare_impozit.php" method="post">
          <label for="category">Categorie:</label>
          <select id="category" name="category" onchange="showInputs()">
            <option value="">Selectează...</option>
            <option value="vehicul_b">Vehicul Cat B</option>
            <option value="vehicul_c">Vehicul Cat C</option>
            <option value="tractor_utilaj">Tractor/Utilaj</option>
            <option value="teren_intravilan">Teren Intravilan</option>
            <option value="teren_extravilan">Teren Extravilan</option>
            <option value="firma_transport">Firmă Transport</option>
            <option value="firma_ferma">Firmă Fermă</option>
            <option value="firma_lemne">Firmă Lemne</option>
            <option value="firma_productie">Firmă Producție</option>
          </select>

          <div id="powerInput" class="hidden">
            <label for="horsePower">Număr de Cai Putere:</label>
            <input type="number" id="horsePower" name="horsePower" min="0">
          </div>

          <div id="licenseInput" class="hidden">
            <label for="licensePlate">Număr de Înmatriculare:</label>
            <input type="text" id="licensePlate" name="licensePlate">
          </div>

          <div id="areaInput" class="hidden">
            <label for="areaSize">Mărime Teren (mp):</label>
            <input type="number" id="areaSize" name="areaSize" min="0">
          </div>

          <div id="companyInputs" class="hidden">
            <label for="numEmployees">Număr Angajați:</label>
            <input type="number" id="numEmployees" name="numEmployees" min="0">
            <label for="annualRevenue">Venit Anual ($):</label>
            <input type="number" id="annualRevenue" name="annualRevenue" min="0">
            <label for="numEquipment">Număr Utilaje:</label>
            <input type="number" id="numEquipment" name="numEquipment" min="0">
          </div>

          <div id="farmLumberInputs" class="hidden">
            <label for="annualRevenue">Venit Anual ($):</label>
            <input type="number" id="annualRevenueFarmLumber" name="annualRevenueFarmLumber" min="0">
            <label for="numEquipment">Număr Utilaje:</label>
            <input type="number" id="numEquipmentFarmLumber" name="numEquipmentFarmLumber" min="0">
          </div>

          <button type="submit">Trimite</button>
        </form>

        <script>
          function showInputs() {
            document.getElementById('powerInput').classList.add('hidden');
            document.getElementById('licenseInput').classList.add('hidden');
            document.getElementById('areaInput').classList.add('hidden');
            document.getElementById('companyInputs').classList.add('hidden');
            document.getElementById('farmLumberInputs').classList.add('hidden');

            const category = document.getElementById('category').value;

            if (category === 'vehicul_b' || category === 'vehicul_c' || category === 'tractor_utilaj') {
              document.getElementById('powerInput').classList.remove('hidden');
              document.getElementById('licenseInput').classList.remove('hidden');
            } else if (category === 'teren_intravilan' || category === 'teren_extravilan') {
              document.getElementById('areaInput').classList.remove('hidden');
            } else if (category === 'firma_transport' || category === 'firma_productie') {
              document.getElementById('companyInputs').classList.remove('hidden');
            } else if (category === 'firma_ferma' || category === 'firma_lemne') {
              document.getElementById('farmLumberInputs').classList.remove('hidden');
            }
          }
        </script>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>
  <div class="modal fade" id="myModal3">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Cumpara / Vinde </h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
          <div class="container">
            <div class="row">
              <div class="col-md-6">
                  <h4>Cumparare produse agricole </h4>
                  <p>*AVETI DREPT DE CUMPARARE SI FARA FIRMA</p>
                  <form id="transactionForm" action="procesare_vanzare.php" method="post">
                    <label for="product">Produs:</label>
                    <select id="product" name="product" onchange="showQuantityInput()">
                    <option value="">Selectează...</option>
                      <option value="Grau">Grau - 875</option>
                      <option value="Ovaz">Ovăz - 880</option>
                      <option value="Secara">Secară - 900</option>
                      <option value="Rapita">Răpită - 1300</option>
                      <option value="Sorghum">Sorghum - 970</option>
                      <option value="Struguri">Struguri - 1170</option>
                      <option value="Masline">Măsline - 1400</option>
                      <option value="Floare">Floare - 1450</option>
                      <option value="Soia">Soia - 1460</option>
                      <option value="Porumb">Porumb - 980</option>
                      <option value="Cartofi">Cartofi - 1500</option>
                      <option value="SugarBeet">Sugar Beet - 1750</option>
                      <option value="SugarCane">Sugar Cane - 1100</option>
                      <option value="Cotton">Cotton - 1675</option>
                      <option value="Paie">Paie - 250</option>
                      <option value="Fan">Fan - 230</option>
                      <option value="Iarba">Iarba - 200</option>
                      <option value="Sillage">Sillage - 350</option>
                      <option value="FertilizantSolid">Fertilizant Solid - 500</option>
                      <option value="FertilizantLichid">Fertilizant Lichid - 420</option>
                      <option value="IerbicidLichid">Ierbicid Lichid - 410</option>
                      <option value="Lime">Lime - 525</option>
                      <option value="Sare">Sare - 200</option>
                      <option value="Seminte">Seminte - 530</option>
                      <option value="Rumegus">Rumeguș - 150</option>
                      <option value="Pin">Pin - 400</option>
                      <option value="Brad">Brad - 410</option>
                      <option value="AltLemn">Alt lemn - 280</option>
                      <option value="Motorina">Motorină - 8000</option>
                      <option value="AddBlue">Add blue - 15000</option>
                      <option value="Lapte">Lapte - 800</option>
                      <option value="Oua">Ouă - 600</option>
                      <option value="Lana">Lână - 1500</option>
                      <option value="Zahar">Zahar - 600</option>
                      <option value="Piatra">Piatra - 290</option>
                      <option value="Paine">Paine - 3270</option>
                      <option value="Faina">Faina - 1800</option>
                      <option value="Ciocolata">Ciocolata - 1900</option>
                      <option value="Branza">Branza - 1650</option>
                      <option value="Unt">Unt - 1450</option>
                      <option value="Tort">Tort - 12600</option>
                      <option value="Transport">Transport - 120</option>
                      <option value="Planks">Planks - 550</option>
                      <option value="Furniture">Furniture - 600</option>
                      <option value="Rye">Rye - 860</option>
                      <option value="Alfalfa">Alfalfa - 300</option>
                      <option value="AlfalfaHay">AlfalfaHay - 320</option>
                      <option value="Manure">Manure - 220</option>
                      <option value="Slurry">Slurry - 280</option>
                      <option value="Sillage">Sillage - 400</option>
                      <option value="Salt">Salt - 250</option>
                      <option value="TransportFirma">TransportFirma - 35</option>
                      <option value="Apa">Apa - 100</option>
                      <option value="Salata">Salata - 410</option>
                      <option value="Rosii">Rosii - 300</option>
                      <option value="Capsuni">Capsuni - 350</option>
                      <option value="Mere">Mere - 320</option>
                      <option value="SucMere">Suc Mere - 1000</option>
                      <option value="Miere">Miere - 980</option>
                      <option value="ConservePeste">Conserve Peste - 320</option>
                      <option value="MineralFeed">Mineral Feed - 200</option>
                      <option value="Stafide">Stafide - 300</option>
                      <option value="Cereale">Cereale - 2900</option>
                      <option value="Fabric">Fabric - 3150</option>
                      <option value="Firewood">Firewood - 650</option>
                      <option value="Crib">Crib - 1900</option>
                      <option value="UleiFloare/Rapita">Ulei Rapita / Floare - 3400</option>
                      <option value="UleiMasline">Ulei Masline - 4000</option>
                      <option value="Peleti">Peleti - 1000</option>
                      <option value="Clipboard">Clipboard - 1300</option>
                      <option value="Casute">Casute pasarici - 8000</option>
                    </select>

                    <div id="quantityInput" class="hidden">
                      <label for="quantity">Cantitate (l):</label>
                      <input type="number" id="quantity" name="quantity" min="0">
                    </div>

                    <button type="submit">Cumpara produs</button>
                  </form>

                  <script>
                    function showQuantityInput() {
                      document.getElementById('quantityInput').classList.remove('hidden');
                    }
                  </script>
              </div>
              <div class="col-md-6">
                <h4>Vanzare produse agricole </h4>
                <p>*DACA NU AVETI FIRMA , NU AVETI DREPT SA VINDETI</p>
               <form id="transactionForm" action="procesare_cumparare.php" method="post">
                    <label for="product2">Produs:</label>
                    <select id="product2" name="product2" onchange="showQuantityInput2()">
                      <option value="">Selectează...</option>
                      <option value="Grau">Grau - 875</option>
                      <option value="Ovaz">Ovăz - 880</option>
                      <option value="Secara">Secară - 900</option>
                      <option value="Rapita">Răpită - 1300</option>
                      <option value="Sorghum">Sorghum - 970</option>
                      <option value="Struguri">Struguri - 1170</option>
                      <option value="Masline">Măsline - 1400</option>
                      <option value="Floare">Floare - 1450</option>
                      <option value="Soia">Soia - 1460</option>
                      <option value="Porumb">Porumb - 980</option>
                      <option value="Cartofi">Cartofi - 1500</option>
                      <option value="SugarBeet">Sugar Beet - 1750</option>
                      <option value="SugarCane">Sugar Cane - 1100</option>
                      <option value="Cotton">Cotton - 1675</option>
                      <option value="Paie">Paie - 250</option>
                      <option value="Fan">Fan - 230</option>
                      <option value="Iarba">Iarba - 200</option>
                      <option value="Sillage">Sillage - 350</option>
                      <option value="FertilizantSolid">Fertilizant Solid - 500</option>
                      <option value="FertilizantLichid">Fertilizant Lichid - 420</option>
                      <option value="IerbicidLichid">Ierbicid Lichid - 410</option>
                      <option value="Lime">Lime - 525</option>
                      <option value="Sare">Sare - 200</option>
                      <option value="Seminte">Seminte - 530</option>
                      <option value="Rumegus">Rumeguș - 150</option>
                      <option value="Pin">Pin - 400</option>
                      <option value="Brad">Brad - 410</option>
                      <option value="AltLemn">Alt lemn - 280</option>
                      <option value="Motorina">Motorină - 8000</option>
                      <option value="AddBlue">Add blue - 15000</option>
                      <option value="Lapte">Lapte - 800</option>
                      <option value="Oua">Ouă - 600</option>
                      <option value="Lana">Lână - 1500</option>
                      <option value="Zahar">Zahar - 600</option>
                      <option value="Piatra">Piatra - 290</option>
                      <option value="Paine">Paine - 3270</option>
                      <option value="Faina">Faina - 1800</option>
                      <option value="Ciocolata">Ciocolata - 1900</option>
                      <option value="Branza">Branza - 1650</option>
                      <option value="Unt">Unt - 1450</option>
                      <option value="Tort">Tort - 12600</option>
                      <option value="Transport">Transport - 120</option>
                      <option value="Planks">Planks - 550</option>
                      <option value="Furniture">Furniture - 600</option>
                      <option value="Rye">Rye - 860</option>
                      <option value="Alfalfa">Alfalfa - 300</option>
                      <option value="AlfalfaHay">AlfalfaHay - 320</option>
                      <option value="Manure">Manure - 220</option>
                      <option value="Slurry">Slurry - 280</option>
                      <option value="Sillage">Sillage - 400</option>
                      <option value="Salt">Salt - 250</option>
                      <option value="TransportFirma">TransportFirma - 35</option>
                      <option value="Salata">Salata - 410</option>
                      <option value="Rosii">Rosii - 300</option>
                      <option value="Capsuni">Capsuni - 350</option>
                      <option value="Mere">Mere - 320</option>
                      <option value="SucMere">Suc Mere - 1000</option>
                      <option value="Miere">Miere - 980</option>
                      <option value="ConservePeste">Conserve Peste - 320</option>
                      <option value="Stafide">Stafide - 300</option>
                      <option value="Cereale">Cereale - 2900</option>
                      <option value="Fabric">Fabric - 3150</option>
                      <option value="Firewood">Firewood - 650</option>
                      <option value="Crib">Crib - 1900</option>
                      <option value="UleiFloare/Rapita">Ulei Rapita / Floare - 3400</option>
                      <option value="UleiMasline">Ulei Masline - 4000</option>
                      <option value="Peleti">Peleti - 1000</option>
                      <option value="Clipboard">Clipboard - 1300</option>
                      <option value="Casute">Casute pasarici - 8000</option>
                      <option value="kW">kW - 2400</option>

                    </select>

                    <div id="quantityInput2" class="hidden">
                      <label for="quantity2">Cantitate (l):</label>
                      <input type="number2" id="quantity2" name="quantity2" min="0">
                    </div>

                    <button type="submit">Vinde produs</button>
                  </form>

                  <script>
                    function showQuantityInput2() {
                      document.getElementById('quantityInput2').classList.remove('hidden');
                    }
                  </script>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>
  <div class="modal fade" id="myModal4">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Atestate si permise</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
          Modal body..
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>
</div>
<div class="firme">
  <h1 class="titlu-firma">Creare Firmă</h1>
  <form action="adauga_firma.php" method="post">
      <label for="nume_firma">Nume Firmă:</label>
      <input type="text" id="nume_firma" name="nume_firma" required><br><br>
      
      <label for="tip_firma">Tip Firmă:</label>
      <select id="tip_firma" name="tip_firma">
          <option value="transport">Firma Transport</option>
          <option value="ferma">Firma Fermă</option>
          <option value="lemn">Firma Lemn</option>
          <option value="productie">Firma Producție</option>
      </select><br><br>
      
      <input type="hidden" name="username" value="<?php echo $_SESSION['username']; ?>">
      
      <input type="submit" value="Adaugă Firmă">
  </form>
</div>
<?php
if (!isset($_SESSION['username'])) {
  die("Eroare: Nu sunteți autentificat. <a href='login.php'>Loghează-te</a>");
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
$sql_check_firma = "SELECT * FROM firme WHERE proprietar_firma = ?";
$stmt_check_firma = $conn->prepare($sql_check_firma);
$stmt_check_firma->bind_param("s", $username);
$stmt_check_firma->execute();
$result = $stmt_check_firma->get_result();
$firme = $result->fetch_all(MYSQLI_ASSOC);
$stmt_check_firma->close();
$conn->close();
?>
<div class="firme">
<h1>Adăugare Utilaje</h1>
    <?php if ($firme): ?>
        <form id="form_select_firma" method="post" action="adauga_utilaje.php">
            <label class="titlu-firma" for="select_firma">Selectează Firma:</label>
            <select id="select_firma" name="firma_id">
                <?php foreach ($firme as $firma): ?>
                    <option value="<?php echo $firma['id']; ?>"><?php echo htmlspecialchars($firma['nume_firma']); ?></option>
                <?php endforeach; ?>
            </select>
            <br><br>
            <label for="utilaje">Adaugă Utilaje:</label>
            <input type="text" id="utilaje" name="utilaje">
            <br><br>
            <button type="submit">Adaugă Utilaje</button>
        </form>
    <?php else: ?>
        <p>Nu aveți o firmă. Vă rugăm să creați una</p>
    <?php endif; ?>
</div>
<?php
if ($username == "FarmingSimulator") {
    $servername = "localhost";
    $dbusername = "root";
    $dbpassword = "root";
    $dbname = "roleplay";

    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
    if ($conn->connect_error) {
        die("Conexiune eșuată: " . $conn->connect_error);
    }

    // Preluare date din tabelul `atestate`
    $sql = "SELECT * FROM atestate";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        // Afișare date
        echo '<input type="text" id="filterInput" placeholder="Cauta permise dupa nume...">';
        echo '<table id="dataTable">';
        echo '<thead><tr>';
        
        // Afișare anteturi coloane
        $fields = $result->fetch_fields();
        foreach ($fields as $field) {
            echo '<th>' . htmlspecialchars($field->name) . '</th>';
        }
        echo '</tr></thead>';
        echo '<tbody>';
        
        // Afișare date
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            foreach ($row as $value) {
                // Înlocuire valoare 1 cu Da și valoare NULL cu Nu
                if (is_null($value)) {
                    $value = '✘';
                } elseif ($value == 1) {
                    $value = '✔';
                }
                echo '<td>' . htmlspecialchars($value) . '</td>';
            }
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    } else {
        echo "Niciun rezultat";
    }

    $conn->close();
}
?>
<?php
if ($username == "FarmingSimulator") {
    $servername = "localhost";
    $dbusername = "root";
    $dbpassword = "root";
    $dbname = "roleplay";

    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
    if ($conn->connect_error) {
        die("Conexiune eșuată: " . $conn->connect_error);
    }

    // Preluare date din tabelul `atestate`
    $sql = "SELECT * FROM comenzi";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        // Afișare date
        echo '<input type="text" id="filterInput2" placeholder="Cauta autovehicule dupa numarul de inmatriculare...">';
        echo '<table id="dataTable2">';
        echo '<thead><tr>';
        
        // Afișare anteturi coloane
        $fields = $result->fetch_fields();
        foreach ($fields as $field) {
            echo '<th>' . htmlspecialchars($field->name) . '</th>';
        }
        echo '</tr></thead>';
        echo '<tbody>';
        
        // Afișare date
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            foreach ($row as $value) {
                // Înlocuire valoare 1 cu Da și valoare NULL cu Nu
                if ($value==0) {
                    $value = '✘';
                } elseif ($value == 1) {
                    $value = '✔';
                }
                echo '<td>' . htmlspecialchars($value) . '</td>';
            }
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    } else {
        echo "Niciun rezultat";
    }
    
    $conn->close();
}
?>
<script>
        document.getElementById('filterInput').addEventListener('input', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#dataTable tbody tr');

            rows.forEach(row => {
                let cells = row.querySelectorAll('td');
                let match = Array.from(cells).some(cell => cell.textContent.toLowerCase().includes(filter));
                row.style.display = match ? '' : 'none';
            });
        });
    </script>
    <script>
        document.getElementById('filterInput2').addEventListener('input', function() {
            let filter = this.value.toLowerCase();
            let table = document.getElementById('dataTable2');
            let rows = table.querySelectorAll('tbody tr');

            rows.forEach(row => {
                let cell = row.cells[1]; // Index pentru a doua coloană
                let text = cell.textContent || cell.innerText;
                row.style.display = text.toLowerCase().includes(filter) ? '' : 'none';
            });
        });
    </script>
<script>
        document.getElementById('filterInput2').addEventListener('input', function() {
            let filter = this.value.toLowerCase();
            let table = document.getElementById('dataTable2');
            let rows = table.querySelectorAll('tbody tr');

            rows.forEach(row => {
                let lastCell = row.cells[row.cells.length - 1]; // Index pentru ultima coloană
                let text = lastCell.textContent || lastCell.innerText;
                row.style.display = text.toLowerCase().includes(filter) ? '' : 'none';
            });
        });
    </script>
<script>
// Butonul pentru informatii cont
var btn_meu = document.getElementById("butonul_meu");
var modal_meu = document.getElementById("mymodal_dashboard");
btn_meu.onclick = function() {
  modal_meu.style.display = "block";
}

// Butonul pentru email
var btn_email = document.getElementById("butonul_email");
var modal_email = document.getElementById("modal_email");
btn_email.onclick = function() {
  modal_email.style.display = "block";
}

// Butonul pentru parola
var btn_parola = document.getElementById("butonul_parola");
var modal_parola = document.getElementById("modal_parola");
btn_parola.onclick = function() {
  modal_parola.style.display = "block";
}

// Butonul pentru data
var btn_data = document.getElementById("butonul_data");
var modal_data = document.getElementById("modal_data");
btn_data.onclick = function() {
  modal_data.style.display = "block";
}

// Butonul pentru ID
var btn_id = document.getElementById("butonul_id");
var modal_id = document.getElementById("modal_id");
btn_id.onclick = function() {
  modal_id.style.display = "block";
}

// Funcția pentru închiderea modalului când se apasă pe X
var spans = document.getElementsByClassName("close");
for (var i = 0; i < spans.length; i++) {
  spans[i].onclick = function() {
    var modals = document.getElementsByClassName("modal_dashboard");
    for (var j = 0; j < modals.length; j++) {
      modals[j].style.display = "none";
    }
  }
}

// Funcția pentru închiderea modalului când se apasă în afara acestuia
window.onclick = function(event) {
  if (event.target.className == "modal_dashboard") {
    event.target.style.display = "none";
  }
}
</script>
