<?php
header("Content-Type: text/html; charset=utf-8");

define("DBHOST", "localhost");
define("DBUSER", "root"); //felhasználó név
define("DBPASS", "");//jelszó, ami most nincs
define("DBNAME", "nevjegyek");
/**itt mindig azt adod meg, ami az aktuális adatbázisod neve!! */

$dbconn = @mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME) or die("Hiba az adatbázis csatlakozáskor");

//ellenőrzés
/*
if(mysqli_connect_error()){
    die("a hiba:" . mysqli_connect_error());
}echo "Sikeres a kapcsolat";*/
    mysqli_query($dbconn, "SET NAMES utf8");
?>