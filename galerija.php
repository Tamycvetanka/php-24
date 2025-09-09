<?php
// Povezivanje sa bazom
$host = "localhost";
$user = "root";      // promeni ako imaš drugog usera
$pass = "";          // lozinka (ako postoji)
$db   = "moja_baza"; // tvoja baza

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Greška pri povezivanju sa bazom: " . $conn->connect_error);
}

// Uzimamo sve slike iz baze
$sql = "SELECT * FROM slike ORDER BY datum_upload DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title>Galerija slika</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }
        h1 {
            text-align: center;
        }
        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 30px;
        }
        .gallery img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            transition: transform 0.3s;
        }
        .gallery img:hover {
            transform: scale(1.05);
        }
        .card {
            background: #fff;
            border-radius: 8px;
            padding: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            text-align: center;
        }
        .card p {
            margin: 5px 0;
            font-size: 14px;
            color: #555;
        }
    </style>
</head>
<body>
<h1>Galerija slika</h1>
<div class="gallery">
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='card'>";
            echo "<img src='" . htmlspecialchars($row['putanja']) . "' alt='" . htmlspecialchars($row['ime_fajla']) . "'>";
            echo "<p>" . htmlspecialchars($row['ime_fajla']) . "</p>";
            echo "<p><small>Upload: " . $row['datum_upload'] . "</small></p>";
            echo "</div>";
        }
    } else {
        echo "<p>Nema slika u bazi.</p>";
    }
    ?>
</div>
</body>
</html>
<?php $conn->close(); ?>

