<?php

$mysqli = new mysqli("localhost", "root", "", "nevjegyek");


if ($mysqli -> connect_errno) {
    echo "Kapcsoalt megszakadt: " . $mysqli -> connect_error;
    exit();
} echo "sikeres";

?>