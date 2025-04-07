<?php 
include_once 'header.php';
?>
<div class="navbar-secund">
  <div class="container-navbar mx-auto">
    <a href="index.php" class="douazecilasuta"><i class="fas fa-shopping-cart"></i>Acasa</a>
    <a href="rar.php" class="douazecilasuta"><i class="fa fa-car"></i>Registru Auto</a>
    <a href="banca.php" class="douazecilasuta active"><i class="fa fa-dollar"></i>Unitate Bancara</a>
    <a href="primarie.php" class="douazecilasuta"><i class="fa fa-building"></i>Primarie</a>
    <a href="dashboard.php" class="douazecilasuta"><i class="fa fa-wrench" aria-hidden="true"></i>Panou de control</a>
  </div>
</div>
<?php 
$conn = new mysqli("localhost", "root", "root", "roleplay");
if($conn->connect_error){
    die("Conexiunea a esuat!");
}
if(isset($_SESSION['username'])){
    $username=$_SESSION['username'];
    $query="SELECT id,balanta,job FROM users WHERE username='$username'";
    $result=$conn->query($query);
    if($result->num_rows>0){
        while($row=$result->fetch_assoc()){
            $id=$row['id'];
            $balanta=$row['balanta'];
            $job=$row['job'];
        }
    }else{
        $id="-";
        $balanta="-";
        $job="-";
    }
}
?>
<div class="card-banca mx-auto mt-5">
    <div class="card-inner">
      <div class="card-front">
        <img class="imagine-logo-card-bancar" src="data:image/png;base64,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" alt="Mastercard Logo">
        <div class="card-info"><?php if(isset($_SESSION['username']) && $_SESSION['username']=="FarmingSimulator"){echo $_SESSION['username']." - Cont Primarie";}elseif(isset($_SESSION['username'])){echo $_SESSION['username'];} ?></div>
        <?php
            if(isset($_SESSION['username'])){
                ?>
                    <div class="card-info"><?php echo "ID CONT : ".$id; ?></div>
                    <div class="card-number"><?php echo "Balanta cont : "; ?></div>
                    <div class="card-info"><?php echo $balanta." $"; ?></div>
                <?php
            }else{
                ?>  
                    <h6 class="card-info">Model card membru</h6>
                    <p class="mic">nu esti inregistrat</p>
                    <div class="card-info"><?php echo "ID CONT : ID-ul tau"?></div>
                    <div class="card-number"><?php echo "Balanta cont : "; ?></div>
                    <div class="card-info"> x $</div>
                <?php
            }
        ?>
      </div>
      <div class="card-back">
        <img class="imagine-logo-card-bancar" src="data:image/png;base64,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" alt="Mastercard Logo">
        <div class="card-info">
            <?php
                if(isset($_SESSION['username'])){
                    $username=$_SESSION['username'];
                    $check_query = "SELECT * FROM imprumuturi WHERE nume_cetatean = ?";
                    $stmt = $conn->prepare($check_query);
                    $stmt->bind_param("s", $username);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()){
                            $suma_imprumut=$row['suma_imprumutata'];
                            $numar_rate=$row['numar_rate'];
                            $rate_platite=$row['rate_platite'];
                        }
                        echo '<h6>Imprumut activ de'.$suma_imprumut.'</h6>';
                        echo '<p>Imprumuturl este activ pe un numar fix de rate - '.$numar_rate.'</p>';
                        echo '<br>';
                        $dobanda=calcul_dobanda($suma_imprumut,$numar_rate);
                        $dobanda=$dobanda*100;
                        $suma_de_platit=$suma_imprumut/$numar_rate+$suma_imprumut*$dobanda/100;
                        echo '<p>Pentru rata dvs. , dobanda calculata este de '.$dobanda.'% , avand de platit lunar suma de '.$suma_de_platit.'</p>';
                    }else{
                        echo '<h6 class="card-imprumut">Nu detineti niciun fel de imprumut . Daca aveti nevoie de bani , puteti merge la sectiunea imprumuturi si sa faceti un imprumut !</h6>';
                    }
                }else{
                    echo "<h6 class='bg-danger text-light mx-auto text-center mt-5'>Nu sunteti conectat!</h6>";
                    echo '<p class="text-light">Pentru a avea acces la informatii , trebuie sa va conectati</p>';
                }
            ?>
        </div>
      </div>
    </div>
</div>
<div class="imprumuturi mt-3">
    <h1 class="titlu-imprumuturi">
        Imprumuturi
    </h1>
    <h4 class="sub-titlu-imprumuturi">
        Daca ai nevoie de bani , te poti imprumuta de la banca Farming
    </h4>
    <div class="d-flex">
        <figure>
        <img class="poza-imprumut" src="https://cdn-icons-png.flaticon.com/128/1538/1538344.png">
        <figcaption>Imprumuta</figcaption>
        </figure>
        <figure>
            <img class="poza-imprumut" src="https://cdn-icons-png.flaticon.com/128/4488/4488426.png" alt="">
            <figcaption>Tranzactii</figcaption>
        </figure>
        <figure>
            <img class="poza-imprumut" src="https://cdn-icons-png.flaticon.com/128/4222/4222019.png" alt="">
            <figcaption>Investeste</figcaption>
        </figure>
    </div>

    <?php
        if(isset($_SESSION['username']) && $_SESSION['username']=="FarmingSimulator"){
            echo "<h5 class='eroare-banca'>Primaria nu poate face imprumuturi</h5>";
        }else{
            if(isset($_SESSION['username']) && ($job==NULL || $job=="-")){
                echo "<h5 class='eroare-banca'>Nu ai carte de munca , nu iti poti face imprumut</h5>";
            }else{
                ?>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col">
                                <?php
                                    if(!isset($_SESSION['username'])){
                                        echo '<p class="mic">*Puteti vizualiza ofertele de imprumuturi</p>';
                                    }
                                ?>
                                <form method="post" action="procesare_imprumut.php">
                                    <label for="suma_imprumut" class="label-imprumut">Suma împrumutată:</label>
                                    <select class="form-control w-50" name="suma_imprumut" id="suma_imprumut">
                                        <option value="alege">Alege suma dorita</option>
                                        <option value="5000">5000 $</option>
                                        <option value="10000">10000 $</option>
                                        <option value="25000">25000 $</option>
                                        <option value="50000">50000 $</option>
                                        <option value="100000">100000 $</option>
                                    </select>
                                    <br>
                                    <label for="numar_rate" class="label-imprumut">Numărul de rate:</label>
                                    <select name="numar_rate" id="numar_rate" class="form-control w-50">
                                        <option value="Alege">Alege numar de rate</option>
                                    </select>
                                    <!-- <br> -->
                                    <label for="dobanda" class="label-imprumut">Dobândă:</label>
                                    <span id="dobanda_val"></span>
                                    <br>
                                    <label for="total" class="label-imprumut">Total de plata:</label>
                                    <span id="total_de_plata"></span>
                                    <?php
                                        if(isset($_SESSION['username'])){
                                            ?>
                                                <input type="hidden" name="username" value="<?php echo $_SESSION['username']; ?>">
                                                <br>
                                                <input type="submit" name="submit" value="Fa împrumut" class="btn btn-success">
                                            <?php
                                        }else{
                                            echo '<label class="label-imprumut">Nu puteti trimite formularul pentru imprumut daca nu sunteti conectat</label>';
                                        }
                                    ?>
                                </form>
                            </div>
                            <div class="col">
                                <?php
                                    if(isset($_SESSION['username'])){
                                        ?>
                                            <img src="https://cdn-icons-png.flaticon.com/128/9428/9428503.png" alt="">
                                            <p class="label-imprumut">Achita ratele</p>
                                        <?php
                                    }
                                ?>
                            </div>
                            <div class="col">
                                <?php
                                    if(isset($_SESSION['username'])){
                                        $username=$_SESSION['username'];
                                        $check_query = "SELECT * FROM imprumuturi WHERE nume_cetatean = ?";
                                        $stmt = $conn->prepare($check_query);
                                        $stmt->bind_param("s", $username);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()){
                                                $suma_imprumut=$row['suma_imprumutata'];
                                                $numar_rate=$row['numar_rate'];
                                                $rate_platite=$row['rate_platite'];
                                            }
                                            echo '<span class="label-imprumut">Imprumut de :</span>'.$suma_imprumut." $";
                                            echo '<br><span class="label-imprumut">Numar de rate :</span>'.$numar_rate;
                                            echo '<br>'.'<span class="label-imprumut">Rate platite :</span>'.$rate_platite;
                                            $dobanda = 0;

                                            if ($suma_imprumut == 5000) {
                                                if ($numar_rate == 2) {
                                                    $dobanda = 1.8 / 100;
                                                }
                                                if ($numar_rate == 5) {
                                                    $dobanda = 2.5 / 100;
                                                }
                                                if ($numar_rate == 10) {
                                                    $dobanda = 4 / 100;
                                                }
                                            }
                                            if ($suma_imprumut == 10000) {
                                                if ($numar_rate == 5) {
                                                    $dobanda = 1.8 / 100;
                                                }
                                                if ($numar_rate == 10) {
                                                    $dobanda = 2 / 100;
                                                }
                                                if ($numar_rate == 25) {
                                                    $dobanda = 2.8 / 100;
                                                }
                                                if ($numar_rate == 50) {
                                                    $dobanda = 4 / 100;
                                                }
                                            }
                                            if ($suma_imprumut == 25000) {
                                                if ($numar_rate == 10) {
                                                    $dobanda = 2 / 100;
                                                }
                                                if ($numar_rate == 25) {
                                                    $dobanda = 2.5 / 100;
                                                }
                                                if ($numar_rate == 50) {
                                                    $dobanda = 3.2 / 100;
                                                }
                                            }
                                            if ($suma_imprumut == 50000) {
                                                if ($numar_rate == 10) {
                                                    $dobanda = 2 / 100;
                                                }
                                                if ($numar_rate == 25) {
                                                    $dobanda = 2.8 / 100;
                                                }
                                                if ($numar_rate == 100) {
                                                    $dobanda = 3.2 / 100;
                                                }
                                            }
                                            echo '<br><span class="label-imprumut">Dobanda : </span>'.$dobanda*$suma_imprumut." $";
                                            if($rate_platite==0){
                                                echo "<h6><span class='text-danger'>ATENTIE!!!</span>Nu ati platit nicio rata</h6>";
                                            }
                                            ?>
                                                <form id="achitaForm" action="procesare_achitare.php" method="post">
                                                    <input type="hidden" name="username" value="<?php echo $username; ?>">
                                                    <input type="hidden" name="suma_imprumut" value="<?php echo $suma_imprumut; ?>">
                                                    <input type="hidden" name="numar_rate" value="<?php echo $numar_rate; ?>">
                                                    <input type="hidden" name="dobanda" value="<?php echo $dobanda; ?>">
                                                    <input type="hidden" name="rate_platite" value="<?php echo $rate_platite; ?>">
                                                    <button type="submit" name="achita_rata" class="btn btn-primary">Achită rata</button>
                                                </form>
                                            <?php
                                        }else{
                                            echo '<img src="https://cdn-icons-png.flaticon.com/128/677/677069.png" alt="" class="mx-auto d-flex">';
                                            echo '<span class="bg-success text-light d-block text-center">Nu aveti imprumut activ!</span>';
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                    </div>

                <?php
            }
        }
    ?>
</div>

<div class="mt-5 p-5">
    <div class="container">
        <div class="row">
            <div class="col-md transfer-bancar">
                <h4>Transfera bani oricui , oricand  si fara comision !</h4>
                <p>Suma maxima pe care o puteti transfera fara a avea comision este de 20 000 $ . Daca suma depaseste aceasta valoare , se va aplica un comision de 5% din diferenta.Suma maxima pe care o poti trimite este 70% din bilantul tau financiar</p>
                <form action="procesare_transfer.php" method="POST" class="form-inline">
                    <label for="inlineFormCustomSelectPref" class="my-1 mr-2">Id-ul contului catre care trimiti:</label>
                    <select name="id_destinatar" id="inlineFormCustomSeelectPref" class="custom-select my-1 mr-sm-2 form-control w-50">
                        <option selected>Alege</option>
                        <?php
                        $sql = "SELECT id FROM users";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo '<option value="'.$row['id'].'">Utilizator'.$row['id'].'</option>';
                            }
                        } else {
                            echo "Nu s-au găsit rezultate.";
                        }
                        ?>
                    </select>
                    <?php
                    $username = $_SESSION['username'];
                    $sql_balanta = "SELECT balanta FROM users WHERE username = '$username'";
                    $result_balanta = $conn->query($sql_balanta);
                    if ($result_balanta->num_rows > 0) {
                        while ($row_balanta = $result_balanta->fetch_assoc()) {
                            $balanta = $row_balanta['balanta'];
                            echo '<p>Balanta ta : '.$balanta.'</p>';
                        }
                    } else {
                        echo 'Nu s-a gasit nicio balanta!';
                    }
                    ?>
                    <input type="number" id="suma_transfer" name="suma_transfer" class="media_screen form-control my-1 mr-sm-2 w-50" placeholder="<?php $procent=0.7*$balanta; echo 'Maxim '.$procent.' $'; ?>" required>
                    <div id="warning" style="color: red;"></div>
                    <button type="submit" class="btn btn-success my-1">Trimite</button>
                </form>
            </div>
            <!-- <div class="col-md-4">
                <img src="media/transfer-bank.png" alt="" class="w-100">
            </div> -->
        </div>
    </div>
</div>
<?php
    if(isset($_SESSION['username']) && $_SESSION['username']=="FarmingSimulator"){
        ?>
        
        <?php
    }
?>
<script>
    // Funcție pentru a verifica suma introdusă și pentru a afișa un avertisment dacă depășește 70% din balanță
    document.getElementById('suma_transfer').addEventListener('input', function() {
        var sumaTransfer = parseInt(this.value); // Obține valoarea introdusă în input
        var balanta = <?php echo $balanta; ?>; // Obține balanța din PHP

        var limita = 0.7 * balanta; // Calculează 70% din balanță

        // Verifică dacă suma introdusă depășește 70% din balanță și afișează avertismentul corespunzător
        if (sumaTransfer > limita) {
            document.getElementById('warning').innerHTML = 'Suma introdusă depășește 70% din balanța disponibilă!';
        } else {
            document.getElementById('warning').innerHTML = ''; // Șterge avertismentul dacă suma este în limite
        }
    });
</script>
    <script>
        document.getElementById('suma_imprumut').addEventListener('change', function() {
            var suma_imprumut = parseInt(this.value);
            var numar_rate_select = document.getElementById('numar_rate');

            // Șterge toate opțiunile existente
            numar_rate_select.innerHTML = '';

            // Adaugă opțiunile corespunzătoare pentru numărul de rate
            if (suma_imprumut == 5000) {
                addOptions(numar_rate_select, ["Alege",2, 5, 10]);
            } else if (suma_imprumut == 10000) {
                addOptions(numar_rate_select, ["Alege",5, 10, 25, 50]);
            } else if (suma_imprumut == 25000) {
                addOptions(numar_rate_select, ["Alege",10, 25, 50]);
            } else if (suma_imprumut == 50000 || suma_imprumut == 100000) {
                addOptions(numar_rate_select, ["Alege",10, 25, 100]);
            }
        });

        document.getElementById('numar_rate').addEventListener('change', function() {
    var numar_rate = parseInt(this.value);
    var suma_imprumut = parseInt(document.getElementById('suma_imprumut').value);
    var dobanda_val = document.getElementById('dobanda_val');
    var total_de_plata = document.getElementById('total_de_plata'); // Corectare aici

    var dobanda = 0;
    var total = 0; // Corectare aici

    if (suma_imprumut == 5000) {
        if (numar_rate == 2) {
            dobanda = 1.8 / 100;
        }
        if (numar_rate == 5) {
            dobanda = 2.5 / 100;
        }
        if (numar_rate == 10) {
            dobanda = 4 / 100;
        }
    }
    if (suma_imprumut == 10000) {
        if (numar_rate == 5) {
            dobanda = 1.8 / 100;
        }
        if (numar_rate == 10) {
            dobanda = 2 / 100;
        }
        if (numar_rate == 25) {
            dobanda = 2.8 / 100;
        }
        if (numar_rate == 50) {
            dobanda = 4 / 100;
        }
    }
    if (suma_imprumut == 25000) {
        if (numar_rate == 10) {
            dobanda = 2 / 100;
        }
        if (numar_rate == 25) {
            dobanda = 2.5 / 100;
        }
        if (numar_rate == 50) {
            dobanda = 3.2 / 100;
        }
    }
    if (suma_imprumut == 50000) {
        if (numar_rate == 10) {
            dobanda = 2 / 100;
        }
        if (numar_rate == 25) {
            dobanda = 2.8 / 100;
        }
        if (numar_rate == 100) {
            dobanda = 3.2 / 100; // Corectare aici
        }
    }

    var valoare_dobanda = (suma_imprumut * dobanda).toFixed(2);
    var total = (suma_imprumut + numar_rate * dobanda * suma_imprumut).toFixed(2);
    dobanda_val.textContent = valoare_dobanda + " $";
    total_de_plata.textContent = total + " $"; // Corectare aici
});


        function addOptions(select, options) {
            for (var i = 0; i < options.length; i++) {
                var option = document.createElement('option');
                option.value = options[i];
                option.text = options[i];
                select.appendChild(option);
            }
        }
    </script>