<?php 
session_start();
$servername = "localhost";
$username = "root";
$password = "root";
$database = "roleplay";
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Conexiunea la baza de date a eșuat: " . $conn->connect_error);
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['denumire_job'])) {
    // Încarcă conținutul JSON din fișier
    $nume_fisier = 'json-files/joburi.json';
    $continut_fisier = file_get_contents($nume_fisier);
    $joburi = json_decode($continut_fisier, true);

    $denumire_job_de_actualizat = $_POST['denumire_job'];
    $utilizator = $_SESSION['username'];
    $stmt = $conn->prepare("SELECT job FROM users WHERE username = ?");
    $stmt->bind_param("s", $utilizator);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($job);
        $stmt->fetch();

        if ($job == 1) {
            echo "<script>alert('Dumneavoastră dețineți deja un job!');</script>";
            echo "<script>window.location.href = 'primarie.php';</script>";
            exit();
        } else {
            // Interogare pentru a obține cerințele utilizatorului din tabelul `atestat`
            $stmt_atestat = $conn->prepare("SELECT permis_cat_B, permis_cat_C, permis_cat_E, atestat_gabaritic, atestat_ADR, examen_padurar FROM atestate WHERE nume_cetatean = ?");
            $stmt_atestat->bind_param("s", $utilizator);
            $stmt_atestat->execute();
            $stmt_atestat->store_result();

            if ($stmt_atestat->num_rows > 0) {
                $stmt_atestat->bind_result($permis_cat_B, $permis_cat_C, $permis_cat_E, $atestat_gabaritic, $atestat_ADR, $examen_padurar);
                $stmt_atestat->fetch();

                foreach ($joburi as $job) {
                    if ($job['denumire_job'] === $denumire_job_de_actualizat) {
                        $cerinte = explode(",", $job['cerinte']);

                        $cerinte_indeplinite = true;
                        foreach ($cerinte as $cerinta) {
                            // Verificare cerințe
                            switch ($cerinta) {
                                case 'permis_cat_B':
                                    if ($permis_cat_B != 1 && is_null($permis_cat_B)) {
                                        $cerinte_indeplinite = false;
                                    }
                                    break;
                                case 'permis_cat_C':
                                    if ($permis_cat_C != 1 && is_null($permis_cat_C)) {
                                        $cerinte_indeplinite = false;
                                    }
                                    break;
                                case 'permis_cat_E':
                                    if ($permis_cat_E != 1 && is_null($permis_cat_E)) {
                                        $cerinte_indeplinite = false;
                                    }
                                    break;
                                case 'atestat_gabaritic':
                                    if ($atestat_gabaritic != 1 && is_null($atestat_gabaritic)) {
                                        $cerinte_indeplinite = false;
                                    }
                                    break;
                                case 'atestat_ADR':
                                    if ($atestat_ADR != 1 && is_null($atestat_ADR)) {
                                        $cerinte_indeplinite = false;
                                    }
                                    break;
                                case 'examen_padurar':
                                    if ($examen_padurar != 1 && is_null($examen_padurar)) {
                                        $cerinte_indeplinite = false;
                                    }
                                    break;
                                default:
                                    // Cerință necunoscută
                                    break;
                            }
                        }

                        if ($cerinte_indeplinite) {
                            $stmt = $conn->prepare("UPDATE users SET nume_job = ?, job = 1 WHERE username = ?");
                            $stmt->bind_param("ss", $denumire_job_de_actualizat, $utilizator);
                            $stmt->execute();
                            echo "<script>alert('Ați fost acceptat pentru acest job! Vă puteți începe activitatea.');</script>";
                            // Redirecționează către o altă pagină
                            echo "<script>window.location.href = 'primarie.php';</script>";
                            exit();
                        } else {
                            echo "<script>alert('Nu îndepliniți toate cerințele pentru acest job!');</script>";
                            echo "<script>window.location.href = 'primarie.php';</script>";
                            exit();
                        }
                    }
                }
                echo "<script>alert('Jobul nu a fost găsit în baza de date!');</script>";
            } else {
                echo "<script>alert('Nu există date despre cerințele utilizatorului în baza de date!');</script>";
                echo "<script>window.location.href = 'primarie.php';</script>";
                exit();
            }
        }
    } else {
        echo "<script>alert('Utilizatorul nu există!');</script>";
    }

    $stmt->close();
} else {
    echo "Nu s-a trimis nicio dată prin formular!";
}
$conn->close();
?>
