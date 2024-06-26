<style>
    header {
        position: absolute;
        top: 0.5em;
        left: 0.5em;
        right: 0.5em;
        margin: 0 auto;
        width: auto;
        box-sizing: border-box;
        background-color: #bbc5ff;
        border-radius: 2em;
    }

    nav img {
        height: 6em;
    }

    nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
    }

    nav div {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        flex-wrap: wrap;
    }

    nav form {
        display: flex;
        align-items: center;
        flex-wrap: wrap; 
    }

    nav form input[type="text"],
    nav form input[type="password"] {
        margin-right: 10px;
        border-radius: 0.5em;
        border: 1px black solid;
    }


    @media screen and (max-width: 600px) {
        nav div, nav form {
            flex-direction: column; 
        }

        nav form input[type="text"],
        nav form input[type="password"] {
            margin-bottom: 10px; 
        }
    }


    nav ul {
        list-style-type: none;
        padding: 0;
    }

    nav ul li {
        display: inline;
        margin-right: 10px;
    }

    nav ul li a {
        color: #6470B6;
        font-family: 'Segoe UI', sans-serif;
        font-size: 2em;
        font-weight: bold;
        text-decoration: none;
    }


    nav ul li a:hover {
        color: #cccccc;
    }

    .welcome-message {
        color: #6470B6;
        font-family: 'Segoe UI', sans-serif;
        font-size: 2em;
        font-weight: bold;
        margin: 1em;
        display: flex;
        align-items: center;
    }

    #PB {
        height: 1.5em;
        margin-left: 0.5em;
    }

    .auth-button {
        padding: 10px 20px;
        margin: 1em;
        background-color: #6470B6;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .auth-button:hover {
        background-color: #4660a3;
    }

</style>

<header>

    <nav>
        <div>
            <img src="Bilder/logo.png" alt="Logo">
            <ul>
                <li><a href="#">Kategorie</a></li>
                <li><a href="#">Schriftsteller</a></li>
                <li><a href="index.php">Home</a></li>
            </ul>
        </div>
        <?php
        if (!isset($_SESSION['username'])) {
            // Zeigt das Login-Formular an, wenn der Nutzer nicht angemeldet ist
            ?>
            <div class="button-container">
                <button class="auth-button" onclick="window.location.href = 'login.php';">Login</button>
                <button class="auth-button" onclick="window.location.href = 'register.php';">Registrieren</button>
            </div>
            <?php
        } else {
            // zeigt denn namen an von dem user der iengelogt ist ( username)
            echo '<p class="welcome-message">Willkommen, ' . htmlspecialchars($_SESSION['username']) . '!<a href="Admin.php"><img id="PB" src="Bilder/PB.png" alt="<Profil Bild>" /></a></p>';
        }
        ?>
    </nav>

</header>