<?php
include 'config.php'; 

$defaultCover = 'Bilder/cover.jpg'; 

// die ID wird genommen für das buch
$bookId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($bookId > 0) {
    // Bereitet die SQL-Abfrage vor, um Buchdetails zu sehen
    $stmt = $pdo->prepare("SELECT * FROM buecher WHERE id = :id");
    $stmt->execute([':id' => $bookId]);
    $book = $stmt->fetch();

    if ($book) {
        $full_description = $book['title']; 
        $words = explode(' ', $full_description);
        if (count($words) > 50) {
            $description = implode(' ', array_slice($words, 0, 50)) . '...';
        } else {
            $description = $full_description;
        }
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($book['title']) ?></title>
    <link rel="stylesheet" href="styles.css">
    <style>
        main {
            padding-top: 8em;

        }
    </style>
</head>
<body>
<main>

    <?php include 'header.php'; ?>




    <div class="container">

    
            <div class="container">



                    <div class="book-card">
                        <div class="Images2">
                            <div class="book-cover">
                            <img src="<?= $defaultCover ?>" alt="<?= htmlspecialchars($book['title']) ?>"  />
  

                            </div>
                            <div class="star-rating">
  &#9733; &#9733; &#9733; &#9733; &#9733;
</div>
                            </div>

                            < class="Infos2">
                                <div class="title-container">
                                    <h1 class="book-title"><?= htmlspecialchars($book['kurztitle']) ?></h1>
                            </div>
                            <p class="book-author">Autor: <span class="author-name"><?= htmlspecialchars($book['autor']) ?></span></p>
                            <div class="description-wrapper">
    <p class="book-description"><?= $description ?></p>
</div>
                
    </div>
    

    <div class="genre-wrapper">
    <span class="genre-label">Genre</span>
    <span class="genre-name"><?= htmlspecialchars($book['kategorie']) ?></span>
</div>
</div>
</main>
</body>

<?php include 'footer.php'; ?>

</html>
<?php
    } else {
        // Buch nicht gefunden
        echo "Buch nicht gefunden.";
    }
} else {
    // Ungültige ID
    echo "Ungültige Anfrage.";
}
?>
