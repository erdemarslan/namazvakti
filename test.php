<?php

require_once 'namazvakti_class/class.namazvakti.php';

$n = new Namaz;

echo '<pre>';

$ulkeler = $n->ulkeler();

$sehirler = $n->sehirler(2);

$ilceler = $n->ilceler(521);

$vakit = $n->vakit(2, 521, 9351);


print_r($vakit);

print_r($ilceler);

print_r($sehirler);

print_r($ulkeler);
