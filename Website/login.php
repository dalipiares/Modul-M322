<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css">
    <title>Login</title>
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
        .login-form input[type="password"] {
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
            margin-top: 0.1em;
            border: none;
            background: linear-gradient(to bottom, #b9ccff, #8bceff);
            color: #000000;
            border-radius: 2px;
        }

        .login-form button:hover {
            background-color: #7187b9;
        }




    </style>
</head>
<body>
<main>
<?php



session_start();

require 'config.php';

$error = ''; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // passwort und username werden geprüft
        $_SESSION['username'] = $username;
        header("Location: index.php");// wenn erfolgreicher lohgin dann zur Hompage
        exit; 
    } else {
        $error = "Invalid username or password.";
    }
}

include 'header.php';

?>
    <form class="login-form" action="login.php" method="post">
        <h2>Login</h2>
        <?php if ($error): ?>
            <p style="color: red;"><?= $error ?></p>
        <?php endif; ?>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
        <button type="button" onclick="window.location.href='register.php'">Register</button>
    </form>

</main>


</body>

<?php include 'footer.php'; ?>

</html>