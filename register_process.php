<?php

include_once 'functii.php';

if (is_user_logged_in()) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    $servername = "localhost";
    $db_username = "root";
    $db_password = "root";
    $db_name = "roleplay";

    $conn = new mysqli($servername, $db_username, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Conexiunea la baza de date a eșuat: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Utilizatorul sau adresa de email există deja!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $insert_stmt = $conn->prepare("INSERT INTO users (username, email, password, create_datetime) VALUES (?, ?, ?, NOW())");
        $insert_stmt->bind_param("sss", $username, $email, $hashed_password);

        if ($insert_stmt->execute()) {
            echo "Utilizatorul a fost înregistrat cu succes!";

            // Inserează și în tabelul "atestate"
            $insert_atestate_stmt = $conn->prepare("INSERT INTO atestate (nume_cetatean) VALUES (?)");
            $insert_atestate_stmt->bind_param("s", $username);
            $insert_atestate_stmt->execute();
        } else {
            echo "Eroare la înregistrare: " . $insert_stmt->error;
        }
    }

    $stmt->close();
    $conn->close();
}
?>
