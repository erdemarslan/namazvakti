<?php

require_once "class.namazvakti.php";

$namaz = new Namaz();

echo "<pre>";
//print_r($namaz->ulkeler());
//print_r($namaz->sehirler(2));
print_r($namaz->ilceler(521));

print_r($namaz->vakit(2,521,9349));