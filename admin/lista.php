<?php
//Lapvédelem
session_start();
if(!isset($_SESSION['belepett'])){
    header("Location:false.html");
    exit();
}

require("../kapcsolat.php");
$rendez = (isset($_GET['rendez'])) ? $_GET['rendez'] : "nev";
$kifejezes = (isset($_POST['kifejezes'])) ? $_POST['kifejezes'] : "";
//print_r ($kifejezes);
//var_dump ($kifejezes);
$sql = "SELECT*
            FROM nevjegyek
            WHERE (
                nev LIKE '%{$kifejezes}%'
                OR cegnev LIKE '%{$kifejezes}%'
                OR mobil LIKE '%{$kifejezes}%'
                OR email LIKE '%{$kifejezes}%'
            )
            ORDER BY {$rendez} ASC";

$eredmeny = mysqli_query($dbconn, $sql);
$kimenet = "<table>
<tr>
    <th>Fotó</th>
    <th><a href=\"?rendez=nev\">Név</a></th>
    <th><a href=\"?rendez=cegnev\">Cégnév</a></th>
    <th>Mobil</th>
    <th>E-mail</th>
    <th>Műveletek</th>
</tr>";
while ($sor = mysqli_fetch_assoc($eredmeny)) {
    $kimenet.= "<tr>
    <td><img src=\"../kepek/{$sor['foto']}\" alt=\"{$sor['foto']}\"</td>
    <td>{$sor['nev']}</td>
    <td>{$sor['cegnev']}</td>
    <td>{$sor['mobil']}</td>
    <td>{$sor['email']}</td>
    <td><a href=\"torles.php?id={$sor['id']}\">Törlés</a> | <a href=\"modositas.php?id={$sor['id']}\">Módosítás</a></td>
</tr>";
}
$kimenet.= " </table>";
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<meta name="Description" content="Enter your description here"/>
<link rel="stylesheet" href="style.css">
<title>Névjegyek</title>
</head>
<body>
<h1>Névjegyek</h1>

<form method="post" action="">
    <input type="search" name="kifejezes" id="kifejezes">
</form>
<p><a href="felvitel.php">Új névjegy felvitele</a> | <a href="kilepes.php"> Kilépés</a></p>
<?php print $kimenet; ?>
<p><a href="felvitel.php">Új névjegy felvitele</a> | <a href="kilepes.php"> Kilépés</a></p>

</body>
</html>