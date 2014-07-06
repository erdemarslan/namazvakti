<?php

require_once '../namazvakti_class/class.namazvakti.php';
$nv = new Namaz( dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR );


// Post işlemi varsa
if (count($_POST) > 0)
{
    switch($_POST['islem'])
    {
        case 'ulke' :
            $veri = $nv->ulkeler('json');
            echo $veri;
        break;
        
        case 'sehir' :
            $veri = $nv->sehirler($_POST['ulke'], 'json');
            echo $veri;
        break;
    
        case 'ilce' :
            $veri = $nv->ilceler($_POST['sehir'], 'json');
            echo $veri;
        break;
    
        case 'vakit' :
            $veri = $nv->vakit( $_POST['ulke'], $_POST['sehir'], $_POST['ilce']);
            print_r( $veri );
        break;
    }
}