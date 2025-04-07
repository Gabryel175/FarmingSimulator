
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
    var_dump($_POST);

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
        } else {
          $nume_fisier = 'json-files/joburi.json';
          $continut_fisier = file_get_contents($nume_fisier);
          $joburi = json_decode($continut_fisier, true);
          if($joburi!=NULL){
            foreach($joburi as $job){
              if($job==$denumire_job_de_actualizat){
                  $cerinte=explode(",",$job['cerinte']);
                  
              }
            }
          }
            
        }
    } else {
        echo "<script>alert('Utilizatorul nu există!');</script>";
    }


    $stmt->close();
    // Redirecționează către o altă pagină
    echo "<script>window.location.href = 'primarie.php';</script>";
    exit();
} else {
    echo "Nu s-a trimis nicio dată prin formular!";
}
$conn->close();
?>

