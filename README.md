namazvakti
==========

Bu PHP Sınıfı Diyanet İşleri Başkanlığından Namaz / ezan vakitlerini çeker.

v.8.1 Sürümüne güncellenmiştir.

<strong>Kullanımı</strong>
index.php dosyasını inceleyiniz.

Gelen veriler dizi veya json verisi olmasına göre kodlar içinde kullanılır.

Bazen cache dosyasının hatalı olmasından dolayı veri çekilmiş gözüküp içeriği boş olabiliyor. Böyle bir durumda cache klasörünün içinin temizlenmesi gerekmektedir. Bunun için aşağıdaki kodu kullanınız:

<code>
$n->cache_temizle();
</code>


<strong>Önemli Hatırlatma</strong>
Soru ve sorunlarınızı github üzerinden paylaşabilirsiniz.

<strong>v.8.1</strong>
* Sunucudan veri çekilmesini engelleyen karakter set hatası düzeltildi.
* Tarih ve Hicri tarihte soruna yol açabilecek hata düzeltildi.
* Tarih ve hicri tarihin daha düzgün görünmesi sağlandı.
* Bir önceki sürüm ile tam uyumluluk mevcuttur.

<strong>v.8.0</strong>
* Bir önceki sürümde yapılan hatalı kodlamadan dolayı verilerin yanlış gelmesini sağlayan problem çözülmüştür. Bunun için sınıf tekrar kodlandı.
* Bazı fonksiyonlarda değişiklikler oldu. Detaylı bilgiyi index.php dosyasından inceleyiniz.
* Geriye doğru kısmi uyumluluk mevcuttur. Kodları ve geri dönen verileri tekrar gözden geçiriniz.
* Diyanet üzerinden çekilen ham verileri ham_* klasörlerinden inceleyebilirsiniz. vericek.php bu verileri çekip yorumlarken kullanılan kodları içerir.

<strong>v.7.0</strong>
* Diyanetin kodlarında ve sunucularında yapmış olduğu değişiklikten dolay sınıf tekrar kodlanmıştır.
* Bazı fonksiyonlarda değişiklikler oldu. Detaylı bilgiyi ornek.php dosyasından inceleyiniz.
* Geriye doğru kısmi uyumluluk mevcuttur. Kodları ve geri dönen verileri tekrar gözden geçiriniz.
* Hicri takvim için v.6.0 da kaldırılan kodlar tekrar eklenmiştir.

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
