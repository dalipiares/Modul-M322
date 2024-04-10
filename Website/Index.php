    <!DOCTYPE html>
    <html lang="de">
    <head>
        <title>Meine Seite</title>
        <link rel="stylesheet" type="text/css" href="styles.css">
    </head>
    <body>
    
    
    
    
    
    <?php
    
    require 'config.php';
    $servername = DB_SERVER;
    $username = DB_USERNAME;
    $password = DB_PASSWORD;
    $dbname = DB_NAME;
    
    
    // erstellt die Verbindung
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // überprüft die Verbindung
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }



    $Such_kriterien = isset($_GET['search']) ? $_GET['search'] : '';

    $sort_option = isset($_GET['sort']) ? $_GET['sort'] : '';

    $order_option = isset($_GET['order']) ? $_GET['order'] : 'asc';

    $filter_options = isset($_GET['filter']) ? $_GET['filter'] : [];

    $where_clause = "";


    $valid_sort_options = ['katalog', 'zustand', 'kurztitle', 'autor', 'kategorie'];
    if (!in_array($sort_option, $valid_sort_options)) {
        $sort_option = 'katalog';
    }

    $valid_order_options = ['asc', 'desc'];
    if (!in_array($order_option, $valid_order_options)) {
        $order_option = 'asc';
    }

    if (!empty($Such_kriterien)) {
        $where_clause = "katalog LIKE '%$Such_kriterien%' OR zustand LIKE '%$Such_kriterien%' OR kurztitle LIKE '%$Such_kriterien%' OR autor LIKE '%$Such_kriterien%' OR kategorie LIKE '%$Such_kriterien%'";
    }

    foreach ($filter_options as $option) {
        if (!empty($where_clause)) {
            $where_clause .= " OR ";
        }
        $where_clause .= "$option LIKE '$Such_kriterien%'";
    }

    if (empty($where_clause)) {
        $where_clause = "id";
    }


    $items_per_page = 12;
    $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
    $offset = ($current_page - 1) * $items_per_page;

    //rechnet die anzahl bücher aus
    $stmt_total = $conn->prepare("SELECT COUNT(*) as total FROM buecher WHERE $where_clause");
    $stmt_total->execute();
    $result_total = $stmt_total->get_result();
    $row_total = $result_total->fetch_assoc();

    // rechnet die totale seiten anzahl aus
    $total_books = $row_total['total'];
    $total_pages = ceil($total_books / $items_per_page);





    $stmt = $conn->prepare("SELECT id, katalog, nummer, kurztitle, kategorie, verkauft, kaufer, autor, title, sprache, foto, verfasser, zustand FROM buecher WHERE $where_clause ORDER BY $sort_option $order_option LIMIT ? OFFSET ?");
    $stmt->bind_param("ii", $items_per_page, $offset);
    $stmt->execute();
    $result = $stmt->get_result();


    





    ?>
    
    
    
    
    
    <main>
    
        <header>
            <nav>
                <img src="Bilder/logo.png" alt="Logo">
                <ul>
                    <li><a href="#">Kategorie</a></li>
                    <li><a href="#">Schriftsteller</a></li>
                </ul>
            </nav>
        </header>
    
        <form action="Index.php" method="get">
            <label>
                <input type="text" name="search" placeholder="Search" >
            </label>
            <label>
                <select name="sort">
                    <option value="">Sortieren nach...</option>
                    <option value="katalog">Katalog</option>
                    <option value="zustand">Zustand</option>
                    <option value="kurztitle">Name</option>
                    <option value="autor">Autor</option>
                    <option value="kategorie">Kategorie</option>
                </select>
                <select name="order">
                    <option value="asc">Aufsteigend</option>
                    <option value="desc">Absteigend</option>
                </select>
            </label>
            <label class="dropdown">
                <label class="dropbtn">Filter</label>
                <label id="auswahlmenu" class="dropdown-content">
            <!-- Hier werden die Checkboxen hinzugefügt -->

                </label>
            </label>
            <input type="submit" value="Suchen">
            <label class="found_books">
                Gefundene Bücher: <?php echo $total_books; ?>
            </label>
        </form>
    
    
        <script>
            const checkboxen = [
                {
                    value: "katalog",
                    text: "Katalog"
                },
                {
                    value: "kurztitle",
                    text: "Name"
                },
                {
                    value: "autor",
                    text: "Autor"
                },
                {
                    value: "kategorie",
                    text: "Kategorie"
                },
                {
                    value: "zustand",
                    text: "Zustand"
                }
            ];
    
            const auswahlmenu = document.getElementById("auswahlmenu");
    
            for (const checkbox of checkboxen) {
                const label = document.createElement("label");
                const input = document.createElement("input");
                input.type = "checkbox";
                input.name = "filter[]";
                input.value = checkbox.value;
                label.appendChild(input);
                label.appendChild(document.createTextNode(checkbox.text));
                auswahlmenu.appendChild(label);
            }
        </script>
    
    

    
    
    <?php

    
    
        if ($result->num_rows > 0) {
            // Bilder Reihen
            while($row = $result->fetch_assoc()) {
                echo '<div class="book-item">';
                echo '<img src="/Bilder/cover.jpg" alt="' . $row["foto"] . '" alt="' . $row["kurztitle"] . '">';
                echo '<h2>' . $row["kurztitle"] . '</h2>';
                echo '<div class="book-details">';
                echo '<p>' . $row["kurztitle"] . '</p>';
                echo '<p>Autor: ' . $row["autor"] . '</p>';
                echo '<p>Kategorie: ' . $row["kategorie"] . '</p>';
                echo '<p>Sprache: ' . $row["sprache"] . '</p>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo "0 results";
        }
    
    
    
    
    
        echo '<div class="pagination">';
    
        // Rechnet die Start und End Seiten aus
        $start_page = max(1, $current_page - 5);
        $end_page = min($total_pages, $current_page + 5);
    
    
        // fügt ein "previous" link hinzu
        if ($current_page > 1) {
            $prev_page = max(1, $current_page - 15);
            echo "<a class=\"page-arrow\" href=\"$_SERVER[PHP_SELF]?search=$Such_kriterien&sort=$sort_option&order=$order_option&page=$prev_page\"><<</a>";
        }
    
        // fügt die Seitenzahlen hinzu
        for ($i = $start_page; $i <= $end_page; $i++) {
            if ($i == $current_page) {
                echo "<span class=\"page-number current-page\">$i</span>";
            } else {
                echo "<a class=\"page-number\" href=\"$_SERVER[PHP_SELF]?search=$Such_kriterien&sort=$sort_option&order=$order_option&page=$i\">$i</a>";
            }
        }
    
    
    
        // fügt ein "next" link hinzu
        if ($current_page < $total_pages) {
            $next_page = min($total_pages, $current_page + 15);
            echo "<a class=\"page-arrow\" href=\"$_SERVER[PHP_SELF]?search=$Such_kriterien&sort=$sort_option&order=$order_option&page=$next_page\">>></a>";
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