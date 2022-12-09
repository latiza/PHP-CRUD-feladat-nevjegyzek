<?php
//Lapvédelem
session_start();
if(!isset($_SESSION['belepett'])){
    header("Location:false.html");
    exit();
}
require("../kapcsolat.php");
//Űrlap feldolgozása
if(isset($_POST['rendben'])){
//változók tisztítása
$mime = array("image/jpeg", "image/gif", "image/png");
$nev = strip_tags(trim(ucwords($_POST['nev'])));
$cegnev = strip_tags(trim(strtoupper($_POST['cegnev'])));
$mobil = strip_tags(trim($_POST['mobil']));
$email = strip_tags(trim(strtolower($_POST['email'])));

//változók vizsgálata

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
        //Módosítás felvitele az adatbázisba
        $id = (int)$_GET['id'];
        $sql = "UPDATE nevjegyek
           SET foto = '{$foto}', nev = '{$nev}', cegnev = '{$cegnev}', 
           mobil = '{$mobil}', email = '{$email}'
           WHERE id = {$id}";
           
            mysqli_query($dbconn, $sql);
            
           //kép mozgatása a végleges helyére
           move_uploaded_file($_FILES['foto']['tmp_name'], "../kepek/{$foto}");
           header("Location:lista.php");
    }
}//űrlap előzetes kitöltése
else{
    $id = (int)$_GET['id'];
    $sql = "SELECT *
            FROM nevjegyek
            WHERE id = {$id}";
    $eredmeny = mysqli_query($dbconn, $sql);
    $sor = mysqli_fetch_assoc($eredmeny);

    $nev = $sor['nev'];
    $cegnev = $sor['cegnev'];
    $mobil = $sor['mobil'];
    $email = $sor['email'];
    $foto = ($sor['foto'] != "nincskep.png") ? $sor['foto'] : "nincskep.png";
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
    <h1>Névjegy módosítása</h1>

<form action="" method="post" enctype="multipart/form-data">
<!--kimenet:-->
<?php if(isset($kimenet)) print $kimenet;?>
<!--fotó feltöltése és méret korlátozása-->
<input type="hidden" id="id" name="id" value="<?php print $id; ?>">
<input type="hidden" name="MAX_FILE_SIZE" value="2000000">
<img src="../kepek/<?php print $foto; ?>" alt="<?php $nev; ?>">
<p><label for="foto">Fotó feltöltése:</label>
<input type="file" name="foto" id="foto"></p>
<!--Név megadása-->
<p><label for="nev">Név*:</label>
<input type="text" name="nev" id="nev" value="<?php print $nev; ?>"></p>
<!--Cégnév megadása-->
<p><label for="cegnev">Cégnév*:</label>
<input type="text" name="cegnev" id="cegnev" value="<?php print $cegnev; ?>"></p>
<!--Mobil szám:-->
<p><label for="mobil">Mobil szám*:</label>
<input type="tel" name="mobil" id="mobil" value="<?php print $mobil; ?>"></p>
<!--E-mail:-->
<p><label for="email">E-mail*:</label>
<input type="email" name="email" id="email" value="<?php print $email; ?>"></p>
<p><em>A *-al jelölt mezők kitöltése kötelező.</em></p>

<!--elküldés és reset-->
<input type="submit" value="Rendben" id="rendben" name="rendben">
<input type="reset" value="Mégsem">
<p><a href="lista.php">Vissza a névjegyekhez</a></p>
</form>

</body>
</html>