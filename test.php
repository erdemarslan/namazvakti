<?php

require_once 'namazvakti_class/class.namazvakti.php';

$n = new Namaz();

echo '<pre>';

// Hac kuralarının diyanet tarafından paylaşıldığı zamanlarda true olmalı
$n->hac_mevsimi(false);

// cache klasörünü temizler
$n->cache_temizle();

// ülkeleri alır
$ulkeler = $n->ulkeler();

// şehirleri alır
$sehirler = $n->sehirler(2);

// ilçeleri alır
$ilceler = $n->ilceler(521);

// vakti diyanetin sunucularından çeker
$vakit = $n->vakit(2, 521, 9351);





print_r($vakit);

print_r($ilceler);

print_r($sehirler);

print_r($ulkeler);
