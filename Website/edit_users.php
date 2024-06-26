

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Edit Users</title>
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





$items_per_page = 8; // genau gleich wie books page ( nur mit kunden)


$search_term = isset($_GET['search']) ? $_GET['search'] : '';
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

$start_number = ($current_page - 1) * $items_per_page;

$stmt = $pdo->prepare("SELECT * FROM users WHERE username LIKE :search_term LIMIT :start_number, :items_per_page");
$stmt->bindValue(':search_term', "%$search_term%", PDO::PARAM_STR);
$stmt->bindValue(':start_number', $start_number, PDO::PARAM_INT);
$stmt->bindValue(':items_per_page', $items_per_page, PDO::PARAM_INT);
$stmt->execute();
$users = $stmt->fetchAll();

$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM users WHERE username LIKE :search_term");
$stmt->bindValue(':search_term', "%$search_term%", PDO::PARAM_STR);
$stmt->execute();
$total_users = $stmt->fetch()['total'];

$total_pages = ceil($total_users / $items_per_page);
?>



    <div class="controls">
        <form action="edit_users.php" method="get">
            <input type="text" name="search" placeholder="Search by user name">
            <input type="submit" value="Search">
            <a href="register.php">Create New User</a>
        </form>
    </div>

    <div class="box">
        <table>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Password</th>
                <th>Admin</th>
                <th>Edit</th>
            </tr>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= str_repeat('*', strlen($user['password'])) ?></td>
                    <td><?= $user['admin'] ? '✔' : '✘' ?></td>
                    <td><a href="edit_user.php?id=<?= htmlspecialchars($user['id']) ?>">Edit</a></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <?php
    echo '<div class="pagination">';

    $start_page = max(1, $current_page - 5);
    $end_page = min($total_pages, $current_page + 5);

    if ($current_page > 1) {
        $prev_page = max(1, $current_page - 15);
        echo "<a class=\"page-arrow\" href=\"$_SERVER[PHP_SELF]?search=$search_term&page=$prev_page\"><<</a>";
    }

    for ($i = $start_page; $i <= $end_page; $i++) {
        if ($i == $current_page) {
            echo "<span class=\"page-number current-page\">$i</span>";
        } else {
            echo "<a class=\"page-number\" href=\"$_SERVER[PHP_SELF]?search=$search_term&page=$i\">$i</a>";
        }
    }

    if ($current_page < $total_pages) {
        $next_page = min($total_pages, $current_page + 15);
        echo "<a class=\"page-arrow\" href=\"$_SERVER[PHP_SELF]?search=$search_term&page=$next_page\">>></a>";
    }

    echo '</div>';
    ?>
</main>

<?php include 'footer.php'; ?>

</body>
</html>
