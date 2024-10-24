
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="opmaak.css">
</head>
<body>

    <h2>Login</h2>
    <form method="post" action="login.php">
        <label>Gebruikersnaam:</label>
        <input type="text" name="gebruikersnaam" required><br><br>
        <label>Wachtwoord:</label>
        <input type="password" name="wachtwoord" required><br><br>
        <input type="submit" name="login" value="Login">
        <?php
session_start(); // Start de sessie


// Database gegevens
$host = "localhost";
$dbnaam = "bedrijf";
$gebruikersnaam = "root";
$wachtwoord = "";

// Verbinding maken met MySQL
$conn = new mysqli($host, $gebruikersnaam, $wachtwoord, $dbnaam);

if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $gebruikersnaam = $_POST['gebruikersnaam'];
    $wachtwoord = md5($_POST['wachtwoord']); // Hashed wachtwoord (MD5 in dit voorbeeld)

    // Query om de gebruiker te vinden
    $sql = "SELECT * FROM gebruikers WHERE gebruikersnaam = '$gebruikersnaam' AND wachtwoord = '$wachtwoord'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Succesvol ingelogd, zet sessievariabele
        $_SESSION['loggedin'] = true;
        $_SESSION['gebruikersnaam'] = $gebruikersnaam;

        // Redirect naar de werknemerspagina
        header("Location: werknemers.php");
    } else {
        echo "Ongeldige gebruikersnaam of wachtwoord.";
    }
}

$conn->close();
?>
    </form>
</body>
</html>