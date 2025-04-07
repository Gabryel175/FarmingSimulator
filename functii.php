<?php
// Functia pentru a verifica daca utilizatorul este autentificat
function is_user_logged_in() {
    return isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] == true;
}
?>

<?php
function logare(){
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $database = "roleplay";

    // Crearea conexiunii
    $conn = new mysqli($servername, $username, $password, $database);

    // Verificare conexiune
    if ($conn->connect_error) {
        die("Conexiunea la baza de date a eșuat: " . $conn->connect_error);
    }
    }
function calcul_dobanda($suma_imprumut,$numar_rate){
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
    return $dobanda;
}
function check_index_for_img_path($index){
    $unu="https://cdn-icons-png.flaticon.com/128/9494/9494567.png";
    $two="https://cdn-icons-png.flaticon.com/128/9494/9494600.png";
    $three="https://cdn-icons-png.flaticon.com/128/9494/9494620.png";
    $four="https://cdn-icons-png.flaticon.com/128/9494/9494623.png";
    $five="https://cdn-icons-png.flaticon.com/128/9494/9494626.png";
    $six="https://cdn-icons-png.flaticon.com/128/9494/9494628.png";
    $seven="https://cdn-icons-png.flaticon.com/128/9494/9494630.png";
    $eight="https://cdn-icons-png.flaticon.com/128/9494/9494632.png";
    $nine="https://cdn-icons-png.flaticon.com/128/9494/9494634.png";
    $ten="https://cdn-icons-png.flaticon.com/128/9494/9494570.png";
  switch($index){
      case 1: 
        echo $unu;
        break;
      case 2:
        echo $two;
        break;
      case 3:
        echo $three;
        break;
      case 4:
        echo $four;
        break;
      case 5:
        echo $five;
        break;
      case 6:
        echo $six;
        break;
      case 7:
        echo $seven;
        break;
      case 8:
        echo $eight;
        break;
      case 9:
        echo $nine;
        break;
      case 10:
        echo $ten;
        break;
    }
}
function extragePuterePHP($descriere) {
    preg_match('/(\d+)HP/', $descriere, $putere);
    return isset($putere[1]) ? $putere[1] : 'N/A';
}
?>
