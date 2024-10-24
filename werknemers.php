<?php
session_start(); // Sessie starten

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php"); // Als niet ingelogd, terug naar login
    exit;
}

// Verbind met de database en haal de werknemers op
$host = "localhost";
$dbnaam = "bedrijf";
$gebruikersnaam = "root";
$wachtwoord = "";

// Verbinding maken met MySQL
$conn = new mysqli($host, $gebruikersnaam, $wachtwoord, $dbnaam);

if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error);
}

// Handle form submission for adding new employee
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_employee'])) {
    $naam = $_POST['naam'];
    $functie = $_POST['functie'];
    $salaris = $_POST['salaris'];

    // Query om de nieuwe werknemer toe te voegen
    $sql = "INSERT INTO werknemers (naam, functie, salaris) VALUES ('$naam', '$functie', '$salaris')";
    if ($conn->query($sql) === TRUE) {
        echo "Nieuwe werknemer succesvol toegevoegd.";
    } else {
        echo "Fout bij het toevoegen van werknemer: " . $conn->error;
    }
}

// Handle form submission for deleting employees
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_employees'])) {
    if (isset($_POST['delete_ids'])) {
        $delete_ids = $_POST['delete_ids'];
        foreach ($delete_ids as $id) {
            $sql = "DELETE FROM werknemers WHERE id = $id";
            $conn->query($sql);
        }
        echo "Geselecteerde werknemers succesvol verwijderd.";
    } else {
        echo "Geen werknemers geselecteerd om te verwijderen.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="opmaak.css">
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

<form method="post" action="werknemers.php">
    <?php
    // Query om alle werknemers op te halen
    $sql = "SELECT id, naam, functie, salaris FROM werknemers";
    $result = $conn->query($sql);

    // HTML output om de tabel te tonen
    if ($result->num_rows > 0) {
        echo "<table border='1'>
                <tr>
                    <th>Selecteer</th>
                    <th>ID</th>
                    <th>Naam</th>
                    <th>Functie</th>
                    <th>Salaris</th>
                </tr>";

        // Gegevens weergeven in tabelrijen
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td><input type='checkbox' name='delete_ids[]' value='" . $row["id"] . "'></td>
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
    ?>
    <br>
    <input type="submit" name="delete_employees" value="Verwijder Geselecteerde Werknemers">
</form>

<br><br>

<h3>Nieuwe Werknemer Toevoegen</h3>
<form method="post" action="werknemers.php">
    <label>Naam:</label>
    <input type="text" name="naam" required><br><br>
    <label>Functie:</label>
    <input type="text" name="functie" required><br><br>
    <label>Salaris:</label>
    <input type="number" name="salaris" required><br><br>
    <input type="submit" name="add_employee" value="Toevoegen">
</form>

<!-- Logout knop -->
<form action="logout.php" method="post">
    <input type="submit" value="Uitloggen">
</form>

</body>
</html>

<?php
// Sluit de databaseverbinding
$conn->close();
?>