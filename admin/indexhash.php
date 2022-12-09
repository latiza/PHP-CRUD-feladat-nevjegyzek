<?php
session_start();

if(isset($_POST['rendben'])){
    //változók tisztítása
    $email = strip_tags(strtolower(trim($_POST['email'])));
    print_r($email);
    $jelszo = strip_tags(trim($_POST['jelszo']));
    print_r($jelszo);
//változók ellenőrzése

    if(empty($email) || 
    !filter_var($email, FILTER_VALIDATE_EMAIL) ||
    !preg_match("/^[a-zA-Z]*$/", $jelszo)){
    $hiba = "Hibás e-mail-t, vagy jelszót adtál meg!";
}
else{
    //sikeres
    if($email == "jancsi@gmail.com" && sha1($jelszo) == "49cef48df229f6e608f4b57c11ef05c4f014f0c6"){
            $_SESSION['belepett'] = true;
            header("Location: lista.php");
        }
        else{
            $hiba = "Hibás e-mail-t, vagy jelszót adtál meg!";
        }
    }
}



?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<meta name="Description" content="Enter your description here"/>
<link rel="stylesheet" href="style.css">
<title>Title</title>
</head>
<body>

<h1>Belépés</h1>
<form method="post" action="">
<!--hiba listát-->
<?php if(isset($hiba)) print $hiba; ?>
<p><label for="email">E-mail</label>
<input type="email" name="email" id="email" required></p>

<p>
    <label for="password">Jelszó</label>
    <input type="password" name="jelszo" id="jelszo">
</p>

<input type="submit" value="Belépés" id="rendben" name="rendben">

</form>

</body>
</html>