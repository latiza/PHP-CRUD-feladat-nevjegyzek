<?php
require("kapcsolat.php");
// Lapozó beállításai
$sql = "SELECT*
            FROM nevjegyek";
$eredmeny = mysqli_query($dbconn, $sql);

//szükséges adatok a számításhoz
$mennyit = 9; //ennyi kártyát akarok látni egy oldalon
$osszesen = mysqli_num_rows($eredmeny); //97 db
//print_r ($osszesen);
$lapok = ceil($osszesen / $mennyit);
//print_r($lapok);

$aktualis = (isset($_GET['oldal'])) ? (int)$_GET['oldal'] : 1;
/**
 * 0, 10 - gép számára 0-tól 1 oldal
 * 10,10
 * 20, 10 - 3ik oldal
 */
$honnan = ($aktualis - 1) * $mennyit >= 1 ? ($aktualis - 1) * $mennyit : 1;
//print_r("<br>Honnan: ".$honnan);

// lapozó felépítése, hivatkozásoknak kell lennie
$lapozo = '<p>';
$lapozo.= ($aktualis != 1) ? "<a href=\"?oldal=1\">Első</a> |" : "Első |";

$lapozo.= ($aktualis > 1 && $aktualis <= $lapok) ? "<a href=\"?oldal=".($aktualis -1)."\">Előző</a> | " : "Előző |";

//összes oldalra el kell végezni a vizsgálatot, amin állsz ne legyen link

for ($oldal = 1; $oldal<=$lapok; $oldal++) { 
   $lapozo.= ($aktualis != $oldal) ? "<a href=\"?oldal={$oldal}\">{$oldal}</a> | " : $oldal." | "; 
}
$lapozo.= ($aktualis > 0 && $aktualis < $lapok) ? "<a href=\"?oldal=".($aktualis + 1)."\">Következő</a> | " : "Következő | ";

$lapozo.= ($aktualis != $lapok) ? "<a href=\"?oldal={$lapok}\">Utolsó</a>" : "Utolsó";

$lapozo.= '</p>';

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
            ORDER BY nev ASC
            LIMIT {$honnan}, {$mennyit}";

$eredmeny = mysqli_query($dbconn, $sql);
//ha valaki érvénytelen oldalt ír be, akkor, írja ki nincs találat

if(@mysqli_num_rows($eredmeny) < 1 ){
    $kimenet = "<article> 
    <h2>Nincs találat a rendszerben!</h2>
    </article>\n";
}else {

$kimenet = "";

while ($sor = mysqli_fetch_assoc($eredmeny)) {
    $kimenet.= "<article>
<img src=\"kepek/{$sor['foto']}\" alt=\"{$sor['nev']}\">

    <h2>{$sor['nev']}</h2>
    <h3>{$sor['cegnev']}</h3>
    <p>Mobil: <a href=\"tel:{$sor['mobil']}\">{$sor['mobil']}</a></p>
    <p>E-mail: <a href=\"mailto:{$sor['email']}\">{$sor['email']}</a></p>
</article>\n";
    }
}
//print_r($sor);


?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./admin/style.css">
    <title>Névjegykártyák</title>
</head>
<body>
    <h1>Névjegyek</h1>

<form method="post" action="">
    <input type="search" name="kifejezes" id="kifejezes">
</form>
<?php print $lapozo; ?>
    <div class="container">
        
    <?php 
    print $kimenet;
    ?>
<!--<p style="clear:both;">Első | Előző | 1 | 2 | 3 | 4 | Következő | Utolsó </p>
        -->
    </div> 
    <?php print $lapozo; ?>
</body>
</html>