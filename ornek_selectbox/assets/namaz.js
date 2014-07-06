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
		
		console.log(dump(data));
        
        $.each( data.veri, function(i,item){
            
            if (item.value != 2) {
                var newOption = $('<option />');
                $('#ulkeler').append(newOption);
                    
                newOption.val(i);
                newOption.html(item);
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
                
                newOption.val(i);
                newOption.html(item);
                
                if ( ulke_adi == 2 || ulke_adi == 33 || ulke_adi == 52 ) {
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
    if ( ulke_adi == 2 || ulke_adi == 33  || ulke_adi == 52 ) {
        $.post('sorgu.php', { islem:'ilce', sehir: sehir_adi }, function(data){
            var response = $.parseJSON(data);
            if (response.durum == 'basarili') {
                $.each(response.veri, function(i,item){
                    var newOption = $('<option />');
                    $('#ilceler').append(newOption);
                    
                    newOption.val(i);
                    newOption.html(item);
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
		
		if (ilce_adi == "" || ilce_adi == 0)
		{
			ilce_adi = sehir_adi;
		}
		    
    $.post('sorgu.php', { islem:'vakit', ilce: ilce_adi, sehir: sehir_adi, ulke: ulke_adi }, function(data){        
        $('.result').html('<pre>' + data + '</pre>');
    });
}
