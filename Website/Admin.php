<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['username'])) {
    // schaut ob jemand eingelogt ist , wenn nicht zurück zur login seite
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Admin Page</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom, #E6F4FF, #AAEEFF) no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        body .foot{
            position: fixed;
            bottom: 0;
            align-self: flex-end;
        }

        .admin-box {
            background: #BBC5FF;
            width: 300px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #000;
            border-radius: 10px;
            text-align: center;
        }
        .admin-box h2 {
            margin-bottom: 20px;
        }
        .admin-box button {
            display: block;
            margin: 10px auto;
            padding: 10px 20px;
        }
    </style>
</head>
<main>
<body>
<?php include 'header.php'; ?>
<div class="admin-box">
    <h2>@<?php echo htmlspecialchars($_SESSION['username']); ?></h2>
    <button onclick="window.location.href='edit_books.php'">Edit Books</button>
    <button onclick="window.location.href='edit_users.php'">Edit Accounts</button> <!-- Changed to edit_users.php -->
    <button onclick="window.location.href='edit_user_data.php'">Edit User Data</button>
    <button onclick="window.location.href='logout.php'">Logout</button>
</div>
</body>
</main>

<?php include 'footer.php'; ?>

</html>
