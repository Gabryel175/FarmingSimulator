<?php
session_start();
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

// Verifică dacă sunt setate datele POST
if (isset($_POST['id_destinatar'], $_POST['suma_transfer'])) {
    // Preia datele POST
    $id_destinatar = $_POST['id_destinatar'];
    $suma_transfer = $_POST['suma_transfer'];
    $username = $_SESSION['username'];

    // Calculare comision și suma modificată pentru expeditor
    $suma_modificare_pentru_expeditor = $suma_transfer;
    if ($suma_transfer > 20000) {
        $diferenta = $suma_transfer - 20000;
        $comision = 0.05 * $diferenta;
        $suma_modificare_pentru_expeditor += $comision;
    }

    // Începe tranzacția
    $conn->begin_transaction();

    try {
        // Actualizează balanțele utilizatorilor în baza de date
        $query_update_sender_balance = "UPDATE users SET balanta = balanta - ? WHERE username = ?";
        $stmt_update_sender_balance = $conn->prepare($query_update_sender_balance);
        $stmt_update_sender_balance->bind_param("ds", $suma_modificare_pentru_expeditor, $username);
        $stmt_update_sender_balance->execute();

        $query_update_receiver_balance = "UPDATE users SET balanta = balanta + ? WHERE id = ?";
        $stmt_update_receiver_balance = $conn->prepare($query_update_receiver_balance);
        $stmt_update_receiver_balance->bind_param("di", $suma_transfer, $id_destinatar);
        $stmt_update_receiver_balance->execute();

        // Inserare tranzacție pentru expeditor
        $insert_transaction_query_sender = "INSERT INTO tranzactii (nume, data_tranzactie, suma, motiv) VALUES (?, NOW(), ?, 'Transfer')";
        $stmt_insert_transaction_sender = $conn->prepare($insert_transaction_query_sender);
        $negative_suma_modificare = -$suma_modificare_pentru_expeditor;
        $stmt_insert_transaction_sender->bind_param("sd", $username, $negative_suma_modificare);
        if (!$stmt_insert_transaction_sender->execute()) {
            throw new Exception("Eroare la inserarea tranzacției expeditor: " . $stmt_insert_transaction_sender->error);
        }

        // Obține numele destinatarului
        $sql = "SELECT username FROM users WHERE id = ?";
        $stmt_get_receiver_name = $conn->prepare($sql);
        $stmt_get_receiver_name->bind_param("i", $id_destinatar);
        $stmt_get_receiver_name->execute();
        $result = $stmt_get_receiver_name->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $nume_destinatar = $row['username'];
        } else {
            throw new Exception("Nu s-au găsit rezultate pentru destinatar.");
        }

        // Inserare tranzacție pentru destinatar
        $insert_transaction_query_receiver = "INSERT INTO tranzactii (nume, data_tranzactie, suma, motiv) VALUES (?, NOW(), ?, 'Transfer')";
        $stmt_insert_transaction_receiver = $conn->prepare($insert_transaction_query_receiver);
        $stmt_insert_transaction_receiver->bind_param("sd", $nume_destinatar, $suma_transfer);
        if (!$stmt_insert_transaction_receiver->execute()) {
            throw new Exception("Eroare la inserarea tranzacției destinatar: " . $stmt_insert_transaction_receiver->error);
        }

        // Confirmă tranzacția
        $conn->commit();

        // Închide declarațiile preparate
        $stmt_update_sender_balance->close();
        $stmt_update_receiver_balance->close();
        $stmt_insert_transaction_sender->close();
        $stmt_insert_transaction_receiver->close();
        $stmt_get_receiver_name->close();
        $conn->close();

        echo 'Felicitări! Tranzacția a fost efectuată cu succes!';
        echo '<br>EXPEDITOR: <br>' . $username;
        echo '<br>DESTINATAR: <br>' . $nume_destinatar;
        echo '<br>SUMA TRANSFERATĂ: <br>' . $suma_transfer;
        echo '<br><p style="color: red;">Faceți o captură de ecran pentru a avea dovada plății.</p>';

    } catch (Exception $e) {
        // Anulează tranzacția în caz de eroare
        $conn->rollback();
        echo "Eroare: " . $e->getMessage();
    }

} else {
    // Dacă datele POST lipsesc, afișează un mesaj de eroare sau redirectează către o altă pagină
    echo "Eroare: Datele POST lipsesc!";
    // Sau
    // header("Location: pagina_de_eroare.php");
    // exit();
}
?>
