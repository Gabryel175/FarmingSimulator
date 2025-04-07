<?php
// Verifică dacă s-a apăsat butonul "Achită rata"
if (isset($_POST['achita_rata'])) {
    // Extrage datele din formular
    $username = $_POST['username']; // Numele utilizatorului
    $suma_imprumut = $_POST['suma_imprumut']; // Suma împrumutată
    $numar_rate = $_POST['numar_rate']; // Numărul total de rate
    $dobanda = $_POST['dobanda']; // Dobânda asociată împrumutului
    $rate_platite = $_POST['rate_platite']; // Numărul de rate deja achitate
    
    // Calculează suma de achitat
    $suma_de_achitat = $suma_imprumut / $numar_rate + $suma_imprumut * $dobanda;
    
    // Conectare la baza de date
    $conn = new mysqli("localhost", "root", "root", "roleplay");

    if ($conn->connect_error) {
        die("Conexiunea la baza de date a eșuat: " . $conn->connect_error);
    }

    // Actualizează balanța utilizatorului în tabelul 'users'
    $query_update_balance = "UPDATE users SET balanta = balanta - ? WHERE username = ?";
    $stmt_update_balance = $conn->prepare($query_update_balance);
    $stmt_update_balance->bind_param("ds", $suma_de_achitat, $username);
    $stmt_update_balance->execute();
    
    // Incrementarea campului 'rate_platite' în tabelul 'imprumuturi'
    $query_increment_rate = "UPDATE imprumuturi SET rate_platite = rate_platite + 1 WHERE nume_cetatean = ?";
    $stmt_increment_rate = $conn->prepare($query_increment_rate);
    $stmt_increment_rate->bind_param("s", $username);
    $stmt_increment_rate->execute();
    
    // Verifică dacă toate ratele au fost plătite
    if ($rate_platite + 1 == $numar_rate) {
        // Șterge înregistrarea din tabelul 'imprumuturi' dacă toate ratele au fost plătite
        $query_delete_loan = "DELETE FROM imprumuturi WHERE nume_cetatean = ?";
        $stmt_delete_loan = $conn->prepare($query_delete_loan);
        $stmt_delete_loan->bind_param("s", $username);
        $stmt_delete_loan->execute();
        
        // Afișează un mesaj de felicitare
        echo "Felicitări, ați terminat de plătit rata!";
    } else {
        // Afișează un mesaj de confirmare a plății
        echo "Rata a fost achitată cu succes!";
    }
    
    // Inserare tranzacție (suma este negativă)
    $insert_transaction_query = "INSERT INTO tranzactii (nume, data_tranzactie, suma, motiv) VALUES (?, NOW(), ?, 'Rata')";
    $stmt_insert_transaction = $conn->prepare($insert_transaction_query);
    $negative_suma_de_achitat = -$suma_de_achitat; // Face suma negativă
    $stmt_insert_transaction->bind_param("sd", $username, $negative_suma_de_achitat);
    if ($stmt_insert_transaction->execute()) {
        // Tranzacția a fost înregistrată cu succes
    } else {
        echo "Eroare la inserarea tranzacției: " . $stmt_insert_transaction->error;
    }

    // Închide conexiunea la baza de date
    $stmt_update_balance->close();
    $stmt_increment_rate->close();
    $stmt_delete_loan->close();
    $stmt_insert_transaction->close();
    $conn->close();
} else {
    // Redirectează către o altă pagină sau afișează un mesaj de eroare
    echo "Eroare la procesare!";
}
?>
