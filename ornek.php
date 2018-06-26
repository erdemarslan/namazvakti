<?php

//require_once "class.namazvakti.php";
require_once "class.namazvakti.php";

// Sınıfı başlatır
$namaz = new Namaz();
// isterseniz değer olarak, cache klasör bilgisini verebilirsiniz
# $namaz = new Namaz("cache_klasorunun_yolu/"); // klasörün yolunun sonuna / koymayı unutmayın

// cache klasörünü değiştirmek için
$namaz->cacheKlasoru('cache/'); // klasörün yolunun sonuna / koymayı unutmayın

// Ülke, İl ve İlçeler isterseniz zincirleme metodu ile kullanabilirsiniz!
$namaz->ulke(2);
$namaz->il(521);
$namaz->ilce(9349);
//$namaz->ulke(2)->il(521)->ilce(9350); // bu şekilde de kullanabilirsiniz!

$ulkeler = $namaz->ulkeler(); // sonucu dizi olarak verir. json olarak almak istiyorsanz $namaz->ulkeler('json') demeniz yeterli
$iller = $namaz->sehirler(2); // ülke kodunun mutlaka belirtilmesi gerekir. çıktı ulkeler() ile aynıdır!
$ilceler = $namaz->ilceler(521); // il kodunun mutlaka belirtilmesi gerekir. çıktı ulkeler() ile aynıdır.

// bugüne ait verileri verir. cache bellekten okur. cache bellekte yoksa sunucudan çeker ve yazar.
$vakit = $namaz->vakit();
// $vakit = $namaz->vakit(2,521,9349, 'array'); // istenilirse ülke, il ve ilçe kodları direk fonksiyona gönderilebilir. çıktı durumu ulkeler() ile aynıdır

// cache bellekten hiç okumaz, direk sunucudan 1 aylık veriyi çeker ve cache belleğe yazar. her çağrıldığında verileri sunucudan alır o yüzden 1 aylık veri verir.
$vakitler = $namaz->vakitler();
// $vakit = $namaz->vakitler(2,521,9349, 'array'); // istenilirse ülke, il ve ilçe kodları direk fonksiyona gönderilebilir. çıktı durumu ulkeler() ile aynıdır

// cache belleği temizler. gerekli durumlarda cache belleği bu komutla temizleyebilirsiniz.
$namaz->cache_temizle();

echo '<pre>';
print_r($vakitler);

print_r($vakit);
