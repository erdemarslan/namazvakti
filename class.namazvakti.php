<?php

/**
 * Namaz - Diyanet İşleri Başkanlığından veri çekme sınıfı
 *
 * @author		Erdem ARSLAN <http://www.erdemarslan.com> <erdemsaid@gmail.com>
 * @copyright	Copyright (c) 2006-2017 erdemarslan.com
 * @link		http://www.erdemarslan.com/programlama/php-programlama/06-01-2014/563-namaz-vakitleri-php-sinifi.html
 * @version     7.0
 * @license		GPL v2.0
 */

 require_once "class.simple_html_dom.php";
 require_once "class.hicri.php";

 Class Namaz {
   // Sabitler
   protected $ulke = 2;
   protected $il = 521;
   protected $ilce = 9349;

   // Ön bellekleme
   protected $cache_klasoru = 'cache';
   protected $cache;

   // Ülke isimleri için dil çevirisinde kullanılacak! $key değeri veritabanından gelen değer, $value değeri çevrimi yapılmış değer!
   public $ulke_isimleri = array();

   protected $ulkeler, $sehirler, $ilceler, $hicri;
   protected $server = 'http://namazvakitleri.diyanet.gov.tr/tr-TR/';

   public $miladiaylar = array(
     0  =>  '',
     1  =>  'Ocak',
     2  =>  'Şubat',
     3  =>  'Mart',
     4  =>  'Nisan',
     5  =>  'Mayıs',
     6  =>  'Haziran',
     7  =>  'Temmuz',
     8  =>  'Ağustos',
     9  =>  'Eylül',
     10  =>  'Ekim',
     11  =>  'Kasım',
     12  =>  'Aralık',
   );
   public $haftaningunleri = array(
     0  => '',
     1  => 'Pazartesi',
     2  => 'Salı',
     3  => 'Çarşamba',
     4  => 'Perşembe',
     5  => 'Cuma',
     6  => 'Cumartesi',
     7  => 'Pazar'
   );
   public $hicriaylar = array(
     0  =>  '',
     1  =>  "Muharrem",
     2  =>  "Safer",
     3  =>  "Rebiü'l-Evvel",
     4  =>  "Rebiü'l-Ahir",
     5  =>  "Cemaziye'l-Evvel",
     6  =>  "Cemaziye'l-Ahir",
     7  =>  "Recep",
     8  =>  "Şaban",
     9  =>  "Ramazan",
     10  =>  "Sevval",
     11  =>  "Zi'l-ka'de",
     12  =>  "Zi'l-Hicce",
   );

   ############################################################################
   #                          GENEL FONKSIYONLAR                              #
   ############################################################################

   // Yapılandırıcı fonksiyon!
   public function __construct($cacheklasoru = NULL) {
     $dosyayolu = (__DIR__);
     $this->cache = is_null( $cacheklasoru ) === TRUE ? $dosyayolu . DIRECTORY_SEPARATOR . $this->cache_klasoru . DIRECTORY_SEPARATOR : $cacheklasoru;

     $this->ulkeler = file_get_contents($dosyayolu . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . 'ulkeler.ndb');
     $this->sehirler = file_get_contents($dosyayolu . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . 'sehirler.ndb');
     $this->ilceler = file_get_contents($dosyayolu . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . 'ilceler.ndb');

     $this->hicri = new HijriDateTime();
   }
   // cache klasörünü değiştirir! sınıfın kendisini döndürür.
   public function cacheKlasoru($cacheklasoru) {
     $this->cache = $cacheklasoru;
     return $this;
   }
   // ülke ayarla, sınıfın kendisini döndürür.
   public function ulke($ulke) {
     if($ulke > 0) {
       $this->ulke = $ulke;
     }
     return $this;
   }
   // il ayarla
   public function il($il) {
     if($il > 0) {
       $this->il = $il;
     }
     return $this;
   }
   // ilçe ayarla sınıfın kendisi döner geri
   public function ilce($ilce) {
     if($ilce > 0) {
       $this->ilce = $ilce;
     }
     return $this;
   }
   // Ülkeleri verir
   public function ulkeler( $cikti='array' ) {
     $ulkeler = json_decode($this->ulkeler, TRUE);

     $sonuc = array(
       'durum' => 'hata',
       'veri' => array()
     );

     foreach ($ulkeler as $key => $value) {
       $sonuc['durum'] = 'basarili';
       $sonuc['veri'][$key] = array_key_exists($value, $this->ulke_isimleri) === TRUE ? $this->ulke_isimleri[$value] : $value;
     }

     $yazdir = $cikti == 'array' ? $sonuc : json_encode($sonuc);
     return $yazdir;
   }
   // Şehirleri verir! Ülkenin idsinin belirtilmesi gerekir!
   public function sehirler( $ulke, $cikti='array' ) {
     $sehirler = json_decode($this->sehirler, TRUE);

     $sonuc = array(
       'durum' => 'hata',
       'veri' => array()
     );

     if(array_key_exists($ulke, $sehirler)) {
       $sonuc['durum'] = 'basarili';
       $sonuc['veri'] = $sehirler[$ulke];
     }

     $yazdir = $cikti == 'array' ? $sonuc : json_encode($sonuc);
     return $yazdir;
   }
   // İlçeleri verir!
   public function ilceler($sehir, $cikti='array') {
     $ilceler = json_decode( $this->ilceler, TRUE );

     $sonuc = array(
       'durum' => 'hata',
       'veri' => array()
     );

     if(array_key_exists($sehir, $ilceler)) {
       $sonuc['durum'] = 'basarili';
       $sonuc['veri'] = $ilceler[$sehir];
     }

     $yazdir = $cikti == 'array' ? $sonuc : json_encode($sonuc);
     return $yazdir;
   }
   // vakti verir! Tek veri gelmesi lazım!
   public function vakit($ulke=NULL, $sehir=NULL, $ilce=NULL, $cikti='array') {
     $this->ulke = is_null($ulke) === TRUE ? $this->ulke : $ulke;
     $this->il = is_null($sehir) === TRUE ? $this->il : $sehir;
     $url = "";
     if($this->ulke == 2 || $this->ulke == 33 || $this->ulke == 52) {
       $this->ilce = is_null($ilce) === TRUE ? $this->ilce : $ilce;
       $ilce = $this->ilceler($this->il);
       //$url = $this->server . $this->ilce . "/" . strtolower($ilce['veri'][$this->ilce]) . "-icin-namaz-vakti";
       $url = $this->server . $this->ilce . "/" . str_replace(' ', '-',trim(strtolower($ilce['veri'][$this->ilce]))) . "-icin-namaz-vakti";
     } else {
       $this->ilce = is_null($ilce) === TRUE ? $this->il : $this->ilce;
       $il = $this->sehirler($this->ulke);
       //$url = $this->server . $this->ilce . "/" . strtolower($il['veri'][$this->il]) . "-icin-namaz-vakti";
       $url = $this->server . $this->ilce . "/" . str_replace(' ', '-',trim(strtolower($il['veri'][$this->il]))) . "-icin-namaz-vakti";
     }

     $cache_dosya_adi = 'vakit_' . $this->ulke . '_' . $this->il . '_' . $this->ilce;
     // Aranacak tarihi belirle!
     $tarih = date("d.m.Y", time());

     if($this->_cache_sor($cache_dosya_adi, FALSE)) {
       // cache geçerli
       $adata = $this->_cache_oku($cache_dosya_adi);
       $data = json_decode($adata, TRUE);

       if(array_key_exists($tarih, $data['veri']['vakitler'])) {
         $sonuc = $data;
       } else {
         $veri = $this->_al_vakitler($url);
         if($veri['durum'] == 'basarili') {
           $this->_cache_yaz($cache_dosya_adi, json_encode($veri));
         }
         $sonuc = $veri;
       }
     } else {
       // cache yok!
       $veri = $this->_al_vakitler($url);
       if($veri['durum'] == 'basarili') {
         $this->_cache_yaz($cache_dosya_adi, json_encode($veri));
       }
       $sonuc = $veri;
     }
     $sonucIstenen = array(
       'durum' => 'basarili',
       'veri' => array(
         'ulke' => '',
         'sehir' => '',
         'ilce' => '',
         'vakit' => array()
       )
     );
     $sonucIstenen['veri']['ulke'] = $sonuc['veri']['ulke'];
     $sonucIstenen['veri']['sehir'] = $sonuc['veri']['sehir'];
     $sonucIstenen['veri']['ilce'] = $sonuc['veri']['ilce'];
     $sonucIstenen['veri']['vakit'] = $sonuc['veri']['vakitler'][$tarih];

     $yazdir = $cikti == 'array' ? $sonucIstenen : json_encode($sonucIstenen);
     return $yazdir;
   }
   // cache temizler
   public function cache_temizle() {
     array_map('unlink', glob($this->cache . "*.ndb"));
   }
   // Aylık veri çeker! Çekme işlemi başarılıysa, cache dosyasına da yazar. Cachdeden okuma yapmaz!
   public function vakitler($ulke=NULL, $sehir=NULL, $ilce=NULL, $cikti='array') {
     $this->ulke = is_null($ulke) === TRUE ? $this->ulke : $ulke;
     $this->il = is_null($sehir) === TRUE ? $this->il : $sehir;
     $url = "";
     if($this->ulke == 2 || $this->ulke == 33 || $this->ulke == 52) {
       $this->ilce = is_null($ilce) === TRUE ? $this->ilce : $ilce;
       $ilce = $this->ilceler($this->il);
       $url = $this->server . $this->ilce . "/" . strtolower($ilce['veri'][$this->ilce]) . "-icin-namaz-vakti";
     } else {
       $this->ilce = is_null($ilce) === TRUE ? $this->il : $this->ilce;
       $il = $this->sehirler($this->ulke);
       $url = $this->server . $this->ilce . "/" . strtolower($il['veri'][$this->il]) . "-icin-namaz-vakti";
     }

     $cache_dosya_adi = 'vakit_' . $this->ulke . '_' . $this->il . '_' . $this->ilce;

     $veri = $this->_al_vakitler($url);
     if($veri['durum'] == 'basarili') {
       $this->_cache_yaz($cache_dosya_adi, json_encode($veri));
     }

     $yazdir = $cikti == 'array' ? $veri : json_encode($veri);
     return $yazdir;
   }


   ############################################################################
   #                          ÖZEL FONKSIYONLAR                               #
   ############################################################################
   // cache den sorar var mı diye?
   private function _cache_sor($dosya_adi, $gunluk=FALSE) {
     $dosya = $this->cache . $dosya_adi . ".ndb";
     if( file_exists($dosya) && is_readable($dosya)) {
       if($gunluk) {
         $bugun = date("dmY", time());
         $dosyaZamani = date("dmY", filemtime($dosya));
         if($bugun == $dosyaZamani) {
           return TRUE;
         } else {
           return FALSE;
         }
       } else {
         return TRUE;
       }
     } else {
       return FALSE;
     }
   }
   //  cache belleğe veriyi yaz
   private function _cache_yaz($dosya_adi, $jsonveri) {
     $dosya = $this->cache . $dosya_adi . ".ndb";
     $fp = fopen($dosya, "w");
     fwrite($fp, $jsonveri);
     fclose($fp);
     return;
   }
   // cache belleği oku
   private function _cache_oku($dosya_adi) {
     $dosya = $this->cache . $dosya_adi . ".ndb";
     return file_get_contents($dosya);
   }
   // Sonucu alır getirir!
   private function _al_vakitler($url) {
     $sonuc = $this->_curl($url);
     return $sonuc;
   }
   // Hicri tarihi uzun ve kısa biçimde verir!
   private function _hicriTarih($tarih=NULL, $uzun=FALSE) {
     if ($tarih === null) $tarih = date('d.m.Y',time());
     $t = explode('.',$tarih);
     $bugun = $this->hicri->GeToHijr($t[0], $t[1], $t[2]);
     $sonuc = "";
     if($uzun) {
       $sonuc = $bugun['day'] . ' ' . $this->hicriaylar[$bugun['month']] . ' ' . $bugun['year'];
     } else {
       $sonuc = $bugun['day'] . '.' . $bugun['month'] . '.' . $bugun['year'];
     }

     return $sonuc;
   }
   // Tarihi uzun bir biçimde verir. Dil değişikliklerinde kolaylık olsun diye böyle bir fonksiyon yazılmıştır!
   private function _uzunTarih($tarih=NULL) {
     if ($tarih === null) $tarih = date('d.m.Y',time());
     return date("d", strtotime($tarih)) . " " . $this->miladiaylar[date("m", strtotime($tarih)) * 1] . " " . date("Y", strtotime($tarih)) . " " . $this->haftaningunleri[date("N", strtotime($tarih))];
   }
   // Veriyi sunucudan çeker!
   private function _curl($url) {
     $ch = curl_init();
     curl_setopt( $ch, CURLOPT_HTTPGET, TRUE );
     curl_setopt( $ch, CURLOPT_URL, $url );
     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
     curl_setopt( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0' );
     curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
     curl_setopt( $ch, CURLOPT_REFERER, "http://namazvakitleri.diyanet.gov.tr/tr-TR" );

     $veri = curl_exec( $ch );
     $bilgi = curl_getinfo( $ch );

     $sonuc = array(
       'durum' => 'hata',
       'veri' => NULL
     );

     if($bilgi['http_code'] == 200) {
       $sonuc['durum'] = 'basarili';
       $sonuc['veri'] = $this->_html_ayikla($veri);
     }

     curl_close($ch);
     return $sonuc;
   }
   // curl ile gelen veriyi ayıklar!
   private function _html_ayikla($veri) {
     $sonuc = array(
       'ulke' => '',
       'sehir' => '',
       'ilce' => '',
       'vakitler' => array()
     );

     // gelen veri var mı?
     if(strpos($veri, "<div id=\"tab-1\" class=\"w3-container w3-border nv-tab-content\" style=\"display:none\">") !== FALSE) {

       $bolme1 = explode("<div id=\"tab-1\" class=\"w3-container w3-border nv-tab-content\" style=\"display:none\">", $veri);
       $elimizde_kalan = explode("</section>", $bolme1[1]);

       // Ülkeyi al!
       $ulkeler = $this->ulkeler();
       $sonuc['ulke'] = $ulkeler['veri'][$this->ulke];

       $sehirler = $this->sehirler($this->ulke);
       $sonuc['sehir'] = $sehirler['veri'][$this->il];

       if($this->ulke == 2 || $this->ulke == 33 || $this->ulke == 52) {
         $ilceler = $this->ilceler($this->il);
         $sonuc['ilce'] = $ilceler['veri'][$this->ilce];
       } else {
         $sonuc['ilce'] = $sonuc['sehir'];
       }


       $html = str_get_html($elimizde_kalan[0]);

       foreach($html->find('tr') as $tr) {
         $sira = 0;
         $simdikisatir = "";
         foreach ($tr->find('td') as $td) {
           $elde = trim($td->plaintext);
           if($sira == 0) {
             $sonuc['vakitler'][$elde] = array(
               'tarih' => $elde,
               'tarih_uzun' => $this->_uzunTarih($elde),
               'hicri' => $this->_hicriTarih($elde),
               'hicri_uzun' => $this->_hicriTarih($elde, TRUE),
               'imsak' => '',
               'gunes' => '',
               'ogle' => '',
               'ikindi' => '',
               'aksam' => '',
               'yatsi' => ''
             );
             $simdikisatir = $elde;
           }

           if($sira == 1) {
             $sonuc['vakitler'][$simdikisatir]['imsak'] = $elde;
           }
           if($sira == 2) {
             $sonuc['vakitler'][$simdikisatir]['gunes'] = $elde;
           }
           if($sira == 3) {
             $sonuc['vakitler'][$simdikisatir]['ogle'] = $elde;
           }
           if($sira == 4) {
             $sonuc['vakitler'][$simdikisatir]['ikindi'] = $elde;
           }
           if($sira == 5) {
             $sonuc['vakitler'][$simdikisatir]['aksam'] = $elde;
           }
           if($sira == 6) {
             $sonuc['vakitler'][$simdikisatir]['yatsi'] = $elde;
           }

           $sira++;
         } // 2. foreach sonu
         $sira = 0;
       } // ilk foreach sonu
      $html->clear();
     } // if sonu
     return $sonuc;
   }
 }
