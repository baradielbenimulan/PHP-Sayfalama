<?php
try{
	$veritabani_baglanti = new PDO("mysql:host=localhost;dbname=sayfalama;charset=UTF8", "root", "");
}catch(PDOException $hata){
	echo "Bağlantı sorunu<br />".$hata->getMessage();
	die();
}

$toplam_kayit_sayisi = $veritabani_baglanti->prepare();





?>
<!doctype html>
<html lang="tr-TR">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Language" content="tr">
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="style.css">
<link rel="icon" type="imavge/icon" href="img/favicon.png">
<title>Sayflama</title>
</head>
<body>
<?php
$kayit_sorgusu = $veritabani_baglanti->prepare("SELECT * FROM urunler ORDER BY id ASC");
$kayit_sorgusu->execute();
$kayit_sayisi = $kayit_sorgusu->rowCount();
$kayit_degerleri = $kayit_sorgusu->fetchAll(PDO::FETCH_ASSOC);

foreach ($kayit_degerleri as $kayitlar) {
	?>
	<div class="icerik">
		<img class="rsm" src="img/<?php echo($kayitlar['UrunResmi']); ?>">
		<h5 class="baslik-h5"><?php echo $kayitlar["UrunAdi"]; ?></h5>
		<p class="icerik_text"><?php echo $kayitlar["UrunFiyati"]." ".$kayitlar["ParaBirimi"]; ?></p>
		<a href="#" class="devami_oku" >Satın Al</a>
	</div>
<?php
}//foreach kapatma
?>
</body>
</html>

<?php
$veritabani_baglanti = null;
?>
