


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
// ID vom buch wird genommen auf das was drazf gedrückt wurde
$book_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($book_id > 0) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['delete'])) {
            // löscht das buch aus der db
            $sql = "DELETE FROM buecher WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute([$book_id])) {
                header('Location: change_successful.php');
                exit;
            } else {
                header('Location: change_failed.php');
                exit;
            }
        } else {
            // alles was geändert werden kann
            $kurztitle = $_POST['kurztitle'];
            $autor = $_POST['autor'];
            $kategorie = $_POST['kategorie'];
            $nummer = $_POST['nummer'];
            $zustand = $_POST['zustand'];

            $sql = "UPDATE buecher SET kurztitle = ?, autor = ?, kategorie = ?, nummer = ?, zustand = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute([$kurztitle, $autor, $kategorie, $nummer, $zustand, $book_id])) {
                header('Location: change_successful.php');
                exit;
            } else {
                header('Location: change_failed.php');
                exit;
            }
        }
    } else {
        // die daten vom buch werden im voraus ausgefüllt so dass man die vorherigen daten sieht .
        $sql = "SELECT kurztitle, autor, kategorie, nummer, zustand FROM buecher WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$book_id]);
        $book = $stmt->fetch();

        if ($book) {
            $kurztitle = $book['kurztitle'];
            $autor = $book['autor'];
            $kategorie = $book['kategorie'];
            $nummer = $book['nummer'];
            $zustand = $book['zustand'];
        } else {
            echo "Book not found";
            exit;
        }
    }
} else {
    echo "Invalid book ID";
    exit;
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Edit Book</title>
    <link rel="stylesheet" href="styles.css">
    <style>
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
    <?php include 'header.php'; ?>

    <div class="box">
        <h1>Edit Book</h1>
        <form method="post" action="">
            <label for="kurztitle">Book Name:</label><br>
            <input type="text" id="kurztitle" name="kurztitle" value="<?php echo htmlspecialchars($kurztitle); ?>"><br>
            <label for="autor">Author:</label><br>
            <input type="text" id="autor" name="autor" value="<?php echo htmlspecialchars($autor); ?>"><br>
            <label for="kategorie">Genre:</label><br>
            <input type="text" id="kategorie" name="kategorie" value="<?php echo htmlspecialchars($kategorie); ?>"><br>
            <label for="nummer">Number:</label><br>
            <input type="text" id="nummer" name="nummer" value="<?php echo htmlspecialchars($nummer); ?>"><br>
            <label for="zustand">Condition:</label><br>
            <input type="text" id="zustand" name="zustand" value="<?php echo htmlspecialchars($zustand); ?>"><br><br>
            <input type="submit" value="Update">
        </form>
        <form method="post" action="">
            <input type="submit" name="delete" value="Delete Book" style="background-color: red; color: white;">
        </form>
    </div>
</main>
<?php include 'footer.php'; ?>
</body>
</html>
