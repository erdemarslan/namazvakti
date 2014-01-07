<?php

/**
 * Namaz - Diyanet İşleri Başkanlığından veri çekme sınıfı
 *
 * @author		Erdem ARSLAN (http://www.erdemarslan.com/)
 * @copyright	Copyright (c) 2014 erdemarslan.com
 * @link		http://www.erdemarslan.com/programlama/php-programlama/06-01-2014/563-namaz-vakitleri-php-sinifi.html
 * @version     4.0
 * @license		GPL v2.0
 */

Class Namaz
{
	
	protected $ulke		= 'TURKIYE';
	protected $sehir	= 'CANAKKALE';
	protected $ilce		= 'CANAKKALE';
	
	protected $cache_klasoru = 'cache';
	protected $cache;
	
	protected $ulkeler = '[{"text":"ABD","value":"ABD"},{"text":"AFGANISTAN","value":"AFGANISTAN"},{"text":"ALMANYA","value":"ALMANYA"},{"text":"ANDORRA","value":"ANDORRA"},{"text":"ANGOLA","value":"ANGOLA"},{"text":"ANGUILLA","value":"ANGUILLA"},{"text":"ANTIGUA VE BARBUDA","value":"ANTIGUA VE BARBUDA"},{"text":"ARJANTIN","value":"ARJANTIN"},{"text":"ARNAVUTLUK","value":"ARNAVUTLUK"},{"text":"ARUBA","value":"ARUBA"},{"text":"AVUSTRALYA","value":"AVUSTRALYA"},{"text":"AVUSTURYA","value":"AVUSTURYA"},{"text":"AZERBAYCAN","value":"AZERBAYCAN"},{"text":"BAHAMALAR","value":"BAHAMALAR"},{"text":"BAHREYN","value":"BAHREYN"},{"text":"BANGLADES","value":"BANGLADES"},{"text":"BARBADOS","value":"BARBADOS"},{"text":"BELARUS","value":"BELARUS"},{"text":"BELCIKA","value":"BELCIKA"},{"text":"BELIZE","value":"BELIZE"},{"text":"BENIN","value":"BENIN"},{"text":"BERMUDA","value":"BERMUDA"},{"text":"BIRLESIK ARAP EMIRLIGI","value":"BIRLESIK ARAP EMIRLIGI"},{"text":"BOLIVYA","value":"BOLIVYA"},{"text":"BOSNA HERSEK","value":"BOSNA HERSEK"},{"text":"BOTSVANA","value":"BOTSVANA"},{"text":"BREZILYA","value":"BREZILYA"},{"text":"BRUNEI","value":"BRUNEI"},{"text":"BULGARISTAN","value":"BULGARISTAN"},{"text":"BURKINA FASO","value":"BURKINA FASO"},{"text":"BURMA (MYANMAR)","value":"BURMA (MYANMAR)"},{"text":"BURUNDI","value":"BURUNDI"},{"text":"BUTAN","value":"BUTAN"},{"text":"CAD","value":"CAD"},{"text":"CECENISTAN","value":"CECENISTAN"},{"text":"CEK CUMHURIYETI","value":"CEK CUMHURIYETI"},{"text":"CEZAYIR","value":"CEZAYIR"},{"text":"CIBUTI","value":"CIBUTI"},{"text":"CIN","value":"CIN"},{"text":"DANIMARKA","value":"DANIMARKA"},{"text":"DEMOKRATIK KONGO CUMHURIYETI","value":"DEMOKRATIK KONGO CUMHURIYETI"},{"text":"DOGU TIMOR","value":"DOGU TIMOR"},{"text":"DOMINIK","value":"DOMINIK"},{"text":"DOMINIK CUMHURIYETI","value":"DOMINIK CUMHURIYETI"},{"text":"EKVATOR","value":"EKVATOR"},{"text":"EKVATOR GINESI","value":"EKVATOR GINESI"},{"text":"EL SALVADOR","value":"EL SALVADOR"},{"text":"ENDONEZYA","value":"ENDONEZYA"},{"text":"ERITRE","value":"ERITRE"},{"text":"ERMENISTAN","value":"ERMENISTAN"},{"text":"ESTONYA","value":"ESTONYA"},{"text":"ETYOPYA","value":"ETYOPYA"},{"text":"FAS","value":"FAS"},{"text":"FIJI","value":"FIJI"},{"text":"FILDISI SAHILI","value":"FILDISI SAHILI"},{"text":"FILIPINLER","value":"FILIPINLER"},{"text":"FILISTIN","value":"FILISTIN"},{"text":"FINLANDIYA","value":"FINLANDIYA"},{"text":"FRANSA","value":"FRANSA"},{"text":"GABON","value":"GABON"},{"text":"GAMBIYA","value":"GAMBIYA"},{"text":"GANA","value":"GANA"},{"text":"GINE","value":"GINE"},{"text":"GRANADA","value":"GRANADA"},{"text":"GRONLAND","value":"GRONLAND"},{"text":"GUADELOPE","value":"GUADELOPE"},{"text":"GUAM ADASI","value":"GUAM ADASI"},{"text":"GUATEMALA","value":"GUATEMALA"},{"text":"GUNEY AFRIKA","value":"GUNEY AFRIKA"},{"text":"GUNEY KORE","value":"GUNEY KORE"},{"text":"GURCISTAN","value":"GURCISTAN"},{"text":"GUYANA","value":"GUYANA"},{"text":"HAITI","value":"HAITI"},{"text":"HINDISTAN","value":"HINDISTAN"},{"text":"HIRVATISTAN","value":"HIRVATISTAN"},{"text":"HOLLANDA","value":"HOLLANDA"},{"text":"HOLLANDA ANTILLERI","value":"HOLLANDA ANTILLERI"},{"text":"HONDURAS","value":"HONDURAS"},{"text":"HONG KONG","value":"HONG KONG"},{"text":"INGILTERE","value":"INGILTERE"},{"text":"IRAK","value":"IRAK"},{"text":"IRAN","value":"IRAN"},{"text":"IRLANDA","value":"IRLANDA"},{"text":"ISPANYA","value":"ISPANYA"},{"text":"ISRAIL","value":"ISRAIL"},{"text":"ISVEC","value":"ISVEC"},{"text":"ISVICRE","value":"ISVICRE"},{"text":"ITALYA","value":"ITALYA"},{"text":"IZLANDA","value":"IZLANDA"},{"text":"JAMAIKA","value":"JAMAIKA"},{"text":"JAPONYA","value":"JAPONYA"},{"text":"KAMBOCYA","value":"KAMBOCYA"},{"text":"KAMERUN","value":"KAMERUN"},{"text":"KANADA","value":"KANADA"},{"text":"KARADAG","value":"KARADAG"},{"text":"KATAR","value":"KATAR"},{"text":"KAZAKISTAN","value":"KAZAKISTAN"},{"text":"KENYA","value":"KENYA"},{"text":"KIRGIZISTAN","value":"KIRGIZISTAN"},{"text":"KIRGIZISTAN","value":"KIRGIZISTAN"},{"text":"KOLOMBIYA","value":"KOLOMBIYA"},{"text":"KOMORLAR","value":"KOMORLAR"},{"text":"KOSOVA","value":"KOSOVA"},{"text":"KOSTARIKA","value":"KOSTARIKA"},{"text":"KUBA","value":"KUBA"},{"text":"KUDUS","value":"KUDUS"},{"text":"KUVEYT","value":"KUVEYT"},{"text":"KUZEY KIBRIS","value":"KUZEY KIBRIS"},{"text":"KUZEY KORE","value":"KUZEY KORE"},{"text":"LAOS","value":"LAOS"},{"text":"LESOTO","value":"LESOTO"},{"text":"LETONYA","value":"LETONYA"},{"text":"LIBERYA","value":"LIBERYA"},{"text":"LIBYA","value":"LIBYA"},{"text":"LIECHTENSTEIN","value":"LIECHTENSTEIN"},{"text":"LITVANYA","value":"LITVANYA"},{"text":"LUBNAN","value":"LUBNAN"},{"text":"LUKSEMBURG","value":"LUKSEMBURG"},{"text":"MACARISTAN","value":"MACARISTAN"},{"text":"MADAGASKAR","value":"MADAGASKAR"},{"text":"MAKAO","value":"MAKAO"},{"text":"MAKEDONYA","value":"MAKEDONYA"},{"text":"MALAVI","value":"MALAVI"},{"text":"MALDIVLER","value":"MALDIVLER"},{"text":"MALEZYA","value":"MALEZYA"},{"text":"MALI","value":"MALI"},{"text":"MALTA","value":"MALTA"},{"text":"MARTINIK","value":"MARTINIK"},{"text":"MAURITIUS ADASI","value":"MAURITIUS ADASI"},{"text":"MAYOTTE","value":"MAYOTTE"},{"text":"MEKSIKA","value":"MEKSIKA"},{"text":"MIKRONEZYA","value":"MIKRONEZYA"},{"text":"MISIR","value":"MISIR"},{"text":"MOGOLISTAN","value":"MOGOLISTAN"},{"text":"MOLDAVYA","value":"MOLDAVYA"},{"text":"MONAKO","value":"MONAKO"},{"text":"MONTSERRAT (U.K.)","value":"MONTSERRAT (U.K.)"},{"text":"MORITANYA","value":"MORITANYA"},{"text":"MOZAMBIK","value":"MOZAMBIK"},{"text":"NAMBIYA","value":"NAMBIYA"},{"text":"NEPAL","value":"NEPAL"},{"text":"NIJER","value":"NIJER"},{"text":"NIJERYA","value":"NIJERYA"},{"text":"NIKARAGUA","value":"NIKARAGUA"},{"text":"NIUE","value":"NIUE"},{"text":"NORVEC","value":"NORVEC"},{"text":"ORTA AFRIKA CUMHURIYETI","value":"ORTA AFRIKA CUMHURIYETI"},{"text":"OZBEKISTAN","value":"OZBEKISTAN"},{"text":"PAKISTAN","value":"PAKISTAN"},{"text":"PALAU","value":"PALAU"},{"text":"PANAMA","value":"PANAMA"},{"text":"PAPUA YENI GINE","value":"PAPUA YENI GINE"},{"text":"PARAGUAY","value":"PARAGUAY"},{"text":"PERU","value":"PERU"},{"text":"PITCAIRN ADASI","value":"PITCAIRN ADASI"},{"text":"POLONYA","value":"POLONYA"},{"text":"PORTEKIZ","value":"PORTEKIZ"},{"text":"PORTO RIKO","value":"PORTO RIKO"},{"text":"REUNION","value":"REUNION"},{"text":"ROMANYA","value":"ROMANYA"},{"text":"RUANDA","value":"RUANDA"},{"text":"RUSYA","value":"RUSYA"},{"text":"SAMOA","value":"SAMOA"},{"text":"SENEGAL","value":"SENEGAL"},{"text":"SEYSEL ADALARI","value":"SEYSEL ADALARI"},{"text":"SILI","value":"SILI"},{"text":"SINGAPUR","value":"SINGAPUR"},{"text":"SIRBISTAN","value":"SIRBISTAN"},{"text":"SLOVAKYA","value":"SLOVAKYA"},{"text":"SLOVENYA","value":"SLOVENYA"},{"text":"SOMALI","value":"SOMALI"},{"text":"SRI LANKA","value":"SRI LANKA"},{"text":"SUDAN","value":"SUDAN"},{"text":"SURINAM","value":"SURINAM"},{"text":"SURIYE","value":"SURIYE"},{"text":"SUUDI ARABISTAN","value":"SUUDI ARABISTAN"},{"text":"SVALBARD","value":"SVALBARD"},{"text":"SVAZILAND","value":"SVAZILAND"},{"text":"TACIKISTAN","value":"TACIKISTAN"},{"text":"TANZANYA","value":"TANZANYA"},{"text":"TAYLAND","value":"TAYLAND"},{"text":"TAYVAN","value":"TAYVAN"},{"text":"TOGO","value":"TOGO"},{"text":"TONGA","value":"TONGA"},{"text":"TRINIDAT VE TOBAGO","value":"TRINIDAT VE TOBAGO"},{"text":"TUNUS","value":"TUNUS"},{"text":"TURKIYE","value":"TURKIYE"},{"text":"TURKMENISTAN","value":"TURKMENISTAN"},{"text":"UGANDA","value":"UGANDA"},{"text":"UKRAYNA","value":"UKRAYNA"},{"text":"UKRAYNA-KIRIM","value":"UKRAYNA-KIRIM"},{"text":"UMMAN","value":"UMMAN"},{"text":"URDUN","value":"URDUN"},{"text":"URUGUAY","value":"URUGUAY"},{"text":"VANUATU","value":"VANUATU"},{"text":"VATIKAN","value":"VATIKAN"},{"text":"VENEZUELA","value":"VENEZUELA"},{"text":"VIETNAM","value":"VIETNAM"},{"text":"YEMEN","value":"YEMEN"},{"text":"YENI KALEDONYA","value":"YENI KALEDONYA"},{"text":"YENI ZELLANDA","value":"YENI ZELLANDA"},{"text":"YESIL BURUN","value":"YESIL BURUN"},{"text":"YUNANISTAN","value":"YUNANISTAN"},{"text":"ZAMBIYA","value":"ZAMBIYA"},{"text":"ZIMBABVE","value":"ZIMBABVE"}]';
	
	protected $hicriaylar = array(
		0 => '',
		1 => 'Muharrem',
		2 => 'Safer',
		3 => "Rebiü'l-Evvel",
		4 => "Rebiü'l-Ahir",
		5 => "Cemaziye'l-Evvel",
		6 => "Cemaziye'l-Ahir",
		7 => 'Recep',
		8 => 'Şaban',
		9 => 'Ramazan',
		10 => 'Sevval',
		11 => "Zi'l-ka'de",
		12 => "Zi'l-Hicce"
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
		$sonuc = $this->ulkeler;
		$yazdir = $cikti == 'json' ? $sonuc : json_decode( $sonuc, TRUE );
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
		
		if ($this->__cache_sor( 'sehirler_' . $ulke ) )
		{
			$sonuc = $this->__cache_oku( 'sehirler_' . $ulke );
		} else {
			$veri = $this->al_sehirler( $ulke );
			if ( $veri['durum'] == 'basarili' )
			{
				$this->__cache_yaz( 'sehirler_' . $ulke, json_encode($veri) );
			}
			$sonuc = json_encode( $veri );
		}
		
		$yazdir = $cikti == 'json' ? $sonuc : json_decode( $sonuc, TRUE );
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
		
		if ( $this->__cache_sor( 'ilceler_' . $sehir ) )
		{
			$sonuc = $this->__cache_oku( 'ilceler_' . $sehir );
		} else {
			$veri = $this->al_ilceler( $sehir );
			
			if( $veri['durum'] == 'basarili' )
			{
				$this->__cache_yaz( 'ilceler_' . $sehir , json_encode($veri) );
			}
			$sonuc = json_encode( $veri );
		}
		
		$yazdir = $cikti == 'json' ? $sonuc : json_decode( $sonuc, TRUE );
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
	public function vakit( $sehir=NULL, $ulke=NULL, $cikti='array' )
	{
		$sehir = is_null( $sehir ) === TRUE ? $this->sehir : $sehir;
		$ulke = is_null( $ulke ) === TRUE ? $this->ulke : $ulke;
		
		if( $this->__cache_sor( 'vakit_' . $ulke . '_' . $sehir, 1 ) )
		{
			$sonuc = $this->__cache_oku( 'vakit_' . $ulke . '_' . $sehir );
		} else {
			$veri = $this->al_vakitler( $sehir, $ulke );
			
			if( $veri['durum'] == 'basarili' )
			{
				$this->__cache_yaz( 'vakit_' . $ulke . '_' . $sehir , json_encode($veri) );
			}
			$sonuc = json_encode( $veri );
		}
		$yazdir = $cikti == 'json' ? $sonuc : json_decode( $sonuc, TRUE );
		return $yazdir;
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
		if ( file_exists( $this->cache .  $dosya . '.json' ) AND is_readable( $this->cache . $dosya . '.json' ) )
		{
			if ( $gecerli == 0 )
			{
				return TRUE;
			} else {
				$dosya_zamani = date( 'dmY', filemtime( $this->cache . $dosya . '.json' ) );
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
		return file_get_contents( $this->cache . $dosya . '.json' );
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
		$fp = fopen( $this->cache . $dosya . '.json', "w" );
		fwrite( $fp, $veri );
		fclose( $fp );
		return;
	}
	
		
	
	#####################################################################################################################
	#####											VERİ ÇEKME İŞLEMLERİ											#####
	#####################################################################################################################
	
	/**
     * Ülkesi verilen şehirleri çeker
     *
     * @param string Verisi çekilecek ülkeyi belirler
     * @return array Sonucu bir dizi olarak döndürür
     */
	private function al_sehirler( $ulke )
	{
		$url = $ulke == 'TURKIYE' ? 'http://www.diyanet.gov.tr/PrayerTime/FillCity?itemId=%s&isState=false&isTurkey=true&itemSource=inner' : 'http://www.diyanet.gov.tr/PrayerTime/FillCity?itemId=%s&isState=false&isTurkey=false&itemSource=inner';
		
		$sonuc = $this->__curl( $url, urlencode($ulke) );
		
		if( $sonuc['durum'] == 'basarili' )
		{
			$veri = array();
			foreach( $sonuc['veri'] as $v )
			{
				$veri[] = array(
					'text'	=> $v['Text'],
					'value'	=> $v['Value'],
				);
			}
			
			$sonuc['veri'] = $veri;
		}
				
		return $sonuc;
	}
	
	
	/**
     * Şehri verilen ilçeleri çeker
     *
     * @param string Verisi çekilecek şehri belirler
     * @return array Sonucu bir dizi olarak döndürür
     */
	private function al_ilceler( $sehir )
	{
		$url = 'http://www.diyanet.gov.tr/PrayerTime/FillDistrict?itemId=%s';
		
		$sonuc = $this->__curl( $url, urlencode($sehir) );
		
		if( $sonuc['durum'] == 'basarili' )
		{
			$veri = array();
			foreach( $sonuc['veri'] as $v )
			{
				$text = $v['Text'] == $sehir ? $v['Text'] . ' - MERKEZ' : $v['Text'];
				$veri[] = array(
					'text'	=> $text,
					'value'	=> $v['Value'],
				);
			}
			
			$sonuc['veri'] = $veri;
		}
				
		return $sonuc;
	}
	
	
	/**
     * Verilen ülke ve şehir için vakitleri çeker
     *
     * @param string Verisi çekilecek ülkeyi belirler
	 * @param string Verisi çekilecek şehiri belirler
     * @return array Sonucu bir dizi olarak döndürür
     */
	private function al_vakitler( $sehir=NULL, $ulke=NULL )
	{
		$ulke = is_null( $ulke ) === TRUE ? $this->ulke : $ulke;
		$sehir = is_null( $sehir ) === TRUE ? $this->ilce : $sehir;
		
		$url = 'http://www.diyanet.gov.tr/PrayerTime/PrayerTimesSet';
		
		$data = array(
			"countryName"	=> "$ulke",
			"name"			=> "$sehir",
			"itemSource"	=> "inner"
		);
		
		$data = json_encode( $data );
		
		$sonuc = $this->__curl( $url, $data, TRUE );
		
		$karaliste = array('NextImsak', 'GunesText', 'ImsakText', 'OgleText', 'IkindiText', 'AksamText', 'YatsiText', 'HolyDaysItem');
		
		if ( $sonuc['durum'] == 'basarili' )
		{
			$veri = array();
			foreach ( $sonuc['veri'] as $k=>$v )
			{
				if( !in_array($k, $karaliste ) )
				{
					if ( $k == 'MoonSrc' )
					{
						$veri[strtolower($k)] = 'http://www.diyanet.gov.tr' . $v;
					}
					elseif ( $k == 'HicriTarih' )
					{
						$veri[strtolower($k)] = $this->hicri();
					} else {
						$veri[strtolower($k)] = $v;
					}
				}
			}
			$sonuc['veri'] = $veri;
		}
		
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
	private function __curl($url, $data, $is_post=FALSE)
	{
		if( !$is_post )
		{
			$url = sprintf( $url, $data );
		}
		
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		
		// Post varsa 
		if ( $is_post )
		{
			curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'POST' );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );
			curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json', 'Content-Length: ' . strlen( $data ) ) );
		}
			
		curl_setopt( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:26.0) Gecko/20100101 Firefox/26.0' );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
		
		$bilgi = curl_getinfo( $ch );
		$veri = curl_exec( $ch );
				
		if( $bilgi['http_code'] == 200 ) // POST durumunda geçerli veri dönerse HTTP_RESPONSE_CODE = 200 oluyor!
		{
			
			$sonuc = array(
				'durum'	=> 'basarili',
				'veri'	=> json_decode( $veri, TRUE )
			);
		} else {
			// GET Durumunda HTTP_RESPONSE_CODE = 0 olduğundan gelen veriye bakıyoruz. Eğer [] ise hata, değil ise veri!
			if( $veri != '[]' )
			{
				$sonuc = array(
					'durum'	=> 'basarili',
					'veri'	=> json_decode( $veri, TRUE )
				);
			} else {
				$sonuc = array(
					'durum'	=> 'hata',
					'veri'	=> array()
				);
			}
		}
		curl_close( $ch );
		return $sonuc;
	}
	
	
	#####################################################################################################################
	#####										HİCRİ TAKVIM FONKSIYONLARI											#####
	#####################################################################################################################
	
	private function hicri($tarih = null)
	{
		if ($tarih === null) $tarih = date('d.m.Y',time());
		$t = explode('.',$tarih);
		
		return $this->jd2hicri(cal_to_jd(CAL_GREGORIAN, $t[1],$t[0],$t[2]));
	}
	
	private function miladi($tarih = null)
	{
		if ($tarih === null) $tarih = date('d.m.Y',time());
		$t = explode('.',$tarih);
		return jd_to_cal(CAL_GREGORIAN,$this->hicri2jd($t[1],$t[0],$t[2]));
	}

    # julian day takviminden hicriye geçiş
    private function jd2hicri($jd)
    {
        $jd = $jd - 1948440 + 10632;
        $n  = (int)(($jd - 1) / 10631);
        $jd = $jd - 10631 * $n + 354;
        $j  = ((int)((10985 - $jd) / 5316)) *
            ((int)(50 * $jd / 17719)) +
            ((int)($jd / 5670)) *
            ((int)(43 * $jd / 15238));
        $jd = $jd - ((int)((30 - $j) / 15)) *
            ((int)((17719 * $j) / 50)) -
            ((int)($j / 16)) *
            ((int)((15238 * $j) / 43)) + 29;
        $m  = (int)(24 * $jd / 709);
        $d  = $jd - (int)(709 * $m / 24);
        $y  = 30*$n + $j - 30;

        return $d . ' ' . $this->hicriaylar[$m] . ' ' . $y;
    }

    # hicriden julian day takvimine geçiş
    private function hicri2jd($m, $d, $y)
    {
        return (int)((11 * $y + 3) / 30) +
            354 * $y + 30 * $m -
            (int)(($m - 1) / 2) + $d + 1948440 - 385;
    }
	
} // Sınıf Bitti
