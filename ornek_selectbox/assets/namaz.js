// Sayfa hazır olduğunda çalışacak kodlar!

$(document).ready(function() {
    // Ülkeleri al ve yerine koy!
    ulkeleri_al();
});


// Ülkeleri Dinamik olarak alma
function ulkeleri_al()
{
    $.post('sorgu.php', { islem: 'ulke' }, function(data){
        
        data = $.parseJSON(data);
        
        $.each( data, function(i,item){
            
            if (item.value != 'TURKIYE') {
                var newOption = $('<option />');
                $('#ulkeler').append(newOption);
                    
                newOption.val(item.value);
                newOption.html(item.text);
            }
        });
    });
}


// Şehirleri Dinamik olarak Alma
function sehirleri_al()
{
    // Ülke değişti, şehir ve ilçeleri sıfırla
    $('#sehirler').html('<option value="0">Lütfen Bir Şehir Seçiniz</option>');
    $('#ilceler').html('<option value="0">Lütfen Bir İlçe Seçiniz</option>');
    $('.result').html('');
    
    // ülkenin değerini oku
    var ulke_adi = $('#ulkeler').val();
    
    // verileri postala sonucu sehiler kutusuna yazdır
    $.post('sorgu.php', { islem: 'sehir', ulke: ulke_adi }, function(data){
        
        var response = $.parseJSON( data );
        
        if (response.durum == 'basarili') {
            
            $.each( response.veri, function(i,item){
                
                var newOption = $('<option />');
                $('#sehirler').append(newOption);
                
                newOption.val(item.value);
                newOption.html(item.text);
                
                if ( ulke_adi == 'TURKIYE' ) {
                    $('#ilceler_label').css('display','block');
                    $('#ilceler').css('display','block');
                } else {
                    $('#ilceler_label').css('display','none');
                    $('#ilceler').css('display', 'none');
                }
                
            });
            
        } else {
            alert('Şehirler Alınamadı!');
        }
        
    });
}


// İlçeleri dinamik olarak al. Sadece ÜLKE TÜRKİYE ise geçerlidir!
function ilceleri_al() {
    // önce ilçeler kutusunu bir varsayılana sıfırla
    $('#ilceler').html('<option value="0">Lütfen Bir İlçe Seçiniz</option>');
    $('.result').html('');
    
    // ülke ve şehir bilgilerini al
    var ulke_adi = $('#ulkeler').val();
    var sehir_adi = $('#sehirler').val();
    
    // Ülke TÜRKİYE ise çalış değilse çık
    if ( ulke_adi == 'TURKIYE' ) {
        $.post('sorgu.php', { islem:'ilce', sehir: sehir_adi }, function(data){
            var response = $.parseJSON(data);
            if (response.durum == 'basarili') {
                $.each(response.veri, function(i,item){
                    var newOption = $('<option />');
                    $('#ilceler').append(newOption);
                    
                    newOption.val(item.value);
                    newOption.html(item.text);
                });
            } else {
                alert('İlçeler Alınamadı!');
            }
        });
    }
}



// Vakti al
function vakti_al() {
    // gerekli alanlar ülke, şehir ve ilçe bilgileri
    var ulke_adi    = $('#ulkeler').val(),
        sehir_adi   = $('#sehirler').val(),
        ilce_adi    = $('#ilceler').val();
    
    // ilçe bilgisi 0 ise ülke TÜRKİYE değil demektir o durumda şehir olarak sehir_adi ni kullanacağız
    var veri_cekilecek_sehir_adi = ilce_adi == 0 ? sehir_adi : ilce_adi;
    
    $.post('sorgu.php', { islem:'vakit', sehir: veri_cekilecek_sehir_adi, ulke: ulke_adi }, function(data){        
        $('.result').html('<pre>' + data + '</pre>');
    });
}