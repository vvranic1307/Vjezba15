<?php
// Povezivanje s bazom podataka
$host = "localhost";
$username = "root"; // Promijenite ako koristite drugi korisnički račun
$password = ""; // Promijenite ako koristite lozinku
$database = "vjezba14";

// Spajanje na bazu
$conn = new mysqli($host, $username, $password, $database);

// Provjera povezivanja
if ($conn->connect_error) {
    die("Pogreška pri povezivanju: " . $conn->connect_error);
}

$rezultati = [];
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $pretraga = isset($_POST['pretraga']) ? $conn->real_escape_string($_POST['pretraga']) : '';

    // SQL upit za pretraživanje
    $sql = "SELECT * FROM users WHERE name LIKE '%$pretraga%' OR lastname LIKE '%$pretraga%'";
    $result = $conn->query($sql);

    // Provjera rezultata
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $rezultati[] = $row;
        }
    } else {
        $poruka = "Nema rezultata za vašu pretragu.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tražilica korisnika</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f9;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
        }
        form {
            margin-bottom: 20px;
        }
        input, button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            background-color: #5cb85c;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #4cae4c;
        }
        .result {
            margin-top: 20px;
            padding: 15px;
            background-color: #dff0d8;
            color: #3c763d;
            border-radius: 5px;
        }
        .no-results {
            margin-top: 20px;
            padding: 15px;
            background-color: #f2dede;
            color: #a94442;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Tražilica korisnika</h1>
        <form method="post" action="">
            <input type="text" name="pretraga" placeholder="Unesite ime ili prezime za pretragu..." required>
            <button type="submit">Pretraži</button>
        </form>

        <?php if (!empty($rezultati)): ?>
            <div class="result">
                <h2>Rezultati pretrage:</h2>
                <ul>
                    <?php foreach ($rezultati as $rezultat): ?>
                        <li>
                            <strong>Ime:</strong> <?php echo htmlspecialchars($rezultat['name']); ?><br>
                            <strong>Prezime:</strong> <?php echo htmlspecialchars($rezultat['lastname']); ?><br>
                            <strong>Korisničko ime:</strong> <?php echo htmlspecialchars($rezultat['username']); ?><br>
                            <strong>Zemlja:</strong> <?php echo htmlspecialchars($rezultat['country_code']); ?><br>
                            <strong>O korisniku:</strong> <?php echo htmlspecialchars($rezultat['about']); ?>
                        </li>
                        <hr>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php elseif (!empty($poruka)): ?>
            <div class="no-results">
                <?php echo htmlspecialchars($poruka); ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
