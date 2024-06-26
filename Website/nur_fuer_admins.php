<?php
header("refresh:3;url=Index.php");
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Nur für Admins</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .message-box {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="message-box">
    <h1>Nur für Admins</h1>
    <p>Sie werden in 3 Sekunden zur Startseite weitergeleitet...</p>
</div>
</body>
</html>
