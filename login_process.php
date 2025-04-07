<?php
session_start();

include_once 'functii.php';

if (is_user_logged_in()) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $servername = "localhost";
    $db_username = "root";
    $db_password = "root";
    $db_name = "roleplay";

    $conn = new mysqli($servername, $db_username, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Conexiunea la baza de date a eșuat: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $db_username, $db_password_hash);
        $stmt->fetch();

        if (password_verify($password, $db_password_hash)) {
            $_SESSION["logged_in"] = true;
            $_SESSION["username"] = $db_username;
            $_SESSION["user_id"] = $user_id;

            header("Location: index.php");
            exit;
        } else {
            echo "Parola este incorectă!";
        }
    } else {
        echo "Utilizatorul nu există!";
    }

    $stmt->close();
    $conn->close();
}
?>
