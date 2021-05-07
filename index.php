<?php
require_once("conn.php");

if (isset($_REQUEST["sayfa"])){
	$gelen_sayfa = $_REQUEST["sayfa"];
}else{
	$gelen_sayfa = 1;
}

$sayfalama_buton_sayisi = 2;
/* sayfa butonlarında sol ve sağ kısımların kaça kadar gitmesi gerektiğini ayarlar
örnek: sayfa 5'deyiz
<< < 3 4 5 6 7 > >>
gibi
*/
$sayfa_basina_gosterilecek_kayit_sayisi = 3;
/* bir sayfada kaç ürün göstersin */


$toplam_kayit_sayisi_sorgusu = $veritabani_baglanti->prepare("SELECT * FROM urunler");
$toplam_kayit_sayisi_sorgusu->execute();
$toplam_kayit_sayisi = $toplam_kayit_sayisi_sorgusu->rowCount();


$sayfalamaya_baslanacak_kayit_sayisisi = ($gelen_sayfa*$sayfa_basina_gosterilecek_kayit_sayisi)-($sayfa_basina_gosterilecek_kayit_sayisi);
/*
burada hesaplama yaptık
eğer sayfa değeri gelmezse ilk sayfayı aç
eğer sayfa değeri gelirse gelen sayfa değerini aç
*/

$bulunan_sayfa_sayisi = ceil($toplam_kayit_sayisi/$sayfa_basina_gosterilecek_kayit_sayisi);
/* burada hata olmasın diye sayısı yukarı yuvarladık.
son sayfada 1 tane ürün olsa bile yenş sayfa açacak
ve ona göre altta sayfalar görünecek
*/
?>

<!doctype html>
<html lang="tr-TR">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Language" content="tr">
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="style.css">
<link rel="icon" type="imavge/icon" href="img/favicon.png">
<title>Sayfalama</title>
</head>
<body>
<div class="urunler">
<?php
/* burada gelen urunleri veritabanından çekeceğiz. Çekerken hazırladığımız limiitte çekiyoruz. */

$kayit_sorgusu = $veritabani_baglanti->prepare("SELECT * FROM urunler ORDER BY id ASC LIMIT $sayfalamaya_baslanacak_kayit_sayisisi, $sayfa_basina_gosterilecek_kayit_sayisi");
$kayit_sorgusu->execute();
$kayit_sayisi = $kayit_sorgusu->rowCount();
$kayit_degerleri = $kayit_sorgusu->fetchAll(PDO::FETCH_ASSOC);

/* burada çektiğimiz kayıtları ilgili alanlara sıralı şekilde yazması için foreech oluşturduk */
foreach ($kayit_degerleri as $kayitlar) {
	?>
	<div class="icerik">
		<img class="rsm" src="img/<?php echo($kayitlar['UrunResmi']); ?>">
		<h5 class="baslik-h5"><?php echo $kayitlar["UrunAdi"]; ?></h5>
		<p class="icerik-fiyat"><?php echo $kayitlar["UrunFiyati"]." ".$kayitlar["ParaBirimi"]; ?></p>
		<a href="#" class="devami_oku" >Satın Al</a>
	</div>
<?php
}//foreach kapatma
?>
</div>
<div class="sayfalama-kapsayici">
	<div class="sayfalama-metin">
		<!-- gelen değerlerle sayfa ve ürün sayılarını yazdık -->
		Toplam <?php echo $bulunan_sayfa_sayisi; ?> sayfada <?php echo $toplam_kayit_sayisi; ?> ürün bulunmuştur
	</div>
	<div class="sayfalama-buton">
	<?php
	// eğer 1. dışındaki bir sayfadaysa burayı çalıştır
		if ($gelen_sayfa>1) {
			echo '<span class="pasif"><a href="index.php?sayfa=1"> << </a></span>';
			$sayfayi_bir_geri_al = $gelen_sayfa-1;
			/* en başa ve bir geri sayfaya gitme kodları*/
			/* << ilk sayfa demek */

			echo '<span class="pasif"><a href="index.php?sayfa='.$sayfayi_bir_geri_al.'"> < </a></span>';
			/* < önceki sayfa demek */
		}


		/* sayfa değerlerini gösteren for döngüsü */
		for (($sayfa_index_degeri=$gelen_sayfa-$sayfalama_buton_sayisi); ($sayfa_index_degeri<=$gelen_sayfa+$sayfalama_buton_sayisi); $sayfa_index_degeri++){
			/*
			burada şöyle oldu örnek $gelen_sayfa = 4; diyelim: ($sayfalama_buton_sayisi = 2;'ydi (10.satıra bak))

			$sayfa_index_degeri= 4 - 2;
			$sayfa_index_degeri = 2; oldu ve

			$sayfa_index_degeri= 4 + 2;
			$sayfa_index_degeri= 6; oldu

			her döngüde $sayfa_index_degeri'ini arttırdık. ve sonuç:
								2 3 4 5 6
			oldu

			*/

			if (($sayfa_index_degeri>0) and ($sayfa_index_degeri<=$bulunan_sayfa_sayisi)){
				/* eğer kullanıcı 1. sayfadaysa 0 ve -1 sayılarını gösterme*/
				/* ya da son sayfaya giderse olmayan sayfa değerlerini gösterme*/
				if ($sayfa_index_degeri==$gelen_sayfa){
					echo " ".'<span class="aktif">'.$sayfa_index_degeri.'</span>'." ";
					
				}else{
					/* eğer farklı sayfadaysa */
					echo '<span class="pasif"><a href="index.php?sayfa='.$sayfa_index_degeri.'">'.$sayfa_index_degeri.'</a></span>';
				}
				
			}
			
		}


		if ($gelen_sayfa!=$bulunan_sayfa_sayisi) {
			
			$sayfayi_bir_ileri_al = $gelen_sayfa+1;
			/* sayfa değerini bir ileri aldık */
			echo '<span class="pasif"><a href="index.php?sayfa='.$sayfayi_bir_ileri_al.'"> > </a></span>'; 
			/* > sonraki sayfa demek */

			echo '<span class="pasif"><a href="index.php?sayfa='.$bulunan_sayfa_sayisi.'"> >> </a></span>';
			/* zaten bulunan sayfa sayısı belliydi. onu url kısmına verdik. */
			/* >> son sayfa demek */
		}

	?>
	</div>
</div>
</body>
</html>

<?php
$veritabani_baglanti = null;
?>
