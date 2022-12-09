<?php 
$jelszo = "juliska";
print "<p>Jelszó: {$jelszo} </p><br>";

print "<p>Jelszó: " .md5($jelszo) . "</p><br>";

print "<p>Jelszó: " .sha1($jelszo) . "</p><br>";



?>