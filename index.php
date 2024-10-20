<!DOCTYPE html>
<html>
<head>
    <title>Werknemers</title>
</head>
<body>
    <h1>Overzicht van Werknemers</h1>

    <?php
// Database gegevens
$host = "localhost"; // Of jouw server
$dbnaam = "bedrijf";
$gebruikersnaam = "root"; // Aanpassen indien nodig
$wachtwoord = ""; // Aanpassen indien nodig

// Verbinding maken met MySQL
$conn = new mysqli($host, $gebruikersnaam, $wachtwoord, $dbnaam);

// Controleer verbinding
if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error);
}

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

</body>
</html>
