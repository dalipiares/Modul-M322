<?php
session_start();
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $katalog = $_POST['katalog'];
    $kurztitle = $_POST['kurztitle'];
    $autor = $_POST['autor'];
    $kategorie = $_POST['kategorie'];
    $zustand = $_POST['zustand'];

    // prepare die sql query damit es eingsetzt werden kann

    $stmt = $pdo->prepare("INSERT INTO buecher (katalog, kurztitle, autor, kategorie, zustand) VALUES (:katalog, :kurztitle, :autor, :kategorie, :zustand)");
    $stmt->execute([':katalog' => $katalog, ':kurztitle' => $kurztitle, ':autor' => $autor, ':kategorie' => $kategorie, ':zustand' => $zustand]);
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Buch erstellen</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        #buch_create{
            width: 10em;
            font-optical-sizing: auto;
        }
        body .foot{
            position: absolute;
            bottom: 0;
            align-self: end;
        }
    </style>
</head>
<body>
<main>
<?php include 'header.php'; ?>
    <div class="box">
        <form action="buch_erstellen.php" method="post">
            <label>
                Katalog:
                <input type="text" name="katalog" required>
            </label>
            <label>
                Kurztitle:
                <input type="text" name="kurztitle" required>
            </label>
            <label>
                Autor:
                <input type="text" name="autor" required>
            </label>
            <label>
                Kategorie:
                <input type="text" name="kategorie" required>
            </label>
            <label>
                Zustand:
                <input type="text" name="zustand" required>
            </label>
            <input id="buch_create" type="submit" value="Buch erstellen">
        </form>
    </div>
</main>



</body>
<?php include 'footer.php'; ?>

</html>