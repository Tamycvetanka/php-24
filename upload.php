<?php
// Povezivanje sa bazom
$host = "localhost";
$user = "root";
$pass = "";
$db   = "moja_baza";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Greška pri povezivanju sa bazom: " . $conn->connect_error);
}

// Proveri da li je fajl poslat
if (!isset($_FILES['profileImage']) || $_FILES['profileImage']['error'] !== UPLOAD_ERR_OK) {
    die("Niste prosledili profilnu sliku.");
}

// Dozvoljeni formati
$allowedExtensions = ["image/jpg", "image/jpeg", "image/png", "image/gif"];
$imageType = $_FILES['profileImage']['type'];

if (!in_array($imageType, $allowedExtensions)) {
    die("Format slike nije dobar! Dozvoljeni formati: " . implode(", ", $allowedExtensions));
}

// Folder za upload
$uploadDir = "uploads/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$imageName = basename($_FILES['profileImage']['name']);
$targetPath = $uploadDir . $imageName;

// Pokušaj snimanja fajla
if (move_uploaded_file($_FILES['profileImage']['tmp_name'], $targetPath)) {
    echo "✅ Slika uspešno uploadovana!<br>";

    // Upis u bazu – OBRATI PAŽNJU: nema id kolone!
    $stmt = $conn->prepare("INSERT INTO slike (ime_fajla, putanja) VALUES (?, ?)");
    $stmt->bind_param("ss", $imageName, $targetPath);

    if ($stmt->execute()) {
        echo "✅ Slika je upisana u bazu!";
    } else {
        echo "❌ Greška pri upisu u bazu: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "❌ Greška pri uploadu fajla.";
}

$conn->close();
?>
