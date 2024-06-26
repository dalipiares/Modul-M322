<?php
session_start();
require 'config.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['delete'])) {
    // Dadurch kann man einen Benutzer löschen (den aktuellen Benutzer)
    $stmt = $pdo->prepare("DELETE FROM users WHERE username = :username");
    $stmt->execute([':username' => $_SESSION['username']]);

    // damit alles richtig funktionert wird die session beendet und wird auf die hompage weiter geleietet
    session_destroy();
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];

        // schaut ob das aktuelle Passwort richtig ist um änderungen vorzunehemn
        $stmt = $pdo->prepare("SELECT password FROM users WHERE username = :username");
        $stmt->execute([':username' => $_SESSION['username']]);
        $user = $stmt->fetch();

        if ($user && password_verify($current_password, $user['password'])) {
            // Aktualisiert die daten
            $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET username = :username, email = :email, password = :password WHERE username = :current_username");
            if ($stmt->execute([':username' => $username, ':email' => $email, ':password' => $hashed_new_password, ':current_username' => $_SESSION['username']])) {
                $_SESSION['username'] = $username;
                header("Location: change_successful.php");
                exit;
            } else {
                header("Location: change_failed.php");
                exit;
            }
        } else {
            header("Location: change_failed.php");
            exit;
        }
    }
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
$stmt->execute([':username' => $_SESSION['username']]);
$user = $stmt->fetch();

if ($user) {
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Edit User Data</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body .foot{
            position: fixed;
            bottom: 0;
            align-self: end;
        }

        body {
            background-size: auto;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
<form action="edit_user_data.php" method="post">
    <label>
        Username:
        <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
    </label>
    <label>
        Email:
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
    </label>
    <label>
        Current Password:
        <input type="password" name="current_password" required>
    </label>
    <label>
        New Password:
        <input type="password" name="new_password" required>
    </label>
    <input type="submit" name="update" value="Update">
</form>
<form method="post" action="edit_user_data.php">
    <input type="hidden" name="delete" value="true">
    <input type="submit" value="Delete User" style="background-color: red; color: white;">
</form>
</body>

<?php include 'footer.php'; ?>
</html>
<?php
} else {
    
    header("Location: login.php");
    exit;
}
?>
