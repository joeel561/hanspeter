<?php 
session_start();
$pdo = new PDO('mysql:host=127.0.0.1;dbname=hanspeter', 'root', 'root');


if(isset($_GET['login'])) {
    $email = $_POST['email'];
    $passwort = $_POST['passwort'];

    $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $result = $statement->execute(array('email' => $email));
    $user = $statement->fetch();

    if ($user !== false && password_verify($passwort, $user['passwort'])) {
      $_SESSION['userid'] = $user['id'];
      die('Bist drin. Weiter zu geheime Sachen <a href="geheim.php"> los </a>');
    } else {
        $errorMessage = "Email oder Passwort ist falsch.<br>";
    }
}
?>
<!DOCTYPE html> 
<html> 
    <head>
    <title>Login</title>    
    </head> 
    <body>

    <?php 
    if (isset($errorMessage)) {
        echo $errorMessage;
    }
    ?>
        <form action="?login=1" method="post">
            E-Mail: <br>
            <input type="email" size="40" maxlength="250" name="email"><br><br>
            Passwort:<br>
            <input type="password" size="40" maxlength="250" name="passwort"><br>
            <input type="submit" value="Abschicken">
        </form>
    </body>
</html>