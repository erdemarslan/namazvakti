<?php

$ulkeler_json = '{"2":"TURKIYE","33":"ABD","166":"AFGANISTAN","13":"ALMANYA","17":"ANDORRA","140":"ANGOLA","125":"ANGUILLA","90":"ANTIGUA VE BARBUDA","199":"ARJANTIN","25":"ARNAVUTLUK","153":"ARUBA","59":"AVUSTRALYA","35":"AVUSTURYA","5":"AZERBAYCAN","54":"BAHAMALAR","132":"BAHREYN","177":"BANGLADES","188":"BARBADOS","208":"BELARUS","11":"BELCIKA","182":"BELIZE","181":"BENIN","51":"BERMUDA","93":"BIRLESIK ARAP EMIRLIGI","83":"BOLIVYA","9":"BOSNA HERSEK","167":"BOTSVANA","146":"BREZILYA","97":"BRUNEI","44":"BULGARISTAN","91":"BURKINA FASO","154":"BURMA (MYANMAR)","65":"BURUNDI","155":"BUTAN","156":"CAD","43":"CECENISTAN","16":"CEK CUMHURIYETI","86":"CEZAYIR","160":"CIBUTI","61":"CIN","26":"DANIMARKA","180":"DEMOKRATIK KONGO CUMHURIYETI","176":"DOGU TIMOR","123":"DOMINIK","72":"DOMINIK CUMHURIYETI","139":"EKVATOR","63":"EKVATOR GINESI","165":"EL SALVADOR","117":"ENDONEZYA","175":"ERITRE","104":"ERMENISTAN","6":"ESTONYA","95":"ETYOPYA","145":"FAS","197":"FIJI","120":"FILDISI SAHILI","126":"FILIPINLER","204":"FILISTIN","41":"FINLANDIYA","21":"FRANSA","79":"GABON","109":"GAMBIYA","143":"GANA","111":"GINE","58":"GRENADA","48":"GRONLAND","171":"GUADELOPE","169":"GUAM ADASI","99":"GUATEMALA","67":"GUNEY AFRIKA","128":"GUNEY KORE","62":"GURCISTAN","82":"GUYANA","70":"HAITI","187":"HINDISTAN","30":"HIRVATISTAN","4":"HOLLANDA","66":"HOLLANDA ANTILLERI","105":"HONDURAS","113":"HONG KONG","15":"INGILTERE","124":"IRAK","202":"IRAN","32":"IRLANDA","23":"ISPANYA","205":"ISRAIL","12":"ISVEC","49":"ISVICRE","8":"ITALYA","122":"IZLANDA","119":"JAMAIKA","116":"JAPONYA","161":"KAMBOCYA","184":"KAMERUN","52":"KANADA","34":"KARADAG","94":"KATAR","92":"KAZAKISTAN","114":"KENYA","168":"KIRGIZISTAN","57":"KOLOMBIYA","88":"KOMORLAR","18":"KOSOVA","162":"KOSTARIKA","209":"KUBA","206":"KUDUS","133":"KUVEYT","1":"KUZEY KIBRIS","142":"KUZEY KORE","134":"LAOS","174":"LESOTO","20":"LETONYA","73":"LIBERYA","203":"LIBYA","38":"LIECHTENSTEIN","47":"LITVANYA","42":"LUBNAN","31":"LUKSEMBURG","7":"MACARISTAN","98":"MADAGASKAR","100":"MAKAO","28":"MAKEDONYA","55":"MALAVI","103":"MALDIVLER","107":"MALEZYA","152":"MALI","24":"MALTA","87":"MARTINIK","164":"MAURITIUS ADASI","157":"MAYOTTE","53":"MEKSIKA","85":"MIKRONEZYA","189":"MISIR","60":"MOGOLISTAN","46":"MOLDAVYA","3":"MONAKO","147":"MONTSERRAT (U.K.)","106":"MORITANYA","151":"MOZAMBIK","196":"NAMIBYA","76":"NEPAL","84":"NIJER","127":"NIJERYA","141":"NIKARAGUA","178":"NIUE","36":"NORVEC","80":"ORTA AFRIKA CUMHURIYETI","131":"OZBEKISTAN","77":"PAKISTAN","149":"PALAU","89":"PANAMA","185":"PAPUA YENI GINE","194":"PARAGUAY","69":"PERU","183":"PITCAIRN ADASI","39":"POLONYA","45":"PORTEKIZ","68":"PORTO RIKO","112":"REUNION","37":"ROMANYA","81":"RUANDA","207":"RUSYA","64":"SAUDI ARABISTAN","198":"SAMOA","102":"SENEGAL","138":"SEYSEL ADALARI","200":"SILI","179":"SINGAPUR","27":"SIRBISTAN","14":"SLOVAKYA","19":"SLOVENYA","150":"SOMALI","74":"SRI LANKA","129":"SUDAN","172":"SURINAM","191":"SURIYE","163":"SVALBARD","170":"SVAZILAND","101":"TACIKISTAN","110":"TANZANYA","137":"TAYLAND","108":"TAYVAN","71":"TOGO","130":"TONGA","96":"TRINIDAT VE TOBAGO","118":"TUNUS","159":"TURKMENISTAN","75":"UGANDA","40":"UKRAYNA","29":"UKRAYNA-KIRIM","173":"UMMAN","192":"URDUN","201":"URUGUAY","56":"VANUATU","10":"VATIKAN","186":"VENEZUELA","135":"VIETNAM","148":"YEMEN","115":"YENI KALEDONYA","193":"YENI ZELLANDA","144":"YESIL BURUN","22":"YUNANISTAN","158":"ZAMBIYA","136":"ZIMBABVE"}';


// Örnekler
$yerler_ornek = array(
	'ulke_id' => array(
		'ulke_adi' => '',
		'ilce_listesi_varmi' => true,
		'sehirler' => array(
			'sehir_id' => array(
				'sehir_adi' => '',
				'ilceler' => array(
					'ilce_id' => 'ilce_adi',
				)
			)
		)
	),
);

$adresler_ornek = array(
	'lokasyon_id' => array(
		'ulke' => '',
		'sehir' => '',
		'ilce' => '',
		'uzun_adi' => '',
		'tam_adi' => '',
		'url' => ''
	)
);


$yerler = array();

$adresler = array();



// Ülkeleri alalım!
$ulkeler = json_decode($ulkeler_json, true);


foreach ($ulkeler as $ulke_id => $ulke_adi) {
	// O ülkenin şehirlerini alalım!
	if(!array_key_exists($ulke_id, $yerler)) {
		$yerler[$ulke_id] = array(
			'ulke_adi' => $ulke_adi,
			'ilce_listesi_varmi' => false,
			'sehirler' => array()
		);
	}
	$sehirler = json_decode(file_get_contents("ham_sehirler/sehir_" . $ulke_id . ".json"), true);

	// İlçe listesi varsa!
	if($sehirler['HasStateList']) {
		$yerler[$ulke_id]['ilce_listesi_varmi'] = true;

		// burada şehir döngüsü yapılacak!
		foreach ($sehirler['StateList'] as $sehir) {
			$yerler[$ulke_id]['sehirler'][$sehir['SehirID']] = array(
				'sehir_adi' => $sehir['SehirAdi'],
				'ilceler' => array()
			);

			$ilcelerimiz = json_decode(file_get_contents("ham_ilceler/" . $ulke_id . "/ilce_" . $sehir['SehirID'] . ".json"), true);

			foreach ($ilcelerimiz['StateRegionList'] as $ilceler) {
				$yerler[$ulke_id]['sehirler'][$sehir['SehirID']]['ilceler'][$ilceler['IlceID']] = $ilceler['IlceAdi'];

				$uzunad = "";

				if($sehir['SehirAdi'] == $ilceler['IlceAdi']) {
					$uzunad = $sehir['SehirAdi'] . "-MERKEZ";
				} else {
					$uzunad = $sehir['SehirAdi'] . "-" . $ilceler['IlceAdi'];
				}

				if(!array_key_exists($ilceler['IlceID'], $adresler)) {
					$adresler[$ilceler['IlceID']] = array(
						'sehir_id' => $ilceler['IlceID'],
						'ulke' => $ulke_adi,
						'sehir' => $sehir['SehirAdi'],
						'ilce' => $ilceler['IlceAdi'],
						'uzun_adi' => $uzunad,
						'tam_adi' => $ulke_adi . "-" . $uzunad,
						'url' => $ilceler['IlceUrl']
					);
				}
			}
		}

	} else {
		$yerler[$ulke_id]['ilce_listesi_varmi'] = false;

		$sehir_idsi = $sehirler['StateList'][0]['SehirID'];
		$yerler[$ulke_id]['sehirler'][$sehir_idsi] = array(
			'sehir_adi' => $sehirler['StateList'][0]['SehirAdi'],
			'ilceler' => array()
		);

		foreach ($sehirler['StateRegionList'] as $ilceler) {
			$yerler[$ulke_id]['sehirler'][$sehir_idsi]['ilceler'][$ilceler['IlceID']] = $ilceler['IlceAdi'];

			if(!array_key_exists($ilceler['IlceID'], $adresler)) {
				$adresler[$ilceler['IlceID']] = array(
					'sehir_id' => $ilceler['IlceID'],
					'ulke' => $ulke_adi,
					'sehir' => $ilceler['IlceAdi'],
					'ilce' => $ilceler['IlceAdi'],
					'uzun_adi' => $ulke_adi . "-" . $ilceler['IlceAdi'],
					'tam_adi' => $ulke_adi . "-" . $ilceler['IlceAdi'],
					'url' => $ilceler['IlceUrl']
				);
			}
		}
	}


}

echo '<pre>';

//print_r($yerler);

print_r($adresler);

//print_r(json_encode($adresler));

/*
echo '<pre>';
print_r(json_decode('{"Result":null,"CountryList":null,"StateList":[{"ExtensionData":{},"SehirAdi":"ADANA","SehirAdiEn":"ADANA","SehirID":"500"},{"ExtensionData":{},"SehirAdi":"ADIYAMAN","SehirAdiEn":"ADIYAMAN","SehirID":"501"},{"ExtensionData":{},"SehirAdi":"AFYONKARAHİSAR","SehirAdiEn":"AFYONKARAHISAR","SehirID":"502"},{"ExtensionData":{},"SehirAdi":"AĞRI","SehirAdiEn":"AGRI","SehirID":"503"},{"ExtensionData":{},"SehirAdi":"AKSARAY","SehirAdiEn":"AKSARAY","SehirID":"504"},{"ExtensionData":{},"SehirAdi":"AMASYA","SehirAdiEn":"AMASYA","SehirID":"505"},{"ExtensionData":{},"SehirAdi":"ANKARA","SehirAdiEn":"ANKARA","SehirID":"506"},{"ExtensionData":{},"SehirAdi":"ANTALYA","SehirAdiEn":"ANTALYA","SehirID":"507"},{"ExtensionData":{},"SehirAdi":"ARDAHAN","SehirAdiEn":"ARDAHAN","SehirID":"508"},{"ExtensionData":{},"SehirAdi":"ARTVİN","SehirAdiEn":"ARTVIN","SehirID":"509"},{"ExtensionData":{},"SehirAdi":"AYDIN","SehirAdiEn":"AYDIN","SehirID":"510"},{"ExtensionData":{},"SehirAdi":"BALIKESİR","SehirAdiEn":"BALIKESIR","SehirID":"511"},{"ExtensionData":{},"SehirAdi":"BARTIN","SehirAdiEn":"BARTIN","SehirID":"512"},{"ExtensionData":{},"SehirAdi":"BATMAN","SehirAdiEn":"BATMAN","SehirID":"513"},{"ExtensionData":{},"SehirAdi":"BAYBURT","SehirAdiEn":"BAYBURT","SehirID":"514"},{"ExtensionData":{},"SehirAdi":"BİLECİK","SehirAdiEn":"BILECIK","SehirID":"515"},{"ExtensionData":{},"SehirAdi":"BİNGÖL","SehirAdiEn":"BINGOL","SehirID":"516"},{"ExtensionData":{},"SehirAdi":"BİTLİS","SehirAdiEn":"BITLIS","SehirID":"517"},{"ExtensionData":{},"SehirAdi":"BOLU","SehirAdiEn":"BOLU","SehirID":"518"},{"ExtensionData":{},"SehirAdi":"BURDUR","SehirAdiEn":"BURDUR","SehirID":"519"},{"ExtensionData":{},"SehirAdi":"BURSA","SehirAdiEn":"BURSA","SehirID":"520"},{"ExtensionData":{},"SehirAdi":"ÇANAKKALE","SehirAdiEn":"CANAKKALE","SehirID":"521"},{"ExtensionData":{},"SehirAdi":"ÇANKIRI","SehirAdiEn":"CANKIRI","SehirID":"522"},{"ExtensionData":{},"SehirAdi":"ÇORUM","SehirAdiEn":"CORUM","SehirID":"523"},{"ExtensionData":{},"SehirAdi":"DENİZLİ","SehirAdiEn":"DENIZLI","SehirID":"524"},{"ExtensionData":{},"SehirAdi":"DİYARBAKIR","SehirAdiEn":"DIYARBAKIR","SehirID":"525"},{"ExtensionData":{},"SehirAdi":"DÜZCE","SehirAdiEn":"DUZCE","SehirID":"526"},{"ExtensionData":{},"SehirAdi":"EDİRNE","SehirAdiEn":"EDIRNE","SehirID":"527"},{"ExtensionData":{},"SehirAdi":"ELAZIĞ","SehirAdiEn":"ELAZIG","SehirID":"528"},{"ExtensionData":{},"SehirAdi":"ERZİNCAN","SehirAdiEn":"ERZINCAN","SehirID":"529"},{"ExtensionData":{},"SehirAdi":"ERZURUM","SehirAdiEn":"ERZURUM","SehirID":"530"},{"ExtensionData":{},"SehirAdi":"ESKİŞEHİR","SehirAdiEn":"ESKISEHIR","SehirID":"531"},{"ExtensionData":{},"SehirAdi":"GAZİANTEP","SehirAdiEn":"GAZIANTEP","SehirID":"532"},{"ExtensionData":{},"SehirAdi":"GİRESUN","SehirAdiEn":"GIRESUN","SehirID":"533"},{"ExtensionData":{},"SehirAdi":"GÜMÜŞHANE","SehirAdiEn":"GUMUSHANE","SehirID":"534"},{"ExtensionData":{},"SehirAdi":"HAKKARİ","SehirAdiEn":"HAKKARI","SehirID":"535"},{"ExtensionData":{},"SehirAdi":"HATAY","SehirAdiEn":"HATAY","SehirID":"536"},{"ExtensionData":{},"SehirAdi":"IĞDIR","SehirAdiEn":"IGDIR","SehirID":"537"},{"ExtensionData":{},"SehirAdi":"ISPARTA","SehirAdiEn":"ISPARTA","SehirID":"538"},{"ExtensionData":{},"SehirAdi":"İSTANBUL","SehirAdiEn":"ISTANBUL","SehirID":"539"},{"ExtensionData":{},"SehirAdi":"İZMİR","SehirAdiEn":"IZMIR","SehirID":"540"},{"ExtensionData":{},"SehirAdi":"KAHRAMANMARAŞ","SehirAdiEn":"KAHRAMANMARAS","SehirID":"541"},{"ExtensionData":{},"SehirAdi":"KARABÜK","SehirAdiEn":"KARABUK","SehirID":"542"},{"ExtensionData":{},"SehirAdi":"KARAMAN","SehirAdiEn":"KARAMAN","SehirID":"543"},{"ExtensionData":{},"SehirAdi":"KARS","SehirAdiEn":"KARS","SehirID":"544"},{"ExtensionData":{},"SehirAdi":"KASTAMONU","SehirAdiEn":"KASTAMONU","SehirID":"545"},{"ExtensionData":{},"SehirAdi":"KAYSERİ","SehirAdiEn":"KAYSERI","SehirID":"546"},{"ExtensionData":{},"SehirAdi":"KİLİS","SehirAdiEn":"KILIS","SehirID":"547"},{"ExtensionData":{},"SehirAdi":"KIRIKKALE","SehirAdiEn":"KIRIKKALE","SehirID":"548"},{"ExtensionData":{},"SehirAdi":"KIRKLARELİ","SehirAdiEn":"KIRKLARELI","SehirID":"549"},{"ExtensionData":{},"SehirAdi":"KIRŞEHİR","SehirAdiEn":"KIRSEHIR","SehirID":"550"},{"ExtensionData":{},"SehirAdi":"KOCAELİ","SehirAdiEn":"KOCAELI","SehirID":"551"},{"ExtensionData":{},"SehirAdi":"KONYA","SehirAdiEn":"KONYA","SehirID":"552"},{"ExtensionData":{},"SehirAdi":"KÜTAHYA","SehirAdiEn":"KUTAHYA","SehirID":"553"},{"ExtensionData":{},"SehirAdi":"MALATYA","SehirAdiEn":"MALATYA","SehirID":"554"},{"ExtensionData":{},"SehirAdi":"MANİSA","SehirAdiEn":"MANISA","SehirID":"555"},{"ExtensionData":{},"SehirAdi":"MARDİN","SehirAdiEn":"MARDIN","SehirID":"556"},{"ExtensionData":{},"SehirAdi":"MERSİN","SehirAdiEn":"MERSIN","SehirID":"557"},{"ExtensionData":{},"SehirAdi":"MUĞLA","SehirAdiEn":"MUGLA","SehirID":"558"},{"ExtensionData":{},"SehirAdi":"MUŞ","SehirAdiEn":"MUS","SehirID":"559"},{"ExtensionData":{},"SehirAdi":"NEVŞEHİR","SehirAdiEn":"NEVSEHIR","SehirID":"560"},{"ExtensionData":{},"SehirAdi":"NİĞDE","SehirAdiEn":"NIGDE","SehirID":"561"},{"ExtensionData":{},"SehirAdi":"ORDU","SehirAdiEn":"ORDU","SehirID":"562"},{"ExtensionData":{},"SehirAdi":"OSMANİYE","SehirAdiEn":"OSMANIYE","SehirID":"563"},{"ExtensionData":{},"SehirAdi":"RİZE","SehirAdiEn":"RIZE","SehirID":"564"},{"ExtensionData":{},"SehirAdi":"SAKARYA","SehirAdiEn":"SAKARYA","SehirID":"565"},{"ExtensionData":{},"SehirAdi":"SAMSUN","SehirAdiEn":"SAMSUN","SehirID":"566"},{"ExtensionData":{},"SehirAdi":"ŞANLIURFA","SehirAdiEn":"SANLIURFA","SehirID":"567"},{"ExtensionData":{},"SehirAdi":"SİİRT","SehirAdiEn":"SIIRT","SehirID":"568"},{"ExtensionData":{},"SehirAdi":"SİNOP","SehirAdiEn":"SINOP","SehirID":"569"},{"ExtensionData":{},"SehirAdi":"ŞIRNAK","SehirAdiEn":"SIRNAK","SehirID":"570"},{"ExtensionData":{},"SehirAdi":"SİVAS","SehirAdiEn":"SIVAS","SehirID":"571"},{"ExtensionData":{},"SehirAdi":"TEKİRDAĞ","SehirAdiEn":"TEKIRDAG","SehirID":"572"},{"ExtensionData":{},"SehirAdi":"TOKAT","SehirAdiEn":"TOKAT","SehirID":"573"},{"ExtensionData":{},"SehirAdi":"TRABZON","SehirAdiEn":"TRABZON","SehirID":"574"},{"ExtensionData":{},"SehirAdi":"TUNCELİ","SehirAdiEn":"TUNCELI","SehirID":"575"},{"ExtensionData":{},"SehirAdi":"UŞAK","SehirAdiEn":"USAK","SehirID":"576"},{"ExtensionData":{},"SehirAdi":"VAN","SehirAdiEn":"VAN","SehirID":"577"},{"ExtensionData":{},"SehirAdi":"YALOVA","SehirAdiEn":"YALOVA","SehirID":"578"},{"ExtensionData":{},"SehirAdi":"YOZGAT","SehirAdiEn":"YOZGAT","SehirID":"579"},{"ExtensionData":{},"SehirAdi":"ZONGULDAK","SehirAdiEn":"ZONGULDAK","SehirID":"580"}],"StateRegionList":null,"HasStateList":true}',true));

*/




















/*
$sonuc = array();

$ilkbolme = explode(";", $veri);

foreach ($ilkbolme as $degerler) {
	$ikincibolme = explode("|", $degerler);

	$sonuc[$ikincibolme[0]] = $ikincibolme[1];
}

echo '<pre>';
print_r(json_encode($sonuc));


echo '<pre>';

$ulkeler = array(2,13,33,42,47,52,64);

foreach ($ulkeler as $ulke) {
	
	$sehir_ham = file_get_contents("ham_sehirler/sehir_" . $ulke . ".json");

	$sehiler_listesi = json_decode($sehir_ham, true);

	foreach ($sehiler_listesi['StateList'] as $sehir) {
		print_r($sehir);
		$url = "http://namazvakitleri.diyanet.gov.tr/tr-TR/home/GetRegList?ChangeType=state&CountryId=" . $ulke . "&Culture=tr-TR&StateId=" . $sehir["SehirID"];
		$dosyadi = "ham_ilceler/" . $ulke . "/ilce_" . $sehir["SehirID"] . ".json";
		vericek($url, $dosyadi);
		echo $dosyadi . " --> TAMAM<br>";
	}
}



function vericek($url, $dosyadi) {
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_HTTPGET, true );
	curl_setopt( $ch, CURLOPT_URL, $url );
	curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0' );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch, CURLOPT_REFERER, "http://namazvakitleri.diyanet.gov.tr/tr-TR" );

	$veri = curl_exec( $ch );
	$bilgi = curl_getinfo( $ch );

	

	if($bilgi["http_code"] == 200) {
		dosyayaYaz($veri, $dosyadi);
	}

	curl_close($ch);
	return;
}



function dosyayaYaz($veri, $dosyadi) {
    $fp = fopen($dosyadi, "w");
    fwrite($fp, $veri);
    fclose($fp);
    return;
}





//print_r(json_decode($sehir_ham, true));










/*

$ulkelerimiz = json_decode($ulkeler, TRUE);

//http://namazvakitleri.diyanet.gov.tr/tr-TR/home/GetRegList?ChangeType=state&CountryId=64&Culture=tr-TR&StateId=819

foreach ($ulkelerimiz as $ulkeid => $adi) {
	$url = "http://namazvakitleri.diyanet.gov.tr/tr-TR/home/GetRegList?ChangeType=country&CountryId=" . $ulkeid . "&Culture=tr-TR";
	$dosyadi = $ulkeid . "/ilce_" . $sehirid . ".json";
	vericek($url, $dosyadi);
	echo $adi . " --> TAMAM<br>";
}



echo '<pre>';

function vericek($url, $dosyadi) {
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_HTTPGET, true );
	curl_setopt( $ch, CURLOPT_URL, $url );
	curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0' );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch, CURLOPT_REFERER, "http://namazvakitleri.diyanet.gov.tr/tr-TR" );

	$veri = curl_exec( $ch );
	$bilgi = curl_getinfo( $ch );

	

	if($bilgi["http_code"] == 200) {
		dosyayaYaz($veri, $dosyadi);
	}

	curl_close($ch);
	return;
}

function dosyayaYaz($veri, $dosyadi) {
	//$dosya = "ham_ilceler/" . $dosyaadi;
    $fp = fopen($dosyadi, "w");
    fwrite($fp, $veri);
    fclose($fp);
    return;
}
*/




