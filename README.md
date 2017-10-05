namazvakti
==========

Bu PHP Sınıfı Diyanet İşleri Başkanlığından Namaz / ezan vakitlerini çeker.

v.6.0 Sürümüne güncellenmiştir.

<strong>Kullanımı</strong>

class.namazvakti.php dosyasını ve db ile cache klasörünü ana dizin klasörünüze koyunuz. Eklemeniz gereken kodlar aşağıda gösterilmiştir:

<code>
// Namaz vakti sınıfını dosyana include et!

require_once 'class.namazvakti.php';
</code>

Sınıfın kullanımı

<code>
$n = new Namaz();

// ülkeleri dizi olarak alma

$ulkeler = $n->ulkeler();

// ülkeleri json verisi olarak alma (özellikle js de kullanılacaksa)

$ulkeler = $n->ulkeler('json');

// Şehirleri dizi olarak alma

$sehirler = $n->sehirler(2); // mutlaka sayı değerinde bir parametreye sahip olmalıdır. Bu sayı değeri ülke kodudur ve $ulkeler değişkeninden alınabilir


// Şehirleri Json verisi olarak almak için

$sehirler = $n->sehirler(2, 'json');


// İlçeleri dizi olarak alma

$ilceler = $n->ilceler(521);

// İlçeleri json verisi olarak alma

$ilceler = $n->ilceler(521, 'json');

// Vakit bilgilerini dizi olarak almak için

$vakit = $n->vakit(2, 521, 9351);

// Vakit bilgilerinin json verisi olarak almak için

$vakit = $n->vakit(2, 521, 9351, 'json');
</code>

Gelen veriler dizi veya json verisi olmasına göre kodlar içinde kullanılır.

Bazen cache dosyasının hatalı olmasından dolayı veri çekilmiş gözüküp içeriği boş olabiliyor. Böyle bir durumda cache klasörünün içinin temizlenmesi gerekmektedir. Bunun için aşağıdaki kodu kullanınız:

<code>
$n->cache_temizle();
</code>


<strong>Önemli Hatırlatma</strong>
Soru ve sorunlarınızı github üzerinden paylaşabilirsiniz.

<strong>v.6.0</strong>
* Diyanetin kodlarında ve sunucularında yapmış olduğu değişiklikten dolayı, sınıf tekrardan kodlanmıştır.
* Hac mevsimi ile ilgili sorun olmayacağı düşünülerek, eski kodlar pasifize edilmiştir.
* Geriye dönük uyumluluk olmayabilir. Kodları ve geri dönen verileri tekrardan gözden geçiriniz!

<strong>v.5.3</strong>
* Diyanetin sunucularından veri çekme sıkıntısı giderilmiştir.
* Hac Kuraları zamanında ortaya çıkan sunucu problemini gidermek için hac_mevsimi() fonkisyonu eklenmiştir.
* cache klasörünün gerektiği zamanlarda temizlenebilmesi için cache_temizle() fonksiyonu eklendi.


<strong>v.5.2</strong>
* Diyanetin sunucularından veri çekme sıkıntısı giderilmiştir.
* Hicri takvimin 1 gün geriden gelme problemi giderilmiştir.
* Hemen hemen tüm dosyalar güncellenmiştir. Dolayısıyla daha önceden bu sistemin dosyalarını kullanıyorsanız, lütfen dosyaları yenileyiniz. Kod çıktılarına dikkat ediniz. Bu sürümde geriye dönük uyumluluk yoktur.
