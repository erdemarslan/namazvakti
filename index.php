<?php

require_once("class.namaz.php");

// Sınıfı başlat!
$namaz = new Namaz();


// İsteğe bağlı ülke isimleri. Doğrudan dil değişimi yapılabilir!
$ulkeIsimleri = array(
	'TURKIYE' => 'Türkiye',
	'ABD' => 'ABD'
);

$sehirIsimleri = array(
	'ADANA' => 'Adana',
	'ÇANAKKALE' => 'Çanakkale'
);

$ilceIsimleri = array(
	'ÇAN' => 'Çan',
	'BİGA' => 'Biga'
);

echo '<pre>';
// Ülke isimlerinin tanımlanması
$namaz->ulkeIsimleri = $ulkeIsimleri;
$namaz->sehirIsimleri = $sehirIsimleri;
$namaz->ilceIsimleri = $ilceIsimleri;

// ülkelerin çekilmesi
$ulkeler = $namaz->ulkeler();

$sehirler = $namaz->sehirler(2);

$ilceler = $namaz->ilceler(2, 521);

$vakit = $namaz->vakit(9349);

$vakitler = $namaz->vakitler(9348);


print_r($ulkeler);
print_r($sehirler);
print_r($ilceler);
print_r($vakit);
print_r($vakitler);