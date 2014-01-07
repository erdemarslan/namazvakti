<?php

echo '<pre>';


require 'namazvakti.class.php';

$cache_klasoru = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR;

$nv = new Namaz( $cache_klasoru );

$ulkeler	= $nv->ulkeler();
$sehirler	= $nv->sehirler( 'TURKIYE', 'array' );
$ilceler	= $nv->ilceler( 'CANAKKALE', 'array' );
$vakit	= $nv->vakit('BIGA','TURKIYE', 'array');

echo '<h2>Ülkeler</h2>';
print_r( $ulkeler );
echo '<h2>Şehirler</h2>';
print_r( $sehirler );
echo '<h2>İlçeler</h2>';
print_r( $ilceler );
echo '<h2>Vakit</h2>';
print_r( $vakit );

