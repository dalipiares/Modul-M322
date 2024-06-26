<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Edit Books</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        table {
            border-collapse: collapse;
            margin: auto; 
        }

        table td, table th {
            border: 1px solid #000;
            padding: 10px;
        }

        main {
            display: flex; 
            flex-direction: column;
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            padding: 20px; 
            width: 100%;
        }

        .box {
            border: 1px solid #000; 
            padding: 20px; 
            box-sizing: border-box; 
            background-color: #BBC5FF;
            margin: 1em;
            font-family: 'Segoe UI', sans-serif;
            border-radius: 15px; 
        }

    </style>
</head>
<body>
<main>

<?php
session_start();
require 'config.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
$stmt = $pdo->prepare("SELECT admin FROM users WHERE username = :username");
$stmt->execute([':username' => $username]);
$user = $stmt->fetch();

if (!$user || !$user['admin']) {
    header("Location: nur_fuer_admins.php");
    exit;
}

include 'header.php';

$items_per_page = 8; // damit nur 8 bücher angeziegt werden 

$search_term = isset($_GET['search']) ? $_GET['search'] : '';
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// damit es limitiert wird
$start_number = ($current_page - 1) * $items_per_page;

// querys für mysql
$stmt = $pdo->prepare("SELECT * FROM buecher WHERE kurztitle LIKE :search_term LIMIT :start_number, :items_per_page");
$stmt->bindValue(':search_term', "%$search_term%", PDO::PARAM_STR);
$stmt->bindValue(':start_number', (int)$start_number, PDO::PARAM_INT);
$stmt->bindValue(':items_per_page', (int)$items_per_page, PDO::PARAM_INT);
$stmt->execute();
$books = $stmt->fetchAll();

// zählz anzahl ergebnisse
$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM buecher WHERE kurztitle LIKE :search_term");
$stmt->bindValue(':search_term', "%$search_term%", PDO::PARAM_STR);
$stmt->execute();
$total_books = $stmt->fetch()['total'];

// berechnung für die seite anzahl
$total_pages = ceil($total_books / $items_per_page);
?>

    <div class="controls">
        <form action="edit_books.php" method="get">
            <input type="text" name="search" placeholder="Search by book name">
            <input type="submit" value="Search">
            <a href="buch_erstellen.php">  Create New Book</a>
        </form>

    </div>


    <div class="box">

        <table>
            <tr>
                <th></th>
                <th>Book Name</th>
                <th>Author</th>
                <th>Genre</th>
                <th>Number</th>
                <th>Condition</th>
                <th>Edit</th>
            </tr>
            <?php foreach ($books as $book): ?>
                <tr>
                    <td><img src="Bilder/cover.jpg" width="40em"></td>
                    <td><?= htmlspecialchars($book['kurztitle']) ?></td>
                    <td><?= htmlspecialchars($book['autor']) ?></td>
                    <td><?= htmlspecialchars($book['kategorie']) ?></td>
                    <td><?= htmlspecialchars($book['nummer']) ?></td>
                    <td><?= htmlspecialchars($book['zustand']) ?></td>
                    <td><a href="edit_book.php?id=<?= htmlspecialchars($book['id']) ?>">Edit</a></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <?php
    echo '<div class="pagination">';

    // start page zahl
    $start_page = max(1, $current_page - 5);
    $end_page = min($total_pages, $current_page + 5);

    // damit man zurück kann
    if ($current_page > 1) {
        $prev_page = max(1, $current_page - 15);
        echo "<a class=\"page-arrow\" href=\"$_SERVER[PHP_SELF]?search=" . htmlspecialchars($search_term) . "&page=$prev_page\"><<</a>";
    }

    // damit zahlen automatisch erstellt werden
    for ($i = $start_page; $i <= $end_page; $i++) {
        if ($i == $current_page) {
            echo "<span class=\"page-number current-page\">$i</span>";
        } else {
            echo "<a class=\"page-number\" href=\"$_SERVER[PHP_SELF]?search=" . htmlspecialchars($search_term) . "&page=$i\">$i</a>";
        }
    }

    // damit man vorwärts gehen kann
    if ($current_page < $total_pages) {
        $next_page = min($total_pages, $current_page + 15);
        echo "<a class=\"page-arrow\" href=\"$_SERVER[PHP_SELF]?search=" . htmlspecialchars($search_term) . "&page=$next_page\">>></a>";
    }

    echo '</div>';
    ?>

</main>

<?php include 'footer.php'; ?>

</body>
</html>
