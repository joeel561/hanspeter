<?php 
session_start();
$pdo = new PDO('mysql:host=127.0.0.1;dbname=hanspeter', 'root', 'root');
?>
<!DOCTYPE html> 
<html> 
<head>
  <title>Registrierung</title>    
</head> 
<body>
 <?php
 $showFormular = true;
if (isset($_GET['register'])) {
    $error = false;
    $email = $_POST['email'];
    $passwort = $_POST['passwort'];
    $passwort2 = $_POST['passwort2'];

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo 'Bitte gib eine gueltige Email an, bro<br>';
        $error= true;
    }
    if(strlen($passwort) == 0) {
        echo 'Passwort bro<br>';
        $error = true;
    }
    if($passwort != $passwort2) {
        echo 'Passwort immernoch bro<br>';
        $error = true;
    }

    if(!$error) {
        $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $result = $statement->execute(array(':email' => $email));
        $user = $statement->fetch();

        if($user !== false) {
            echo 'Email ist vergeben<br>';
            $error = true;
        }
    }

    if(!$error) {
        $passwort_hash = password_hash($passwort, PASSWORD_DEFAULT);

        $statement = $pdo->prepare("INSERT INTO users (email, passwort) VALUES (:email, :passwort)");
        $result = $statement->execute(array('email' => $email, 'passwort' => $passwort_hash));

        if($result) {
            echo 'Du bist drin. <a href="login.php">Zum Login</a>';
            $showFormular = false;
        } else {
            echo 'Beim Abspeichern ist ein Fehler aufgetreten.<br>';
        }
    }
}

if ($showFormular) {
    ?>

    <form action="?register=1" method="post">
        E-Mail:<br>
        <input type="email" size="40" maxlength="250" name="email"><br><br>
        
        Dein Passwort:<br>
        <input type="password" size="40"  maxlength="250" name="passwort"><br>
        
        Passwort wiederholen:<br>
        <input type="password" size="40" maxlength="250" name="passwort2"><br><br>
        
        <input type="submit" value="Abschicken">
    </form>
<?php
}
?>
</body>
<html>