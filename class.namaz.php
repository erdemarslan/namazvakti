<?php
/**
 * Namaz - Diyanet İşleri Başkanlığından veri çekme sınıfı
 *
 * @author		Erdem ARSLAN <http://www.erdemarslan.com> <erdemsaid@gmail.com>
 * @copyright	Copyright (c) 2006-2017 erdemarslan.com
 * @link		http://www.eralabs.net/
 * @version     8.0
 * @license		GPL v2.0
 */

require_once "class.simple_html_dom.php";
require_once "class.hicri.php";


Class Namaz {

	// Sınıf içerisinde işlenecek veriler
	protected $veritabani, $adresler, $hicriSinif;

	// Ülke İsimlerinin dışarıdan alınabilmesini sağlayan değişken. Wordpress eklentisi için gerekli olacak!
	public $ulkeIsimleri = array();
	public $sehirIsimleri = array();
   public $ilceIsimleri = array();


	// Cache ile ilgili veriler
	protected $cache;
	protected $cacheKlasorYolu = "cache";

	// Miladi Ay isimleri. Dışarıdan değiştirilebilir. O yüzden diğer dillere tercüme de edilebilir!
	public $miladiAylar = array(
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
		12  =>  'Aralık'
		);

	// Haftanın gün isimler. Aynı miladi aylar gibi tercüme edilebilir.
	public $haftaninGunleri = array(
		1  => 'Pazartesi',
		2  => 'Salı',
		3  => 'Çarşamba',
		4  => 'Perşembe',
		5  => 'Cuma',
		6  => 'Cumartesi',
		7  => 'Pazar'
		);

   // Hicri aylar. Aynı miladi aylar ve günler gibi bunlar da dışarıdan erişilip tercüme edilebilir!
	public $hicriAylar = array(
		1  =>  "Muharrem",
		2  =>  "Safer",
		3  =>  "Rebiü'l-Evvel",
		4  =>  "Rebiü'l-Ahir",
		5  =>  "Cemaziye'l-Evvel",
		6  =>  "Cemaziye'l-Ahir",
		7  =>  "Recep",
		8  =>  "Şaban",
		9  =>  "Ramazan",
		10  =>  "Şevval",
		11  =>  "Zi'l-ka'de",
		12  =>  "Zi'l-Hicce"
		);

	############################################################################
   #                          GENEL FONKSIYONLAR                              #
   ############################################################################


	// Yapılandırıcı Fonksiyon
	public function __construct($cacheklasoru = NULL) {
		// Bu dosyanın konumu
		$dosyaYolu = (__DIR__);

		// Cache klasörünü tanımlayalım tam olarak neresi diye! Daha sonradan değiştirilebilir
		$this->cache = is_null($cacheklasoru) ? $dosyaYolu . DIRECTORY_SEPARATOR . $this->cacheKlasorYolu . DIRECTORY_SEPARATOR : $cacheklasoru;

		// Veritabanından listeleri alalım!
      $this->veritabani = json_decode(file_get_contents($dosyaYolu . DIRECTORY_SEPARATOR . "db" . DIRECTORY_SEPARATOR . "yerler.ndb"), true);

		// Hicri takvim sınıfını ekleyelim!
		$this->hicriSinif = new HijriDateTime();
	}

   // Yıkıcı Fonksiyon
   public function __destruct() {
      $this->cache = null;
      $this->veritabani = null;
      $this->hicriSinif = null;
   }

	// Cache klasörünü değiştirir!
	public function cacheKlasoru($cacheklasoru) {
		$this->cache = $cacheklasoru;
		return $this;
	}

	// Ülke listesini verir!
	public function ulkeler($cikti = "array") {
      // sonuç değişkenini ayarla
		$sonuc = array(
			'durum' => 'hata',
			'veri' => array()
		);

		// Veritabanından sadece ülke adlarını ve isimlerini döndür!
		foreach ($this->veritabani as $ulke_id => $bilgi) {
			$sonuc['durum'] = 'basarili';
			$sonuc['veri'][$ulke_id] = array_key_exists($bilgi['ulke_adi'], $this->ulkeIsimleri) ? $this->ulkeIsimleri[$bilgi['ulke_adi']] : $bilgi['ulke_adi'];
		}

		// fonksiyon dışına aktar!
		return ($cikti == 'array' ? $sonuc : json_encode($sonuc));
	}

	// Şehirlerin listesini verir! Ülke id verilmesi gerekir!
	public function sehirler($ulke, $cikti = "array") {

		$sonuc = array(
			'durum' => 'hata',
         'ilce' => false,
			'veri' => array()
		);

		if(array_key_exists($ulke, $this->veritabani)) {
			$sonuc['durum'] = 'basarili';

         $ulke = $this->veritabani[$ulke];
         if($ulke['ilce_listesi_varmi']) {
            foreach ($ulke['sehirler'] as $sehir_id => $bilgi) {
               $sonuc['veri'][$sehir_id] = array_key_exists($bilgi['sehir_adi'], $this->sehirIsimleri) ? $this->sehirIsimleri[$bilgi['sehir_adi']] : $bilgi['sehir_adi'];
            }
         } else {
            $sonuc['ilce'] = true;

            foreach ($ulke['sehirler'] as $sehir) {
               foreach($sehir['ilceler'] as $ilce_id => $ilce_adi) {
                  $sonuc['veri'][$ilce_id] = array_key_exists($ilce_adi, $this->sehirIsimleri) ? $sehirIsimleri[$ilce_adi] : $ilce_adi;
               }
            }
         }
		}

		return ($cikti == 'array' ? $sonuc : json_encode($sonuc));
	}

	// İlçelerin listesini verir! Ülke ve Şehir id verilmesi gerekir.
	public function ilceler($ulke, $sehir, $cikti = 'array') {
		
		$sonuc = array(
			'durum' => 'hata',
			'veri' => array()
		);

      $ulke = $this->veritabani[$ulke];

      if($ulke['ilce_listesi_varmi']) {
         if(array_key_exists($sehir, $ulke['sehirler'])) {
            $sonuc['durum'] = 'basarili';
            foreach ($ulke['sehirler'][$sehir]['ilceler'] as $ilce_id => $isim) {
               $sonuc['veri'][$ilce_id] = array_key_exists($isim, $this->ilceIsimleri) ? $this->ilceIsimleri[$isim] : $isim;
            }
         }
      }

		return ($cikti == 'array' ? $sonuc : json_encode($sonuc));
	}

	// cache belleği temizler
	public function cacheTemizle() {
		array_map('unlink', glob($this->cache . "*.ndb"));
	}

	// Tek vakti al
	public function vakit($sehir, $cikti = 'array') {

      $sonuc = array(
         'durum' => 'hata',
         'veri' => array()
      );

      // Yer bilgisini biz buluyoruz. Database gerekliliğini ortadan kaldıralım şimdilik!
      $yer = $this->_yerBilgisi($sehir);

      $cacheDosyasi = "cache_" . $yer['sehir_id'] . ".ndb";

      // bugünü alalım. Lazım olacak!
      $bugun = date("d.m.Y", time());

      if($this->_cacheSor($cacheDosyasi)) {
         // cache bellekte var! Hadi şimdi irdeleyelim!
         $veri = json_decode($this->_cacheOku($cacheDosyasi), true);

         if(array_key_exists($bugun, $veri['veri']['vakitler'])) {
            $sonuc = $veri;
         } else {
            // bugün yok la içinde! hadi tekrar çekelim!
            $sunucu = $this->_sunucudanVeriCek($yer['url']);

            if($sunucu['durum'] == 'basarili' && count($sunucu['veri']['vakitler']) > 0) {
               // herşey yolunda gitmiş çok şükür!
               $icerik = array(
                  'ulke' => $yer['ulke'],
                  'sehir' => $yer['sehir'],
                  'ilce' => $yer['ilce'],
                  'yer_adi' => $yer['uzun_adi'],
                  'vakitler' => $sunucu['veri']['vakitler']
               );

               $sonuc['durum'] = 'basarili';
               $sonuc['veri'] = $icerik;

               // cache belleğe de yazalım ayıp olmasın!
               $this->_cacheYaz($cacheDosyasi, json_encode($sonuc));
            }
         }
      } else {
         // cache bellekte veri yok!
         // ozaman sunucudan çek! Yaz Yerine koy!
         $sunucu = $this->_sunucudanVeriCek($yer['url']);

         if($sunucu['durum'] == 'basarili' && count($sunucu['veri']['vakitler']) > 0) {
            // herşey yolunda gitmiş çok şükür!
            $icerik = array(
               'ulke' => $yer['ulke'],
               'sehir' => $yer['sehir'],
               'ilce' => $yer['ilce'],
               'yer_adi' => $yer['uzun_adi'],
               'vakitler' => $sunucu['veri']['vakitler']
            );

            $sonuc['durum'] = 'basarili';
            $sonuc['veri'] = $icerik;

            // cache belleğe de yazalım ayıp olmasın!
            $this->_cacheYaz($cacheDosyasi, json_encode($sonuc));
         }
      } // cache dosyası yoktu biz de oluşturduk sonu!

      if($sonuc['durum'] == 'basarili') {
         $sonuc['veri']['vakit'] = $sonuc['veri']['vakitler'][$bugun];
         unset($sonuc['veri']['vakitler']);
      }
      return ($cikti == 'array' ? $sonuc : json_encode($sonuc));
	}


	// Bütün vakitleri alır. Cacheden okumaz! Sadece cacheye yazar!
	public function vakitler($sehir, $cikti = 'array') {

      $sonuc = array(
         'durum' => 'hata',
         'veri' => array()
      );

      // Yer bilgisini biz buluyoruz. Database gerekliliğini ortadan kaldıralım şimdilik!
      $yer = $this->_yerBilgisi($sehir);

      $cacheDosyasi = "cache_" . $yer['sehir_id'] . ".ndb";

      $sunucu = $this->_sunucudanVeriCek($yer['url']);

      if($sunucu['durum'] == 'basarili' && count($sunucu['veri']['vakitler']) > 0) {
         // herşey yolunda gitmiş çok şükür!
         $icerik = array(
            'ulke' => $yer['ulke'],
            'sehir' => $yer['sehir'],
            'ilce' => $yer['ilce'],
            'yer_adi' => $yer['uzun_adi'],
            'vakitler' => $sunucu['veri']['vakitler']
         );

         $sonuc['durum'] = 'basarili';
         $sonuc['veri'] = $icerik;

         // cache belleğe de yazalım ayıp olmasın!
         $this->_cacheYaz($cacheDosyasi, json_encode($sonuc));
      }

      return ($cikti == 'array' ? $sonuc : json_encode($sonuc));
	}


	############################################################################
	#                          ÖZEL  FONKSIYONLAR                              #
	############################################################################

   // Yer bilgisini verir!
   private function _yerBilgisi($sehir) {
      $adresler = json_decode(file_get_contents((__DIR__) . DIRECTORY_SEPARATOR . "db" . DIRECTORY_SEPARATOR . "adresler.ndb"), true);
      $veri = array();
      if(array_key_exists($sehir, $adresler)) {
         $veri = $adresler[$sehir];
      }
      $adresler = null;
      unset($adresler);
      return $veri;
   }
	// cache bellekte dosya var mı diye sorar!
	private function _cacheSor($dosyaAdi, $gunluk = FALSE) {
		// cache bellekte veri var mı diye sorar!
      $dosya = $this->cache . $dosyaAdi;
      if(file_exists($dosya) && is_readable($dosya)) {
         if($gunluk) {
            $bugun = date("dmY", time());
            $dosyaZamani = date("dmY", filemtime($dosya));
            if($bugun == $dosyaZamani) {
               return true;
            } else {
               return false;
            }
         } else {
            return true;
         }
      } else {
         return false;
      }
	}

	// cache belleğe yaz!
	private function _cacheYaz($dosyaAdi, $jsonVeri) {
		// cache belleğe veri yazar!
      $dosya = $this->cache . $dosyaAdi;
      $fp = fopen($dosya,"w");
      fwrite($fp, $jsonVeri);
      fclose($fp);
      return;
	}

	// cache belleği okur!
	private function _cacheOku($dosyaAdi) {
		// cache bellekteki dosyayı okur
      $dosya = $this->cache . $dosyaAdi;
      return file_get_contents($dosya);
	}

   // Sunucudan veri çeker!
   private function _sunucudanVeriCek($url) {

      $fullURL = "http://namazvakitleri.diyanet.gov.tr" . $url;

      $ch = curl_init();
      curl_setopt( $ch, CURLOPT_HTTPGET, true );
      curl_setopt( $ch, CURLOPT_URL, $fullURL );
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
      curl_setopt( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/61.0' );
      curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
      curl_setopt( $ch, CURLOPT_REFERER, "http://namazvakitleri.diyanet.gov.tr/tr-TR" );

      $veri = curl_exec($ch);
      $bilgi = curl_getinfo($ch);


      $sonuc = array(
         'durum' => 'hata',
         'veri' => array()
      );

      if($bilgi['http_code'] == 200) {
         $sonuc['durum'] = 'basarili';
         $sonuc['veri'] = $this->_htmlOku($veri);
      }
      curl_close($ch);

      $veri = null;
      $bilgi = null;
      unset($veri);
      unset($bilgi);
      return $sonuc;
   }

   // Uzaktan gelen html verisini anlamlı verilere döndürür!
   private function _htmlOku($veri) {
      $sonuc = array(
         'vakitler' => array()
      );

      //print_r($veri);

      // Gelen veri var mı?
      if(strpos($veri, "<div id = \"tab-1\" class=\"w3-container w3-border nv-tab-content\" style=\"display:none\">") !== FALSE) {
         $bolme1 = explode("<div id = \"tab-1\" class=\"w3-container w3-border nv-tab-content\" style=\"display:none\">", $veri);
         $elimizde_kalan = explode("</section>", $bolme1[1]);
         $html = str_get_html($elimizde_kalan[0]);

         foreach ($html->find('tr') as $tr) {
            $sira = 0;
            $simdikiSatir = "";

            foreach ($tr->find('td') as $td) {
               $elde = trim($td->plaintext);

               if($sira == 0) {
                  $tarih = $this->_kisaTarih($elde);
                  //$tarih = $elde;

                  $sonuc['vakitler'][$tarih] = array(
                     'tarih' => $tarih,
                     'tarih_uzun' => $elde,
                     'hicri' => $this->_hicriTarih($tarih),
                     'hicri_uzun' => $this->_hicriTarih($tarih, true),
                     'imsak' => '',
                     'gunes' => '',
                     'ogle' => '',
                     'ikindi' => '',
                     'aksam' => '',
                     'yatsi' => ''
                  );
                  $simdikiSatir = $tarih;
               } // sira = 0

               if($sira == 1) {
                  $sonuc['vakitler'][$simdikiSatir]['imsak'] = $elde;
               }
               if($sira == 2) {
                  $sonuc['vakitler'][$simdikiSatir]['gunes'] = $elde;
               }
               if($sira == 3) {
                  $sonuc['vakitler'][$simdikiSatir]['ogle'] = $elde;
               }
               if($sira == 4) {
                  $sonuc['vakitler'][$simdikiSatir]['ikindi'] = $elde;
               }
               if($sira == 5) {
                  $sonuc['vakitler'][$simdikiSatir]['aksam'] = $elde;
               }
               if($sira == 6) {
                  $sonuc['vakitler'][$simdikiSatir]['yatsi'] = $elde;
               }
               $sira++;
            } // ikinci foreach sonu (td olan)
            $sira = 0;
         } // ilk foreach sonu (tr olan)
         $html->clear();
      } // gelen veri sonu (if sonu)
      return $sonuc;
   }

   // 23 Nisan 1923 Pazartesi şeklindeki tarihi 23.04.1923 şeklinde döndürür
   private function _kisaTarih($uzuntarih=null) {
      if(is_null($uzuntarih)) {
         return date("d.m.Y", time());
      }

      $parca = explode(" ", $uzuntarih);
      $aylar = array_flip($this->miladiAylar);

      $ay = "00";
      $gun = $parca[0];
      $yil = $parca[2];

      if($aylar[html_entity_decode($parca[1])] > 9) {
         $ay = $aylar[html_entity_decode($parca[1])];
      } else {
         $ay = "0" . $aylar[html_entity_decode($parca[1])];
      }

      return $gun . "." . $ay . "." . $yil;
   }

   // Miladi tarihi  hicri tarihe çevirir.
   private function _hicriTarih($tarih, $uzun = false) {
      if ($tarih === null) $tarih = date('d.m.Y',time());
      $t = explode('.',$tarih);
      $bugun = $this->hicriSinif->GeToHijr($t[0], $t[1], $t[2]);
      $sonuc = "";
      if($uzun) {
         $sonuc = $bugun['day'] . ' ' . $this->hicriAylar[$bugun['month']] . ' ' . $bugun['year'];
      } else {

         $gun = $bugun['day'];
         $ay = $bugun['month'];

         if($gun < 10) {
            $gun = "0" . $gun;
         }

         if($ay < 10) {
            $ay = "0" . $ay;
         }

         $sonuc = $gun . '.' . $ay . '.' . $bugun['year'];
      }
      return $sonuc;
   }
}