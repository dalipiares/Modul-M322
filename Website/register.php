<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">

    <link rel="stylesheet" href="styles.css">

    <title>Register</title>
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

        body .foot {
            position: absolute;
            bottom: 0;
            align-self: end;
        }

        .login-form {
            width: 300px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border-radius: 2px;
        }

        .login-form h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .login-form input[type="text"],
        .login-form input[type="password"],
        .login-form input[type="email"] {
            width: 100%;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 2px;
            margin-bottom: 20px;
            box-sizing: border-box;
        }

        .login-form button {
            width: 100%;
            padding: 10px;
            border: none;
            background: linear-gradient(to bottom, #b9ccff, #8bceff);
            color: #000000;
            border-radius: 2px;
        }

        .login-form button:hover {
            background-color: #7187b9;
        }

        .admin-key {
            margin-top: 10px;
        }
    </style>
</head>
<body>
<?php

session_start();

require 'config.php';

define('ADMIN_KEY', '1234'); // hier kann man einen admin schlüssel festlegen 

$error = ''; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $is_admin = isset($_POST['admin']);
    $admin_key = $_POST['admin_key'];

    if (strlen($username) > 45) {
        $error = "Der Benutzername darf maximal 45 Zeichen lang sein.";
    } elseif (strlen($password) < 8) {
        $error = "Das Passwort muss mindestens 8 Zeichen lang sein.";
    } elseif ($is_admin && $admin_key !== ADMIN_KEY) {
        header("Location: change_failed.php");
        exit;
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();

        if ($user) {
            $error = "Benutzername bereits vergeben.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, admin) VALUES (:username, :email, :password, :admin)");
            $stmt->execute([
                ':username' => $username,
                ':email' => $email,
                ':password' => $hashed_password,
                ':admin' => $is_admin && $admin_key === ADMIN_KEY ? 1 : 0
            ]);
            header("Location: login.php");
        }
    }
}
include 'header.php';
?>
<form class="login-form" action="register.php" method="post">
    <h2>Registrieren</h2>
    <?php if ($error): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>
    <input type="text" name="username" placeholder="Username" maxlength="45" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" minlength="8" required>
    <label>
        <input type="checkbox" name="admin"> Admin
    </label>
    <input type="text" name="admin_key" placeholder="Admin Key" class="admin-key">
    <button type="submit">Registrieren</button>
</form>

<?php include 'footer.php'; ?>

</body>
</html>
