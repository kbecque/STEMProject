<?php
session_start(); // Sessie starten

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php"); // Als niet ingelogd, terug naar login
    exit;
}

// Verbind met de database en haal de werknemersgegevens op
$host = "localhost";
$dbnaam = "bedrijf";
$gebruikersnaam = "root";
$wachtwoord = "";

// Verbinding maken met MySQL
$conn = new mysqli($host, $gebruikersnaam, $wachtwoord, $dbnaam);

if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error);
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Werknemers</title>
    <script>
        // JavaScript event wanneer het tabblad of de browser wordt gesloten
        window.onunload = function() {
            // Maak een asynchrone request naar logout.php
            navigator.sendBeacon('logout.php');
        };
    </script>
</head>
<body>

<h1>Overzicht van Werknemers</h1>

<?php
// Query om alle werknemers op te halen
$sql = "SELECT id, naam, functie, salaris FROM werknemers";
$result = $conn->query($sql);

// HTML output om de tabel te tonen
if ($result->num_rows > 0) {
    echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Naam</th>
                <th>Functie</th>
                <th>Salaris</th>
            </tr>";

    // Gegevens weergeven in tabelrijen
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["id"] . "</td>
                <td>" . $row["naam"] . "</td>
                <td>" . $row["functie"] . "</td>
                <td>" . $row["salaris"] . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "Geen resultaten gevonden.";
}
// Sluit de databaseverbinding
$conn->close();
?>

<br><br>
<!-- Logout knop -->
<form action="logout.php" method="post">
    <input type="submit" value="Uitloggen">
</form>


</body>
</html>
