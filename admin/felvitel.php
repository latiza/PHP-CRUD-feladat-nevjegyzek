<?php
//Lapvédelem
session_start();
if(!isset($_SESSION['belepett'])){
    header("Location:false.html");
    exit();
}
//Űrlap feldolgozása
if(isset($_POST['rendben'])){
//változók tisztítása
$nev = strip_tags(trim(ucwords($_POST['nev'])));
//print_r($nev);
$cegnev = strip_tags(trim(strtoupper($_POST['cegnev'])));
//print_r($cegnev);
$mobil = strip_tags(trim($_POST['mobil']));
$email = strip_tags(trim(strtolower($_POST['email'])));

//változók vizsgálata
$mime = array("image/jpeg", "image/gif", "image/png");
if(empty($nev))
    $hibak[] = "Nem adott meg nevet!";
elseif (strlen($nev) < 3)
    $hibak[] = "Túl rövid nevet adott meg!";
if(!empty($mobil) && strlen($mobil) < 6)
    $hibak[] = "Rossz mobil számot adott meg!";
if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) 
    $hibak[] = "E-mail címe helytelen!";
if($_FILES['foto']['error'] == 0 && $_FILES['foto']['size'] > 2000000)
    $hibak[] = "A kép mérete nagyobb mint 2 MB!";
if($_FILES['foto']['error'] == 0 && !in_array($_FILES['foto']['type'], $mime))
    $hibak[] = "A kép formátuma nem megfelelő!";

//új fájlnév elkészítése
switch($_FILES['foto']['type']){
    case "image/png" : $kit = ".png"; break;
    case "image/gif" : $kit = ".gif"; break;
    default: $kit = ".jpg";
}
$foto = date("U").$kit;
//Hibaüzenet összeállítása
if(isset($hibak)){
    $kimenet = "<ul>\n";
    foreach($hibak as $hiba){
        $kimenet.="<li>{$hiba}</li>";
    }
    $kimenet.= "</ul>\n";
}else{
    //felvitel az adatbázisba
    require("../kapcsolat.php");
    $sql = "INSERT INTO nevjegyek
            (foto, nev, cegnev, mobil, email)
            VALUES
            ('{$foto}','{$nev}', '{$cegnev}', '{$mobil}','{$email}')";
            mysqli_query($dbconn, $sql);

           //kép mozgatása a végleges helyére
           move_uploaded_file($_FILES['foto']['tmp_name'], "../kepek/{$foto}");
           header("Location:lista.php");
    }

}


?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Új dolgozó felvitele</title>
</head>
<body>
    <h1>Új névjegy felvitele</h1>

<form action="" method="post" enctype="multipart/form-data">
<!--kimenet:-->
<?php if(isset($kimenet)) print $kimenet;?>
<!--fotó feltöltése és méret korlátozása-->
<input type="hidden" name="MAX_FILE_SIZE" value="2000000">
<p><label for="foto">Fotó feltöltése:</label>
<input type="file" name="foto" id="foto"></p>
<!--Név megadása-->
<p><label for="nev">Név*:</label>
<input type="text" name="nev" id="nev" value=""></p>
<!--Cégnév megadása-->
<p><label for="cegnev">Cégnév*:</label>
<input type="text" name="cegnev" id="cegnev" value=""></p>
<!--Mobil szám:-->
<p><label for="mobil">Mobil szám*:</label>
<input type="tel" name="mobil" id="mobil" value=""></p>
<!--E-mail:-->
<p><label for="email">E-mail*:</label>
<input type="email" name="email" id="email" value=""></p>
<p><em>A *-al jelölt mezők kitöltése kötelező.</em></p>

<!--elküldés és reset-->
<input type="submit" value="Rendben" id="rendben" name="rendben">
<input type="reset" value="Mégsem">
<p><a href="lista.php">Vissza a névjegyekhez</a></p>
</form>

<?php include("footer.html");?>
</body>
</html>