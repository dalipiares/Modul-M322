<!DOCTYPE html>
<html lang="de">
<head>
    <title>Meine Seite</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
<header>
    <h1>Willkommen auf meiner Seite</h1>
    <nav>
        <ul>
            <li><a href="#">Startseite</a></li>
            <li><a href="#">Über mich</a></li>
            <li><a href="#">Projekte</a></li>
            <li><a href="#">Kontakt</a></li>
        </ul>
    </nav>
</header>

<main>
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "books";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $items_per_page = 12;
    $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
    $offset = ($current_page - 1) * $items_per_page;

    // Get the total number of books
    $sql_total = "SELECT COUNT(*) as total FROM buecher";
    $result_total = $conn->query($sql_total);
    $row_total = $result_total->fetch_assoc();
    $total_books = $row_total['total'];
ss
    // Calculate the total number of pages
    $total_pages = ceil($total_books / $items_per_page);

    $sql = "SELECT id, katalog, nummer, kurztitle, kategorie, verkauft, kaufer, autor, title, sprache, foto, verfasser, zustand FROM buecher LIMIT $items_per_page OFFSET $offset";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            echo '<div class="book-item">';
            echo '<img src="' . $row["foto"] . '" alt="' . $row["title"] . '">';
            echo '<h2>' . $row["title"] . '</h2>';
            echo '<p>' . $row["kurztitle"] . '</p>';
            echo '<p>Autor: ' . $row["autor"] . '</p>';
            echo '<p>Kategorie: ' . $row["kategorie"] . '</p>';
            echo '<p>Sprache: ' . $row["sprache"] . '</p>';
            echo '</div>';
        }
    } else {
        echo "0 results";
    }

    echo '<div class="pagination">';

    // Calculate the start and end page numbers
    $start_page = max(1, $current_page - 5);
    $end_page = min($total_pages, $current_page + 5);

    // Add a "previous" link
    if ($current_page > 1) {
        $prev_page = max(1, $current_page - 15);
        echo "<a class=\"page-arrow\" href=\"$_SERVER[PHP_SELF]?page=$prev_page\"><<</a>";
    }

    // Display page numbers
    for ($i = $start_page; $i <= $end_page; $i++) {
        if ($i == $current_page) {
            echo "<span class=\"page-number current-page\">$i</span>";
        } else {
            echo "<a class=\"page-number\" href=\"$_SERVER[PHP_SELF]?page=$i\">$i</a>";
        }
    }

    // Add a "next" link
    if ($current_page < $total_pages) {
        $next_page = min($total_pages, $current_page + 15);
        echo "<a class=\"page-arrow\" href=\"$_SERVER[PHP_SELF]?page=$next_page\">>></a>";
    }

    echo '</div>';

    $conn->close();
    ?>
</main>

<footer>
    <div class="footer-content">
        <p>© 2024 Meine Seite. Alle Rechte vorbehalten.</p>
        <ul class="footer-links">
            <li><a href="#">Impressum</a></li>
            <li><a href="#">Datenschutz</a></li>
            <li><a href="#">Kontakt</a></li>
        </ul>
    </div>
</footer>
</body>
</html>