<?php

// Conectare la baza de date
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

// Verificare dacă formularul a fost trimis
if (isset($_POST['submit'])) {
    // Prelucrare date din formular
    $username = $_POST['username'];
    $suma_imprumut = $_POST['suma_imprumut'];
    $numar_rate = $_POST['numar_rate'];

    // Verificare dacă utilizatorul are deja un împrumut
    $check_query = "SELECT * FROM imprumuturi WHERE nume_cetatean = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Utilizatorul are deja un împrumut.";
    } else {
        // Creare împrumut nou
        $insert_query = "INSERT INTO imprumuturi (nume_cetatean, suma_imprumutata, numar_rate, rate_platite) VALUES (?, ?, ?, '0')";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("sii", $username, $suma_imprumut, $numar_rate);
        if ($stmt->execute()) {
            echo "Împrumutul a fost înregistrat cu succes!";

            // Actualizare balanță în tabelul users
            $update_query = "UPDATE users SET balanta = balanta + ? WHERE username = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("is", $suma_imprumut, $username);
            $stmt->execute();

            // Inserare tranzacție
            $insert_transaction_query = "INSERT INTO tranzactii (nume, data_tranzactie, suma, motiv) VALUES (?, NOW(), ?, 'Împrumut')";
            $stmt = $conn->prepare($insert_transaction_query);
            
            $stmt->bind_param("sd", $username, $suma_imprumut);
            if ($stmt->execute()) {
                echo "Tranzacția a fost înregistrată cu succes!";
            } else {
                echo "Eroare la inserarea tranzacției: " . $stmt->error;
            }
        } else {
            echo "Eroare la înregistrarea împrumutului: " . $conn->error;
        }
    }
}

// Închiderea conexiunii cu baza de date
$conn->close();
?>
