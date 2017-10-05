<?php

/**
 * Namaz - Diyanet İşleri Başkanlığından veri çekme sınıfı
 *
 * @author		Erdem ARSLAN <http://www.erdemarslan.com> <erdemsaid@gmail.com>
 * @copyright	Copyright (c) 2006-2017 erdemarslan.com
 * @link		http://www.erdemarslan.com/programlama/php-programlama/06-01-2014/563-namaz-vakitleri-php-sinifi.html
 * @version     6.0
 * @license		GPL v2.0
 */

require_once "simple_html_dom.php";

Class Namaz
{
	
	protected $ulke		= 2;
	protected $sehir	= 539;
	protected $ilce		= 9541;
	
	protected $cache_klasoru = 'cache';
	protected $cache;
	
	
	protected $ulke_isimleri = array();
	
	protected $ulkeler;
	protected $sehirler;
	protected $ilceler;
	
	protected $server = 'https://namazvakitleri.diyanet.gov.tr/tr-TR';
	
	
	protected $miladiaylar = array(
		0 => '',
		1 => 'Ocak',
		2 => 'Şubat',
		3 => 'Mart',
		4 => 'Nisan',
		5 => 'Mayıs',
		6 => 'Haziran',
		7 => 'Temmuz',
		8 => 'Ağustos',
		9 => 'Eylül',
		10 => 'Ekim',
		11 => 'Kasım',
		12 => 'Aralık'
	);

	protected $haftaningunleri = array(
		0 => '',
		1 => 'Pazartesi',
		2 => 'Salı',
		3 => 'Çarşamba',
		4 => 'Perşembe',
		5 => 'Cuma',
		6 => 'Cumartesi',
		7 => 'Pazar'

	);
		
	/**
     * Sınıfı yapılandırıcı fonksiyon
     *
     * @return mixed
     */
	public function __construct($cache_klasoru=NULL)
	{
		// Cache yolunu belirleyelim!
		$dosyayolu = dirname(__FILE__);
		$this->cache = is_null( $cache_klasoru ) === TRUE ? $dosyayolu . DIRECTORY_SEPARATOR . $this->cache_klasoru . DIRECTORY_SEPARATOR : $cache_klasoru;
		
		
		// cacheden ülke şehir ve ilçeleri oku!
		$this->ulkeler	= file_get_contents( $dosyayolu . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . 'ulkeler.ndb' );
		$this->sehirler	= file_get_contents( $dosyayolu . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . 'sehirler.ndb' );
		$this->ilceler	= file_get_contents( $dosyayolu . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . 'ilceler.ndb' );
		
	}
	
		
	#####################################################################################################################
	#####											VERİ VERME İŞLEMLERİ											#####
	#####################################################################################################################
	
	/**
     * Ülkesi verilen şehirleri çeker
     *
     * @param string Verisi çekilecek ülkeyi belirler
	 * @param string Verinin dışarıya nasıl çıktılanacağını belirtir
     * @return array Sonucu bir dizi olarak döndürür
     */
	public function ulkeler( $cikti='array' )
	{
		// ülkeleri arraya çevir
		$ulkeler = json_decode( $this->ulkeler, TRUE);
		$sonuc = array(
			'durum' => 'hata',
			'veri' => array()
		);
		
		foreach( $ulkeler as $key => $value )
		{
			$sonuc['durum'] = 'basarili';
			$sonuc['veri'][$key] = array_key_exists( $value, $this->ulke_isimleri ) === TRUE ? $this->ulke_isimleri[$value] : $value;
		}
		
		$yazdir = $cikti == 'array' ? $sonuc : json_encode( $sonuc );
		return $yazdir;
	}
	
	/**
     * Ülkesi verilen şehirleri çeker
     *
     * @param string Verisi çekilecek ülkeyi belirler
	 * @param string Verinin dışarıya nasıl çıktılanacağını belirtir
     * @return array Sonucu bir dizi olarak döndürür
     */
	public function sehirler( $ulke=NULL, $cikti='array' )
	{
		$ulke = is_null( $ulke ) === TRUE ? $this->ulke : $ulke;
		
		// şehirleri arraya çevir
		$sehirler = json_decode( $this->sehirler, TRUE);
		
		$sonuc = array(
			'durum' => 'hata',
			'veri' => array()
		);
		
		if ( array_key_exists( $ulke, $sehirler ) )
		{
			$sonuc['durum'] = 'basarili';
			$sonuc['veri'] = $sehirler[$ulke];
		}
		
		$yazdir = $cikti == 'array' ? $sonuc : json_encode( $sonuc );
		return $yazdir;
	}
	
	/**
     * Şehri verilen ilçeleri çeker
     *
     * @param string Verisi çekilecek şehri belirler
	 * @param string Verinin dışarıya nasıl çıktılanacağını belirtir
     * @return array Sonucu bir dizi olarak döndürür
     */
	public function ilceler( $sehir=NULL, $cikti='array' )
	{
		$sehir = is_null( $sehir ) === TRUE ? $this->sehir : $sehir;
		
		// ilçeleri alalım
		$ilceler = json_decode( $this->ilceler, TRUE );
		
		$sonuc = array(
			'durum' => 'hata',
			'veri' => array()
		);
		
		if( array_key_exists( $sehir, $ilceler ) )
		{
			$sonuc['durum'] = 'basarili';
			$sonuc['veri'] = $ilceler[$sehir];
		}
		
		
		$yazdir = $cikti == 'array' ? $sonuc : json_encode( $sonuc );
		return $yazdir;
	}
	
	/**
     * Verilen ülke ve şehir için vakitleri çeker
     *
     * @param string Verisi çekilecek ülkeyi belirler
	 * @param string Verisi çekilecek şehiri belirler
	 * @param string Verinin dışarıya nasıl çıktılanacağını belirtir
     * @return array Sonucu bir dizi olarak döndürür
     */
	public function vakit( $ulke=NULL, $sehir=NULL, $ilce=NULL, $cikti='array' )
	{
		$sehir = is_null( $sehir ) === TRUE ? $this->sehir : $sehir;
		$ulke = is_null( $ulke ) === TRUE ? $this->ulke : $ulke;
		if ($ulke == 2 || $ulke == 33 || $ulke == 52)
		{
			$ilce = is_null( $ilce ) === TRUE ? $this->ilce : $ilce;
		} else {
			$ilce = is_null( $ilce ) === TRUE ? $this->sehir : $ilce;
		}
		
		$cache_dosya_adi = 'vakit_' . $ulke . '_' . $sehir . '_' . $ilce;

		// Aranacak tarihi belirle!
		$tarih = date("d", time()) . " " . $this->miladiaylar[date("m", time())] . " " . date("Y", time()) . " " . $this->haftaningunleri[date("N", time())];


		if( $this->__cache_sor( $cache_dosya_adi, 1 ) )
		{
			$data = $this->__cache_oku( $cache_dosya_adi );
			// cache bellekte bugünün tarihi yoksa, hop belleği sil, yeniden çek ve devam et!

			$adata = json_decode($data, TRUE);
			if(array_key_exists( $tarih, $adata["veri"]["vakitler"])) {
				// tamam key var bişey yapma!
				$sonuc = $data;
			} else {
				$veri = $this->al_vakitler( $ulke, $sehir, $ilce );
			
				if( $veri['durum'] == 'basarili' )
				{
					$this->__cache_yaz( $cache_dosya_adi , json_encode($veri) );
				}
				$sonuc = json_encode( $veri );
			}
		} else {
			$veri = $this->al_vakitler( $ulke, $sehir, $ilce );
			
			if( $veri['durum'] == 'basarili' )
			{
				$this->__cache_yaz( $cache_dosya_adi , json_encode($veri) );
			}
			$sonuc = json_encode( $veri );
		}
		$yazdir = $cikti == 'json' ? $sonuc : json_decode( $sonuc, TRUE );
		return $yazdir;
	}
	
	/**
     * Cache klasörünün içini temizler
     *
     * @return void
     */
	public function cache_temizle()
	{
		array_map('unlink', glob( $this->cache . '*.ndb' ));
	}
	
	
	#####################################################################################################################
	#####												CACHE İŞLEMLERİ												#####
	#####################################################################################################################
	
	/**
     * Cache dosyası var mı yok mu, varsa süresi geçerli mi onu kontrol eder!
     *
     * @param string Dosyanın adı
	 * @param integer 0 - süresiz, 1 - 1 gün süreli
     * @return boolean Sonuç TRUE ya da FALSE olarak döner.
     */
	private function __cache_sor( $dosya, $gecerli=0 )
	{
		if ( file_exists( $this->cache .  $dosya . '.ndb' ) AND is_readable( $this->cache . $dosya . '.ndb' ) )
		{
			if ( $gecerli == 0 )
			{
				return TRUE;
			} else {
				$dosya_zamani = date( 'dmY', filemtime( $this->cache . $dosya . '.ndb' ) );
				$bugun = date( 'dmY', time() );
				
				if ( $dosya_zamani == $bugun )
				{
					return TRUE;
				} else {
					return FALSE;
				}
			}
		} else {
			return FALSE;
		}
	}
	
	/**
     * Cache dosyasından okur
     *
     * @param string Dosyanın adı
     * @return json Sonuç json türünde geri döner
     */
	private function __cache_oku( $dosya )
	{
		return file_get_contents( $this->cache . $dosya . '.ndb' );
	}
	
	/**
     * Cache dosyasına yazar
     *
     * @param string Dosyanın adı
	 * @param string Dosyaya kaydedilecek veri
     * @return mixed Sonuç dönmez
     */
	private function __cache_yaz( $dosya , $veri )
	{
		$fp = fopen( $this->cache . $dosya . '.ndb', "w" );
		fwrite( $fp, $veri );
		fclose( $fp );
		return;
	}
	
		
	
	#####################################################################################################################
	#####											VERİ ÇEKME İŞLEMLERİ											#####
	#####################################################################################################################
	
	
	/**
     * Verilen ülke ve şehir için vakitleri çeker
     *
     * @param string Verisi çekilecek ülkeyi belirler
	 * @param string Verisi çekilecek şehiri belirler
     * @return array Sonucu bir dizi olarak döndürür
     */
	private function al_vakitler( $ulke=NULL, $sehir=NULL, $ilce = NULL )
	{
		$ulke = is_null( $ulke ) === TRUE ? $this->ulke : $ulke;
		$sehir = is_null( $sehir ) === TRUE ? $this->ilce : $sehir;
		if ($ulke == 2 || $ulke == 33 || $ulke == 52)
		{
			$ilce = is_null( $ilce ) === TRUE ? $this->ilce : $ilce;
		} else {
			$ilce = is_null( $ilce ) === TRUE ? $sehir : $ilce;
		}
		
		// Adresi belirle
		$url =  $this->server . '/Home/PrayerTimePdfCreate/' . $ilce . '?vakit=Haftalik';
		// Sunucudan verileri çek!
		$sonuc = $this->__curl( $url );
		
		return $sonuc;
	}

	
	/**
     * Diyanetten verileri almak için cURL metodu - Özeldir
     *
     * @param string Bağlantı adresini verir
     * @param string Başlantı için gerekli verileri verir
	 * @param boolean Bu bağlantının POST metodu ile yapılıp yapılmayacağını belirtir
     * @return array sonucu bir dizi olarak döndürür
     */
	private function __curl($url)
	{
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_HTTPGET, TRUE );
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0' );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
		curl_setopt( $ch, CURLOPT_REFERER, "https://namazvakitleri.diyanet.gov.tr/tr-TR" );
		
		$veri = curl_exec( $ch );
		$bilgi = curl_getinfo( $ch );
		
		if( $bilgi['http_code'] == 200 ) // POST durumunda geçerli veri dönerse HTTP_RESPONSE_CODE = 200 oluyor!
		{
			$sonuc = array(
				'durum'	=> 'basarili',
				'veri'	=> $this->_html_ayikla($veri)
			);
		}
		else
		{
			$sonuc = array(
				'durum'	=> 'hata',
				'veri'	=> array()
			);
		}
		curl_close( $ch );
		return $sonuc;
	}


	/**
     * Gelen HTML veriyi ayıklar - Özeldir
     *
     * @param string RAW HTTP verisi (HTML çıktısı)
     * @return array sonucu bir dizi olarak döndürür
     */

	private function _html_ayikla($veri)
	{
		// HTML yi ayıkla
		$sonuc = array(
			'ulke' => '',
			'sehir' => '',
			'vakitler' => array()
		);

		$html = str_get_html($veri);

		$sonuc["ulke"] = trim($html->find("h4", 0)->plaintext);
		$sonuc["sehir"] = trim($html->find("h4", 1)->plaintext);


		foreach ($html->find('tr') as $tr) {
			$sira = 0;
			$simdiki_satir = "";
			foreach($tr->find('td') as $td) {
				$elde = trim($td->plaintext);
				if($sira == 0) {
					$sonuc["vakitler"][$elde] = array(
						'tarih' => $elde,
						'hicri' => "",
						'imsak' => "",
						'gunes' => "",
						'ogle'	=> "",
						'ikindi' => "",
						'aksam'	=> "",
						'yatsi'	=> "",
						'kible'	=> ''
					);
					$simdikisatir = $elde;
				}

				if($sira == 1) {
					$sonuc["vakitler"][$simdikisatir]["hicri"] = $elde;
				}

				if($sira == 2){
					$sonuc["vakitler"][$simdikisatir]["imsak"] = $elde;
				}

				if($sira == 3) {
					$sonuc["vakitler"][$simdikisatir]["gunes"] = $elde;
				}

				if($sira == 4) {
					$sonuc["vakitler"][$simdikisatir]["ogle"] = $elde;
				}

				if($sira == 5) {
					$sonuc["vakitler"][$simdikisatir]["ikindi"] = $elde;
				}

				if($sira == 6) {
					$sonuc["vakitler"][$simdikisatir]["aksam"] = $elde;
				}

				if($sira == 7) {
					$sonuc["vakitler"][$simdikisatir]["yatsi"] = $elde;
				}

				if($sira == 8) {
					$sonuc["vakitler"][$simdikisatir]["kible"] = $elde;
				}
				$sira = $sira + 1;
			}

			$sira = 0;
		}

		return $sonuc;
	}
	
} // Sınıf Bitti