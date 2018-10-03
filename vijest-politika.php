<?php
session_start();

include_once("baza.php");
error_reporting( error_reporting() & ~E_NOTICE);
$veza = spojiSeNaBazu();


if(isset($_POST["submit"])){
	$vijest_id = $_GET["vijest_id"];
	$tekst_komentara = $_POST["tekst_komentara"];
	$korisnik_id = $_POST["korisnik_id"];
	$datum = $_POST["datum"];
	$vrijeme = $_POST["vrijeme"];
	$greska = "";
	if(!isset($tekst_komentara) || empty($tekst_komentara)){
		$greska .= "Unesite komentar!<br>";
	}
	if(!isset($korisnik_id) || empty($korisnik_id)){
		$greska .= "Unesite korisnika!<br>";
	}
	if(!isset($datum) || empty($datum)){
		$greska .= "Unesite datum!<br>";
	}
	if(!isset($vrijeme) || empty($vrijeme)){
		$greska .= "Unesite vrijeme!<br>";
	}
	if(empty($greska)){
		$upit = "INSERT INTO komentar (vijest_id, korisnik_id, datum, vrijeme, tekst) VALUES ('".$vijest_id."', '".$korisnik_id."', '".$datum."', '".$vrijeme."', '".$tekst_komentara."')";
		izvrsiUpit($upit);
		header("Refresh:0");
	}
}

$upit = "SELECT * FROM vijest WHERE vijest_id = {$_GET["vijest_id"]}";
$rezultat = izvrsiUpit($upit);
$rezultat2 = izvrsiUpit($upit);
$rezultat_ispis = mysqli_fetch_array($rezultat);

$broj_pregleda = $rezultat_ispis['broj_pregleda'] + "1";
$upit = "UPDATE vijest SET broj_pregleda='".$broj_pregleda."' WHERE vijest_id = ".$_GET['vijest_id'];
			izvrsiUpit($upit);

$pretvori_datum_ddmmgggg = date('d.m.Y.',strtotime($rezultat_ispis["datum"]));
?>
<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>ZGinfo</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="apple-touch-icon" href="apple-touch-icon.png">
		
        <link rel="stylesheet" href="third-party/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="third-party/bootstrap/css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="third-party/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="stil.css">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="third-party/fancybox-master/dist/jquery.fancybox.min.css">
    </head>
    <body>
    	<header>
    	<?php include("meni.php"); ?>
		</header>

		<div class="body">
			<section class="page-header page-header-politika">
				<div class="container">
					<div class="row">
						<div class="col-md-12">
							<ul class="breadcrumb">
								<li><a href="index.php">Naslovnica</a></li>
								<li><a href="politika.php">Politika</a></li>
								<li class="active"><?php echo $rezultat_ispis["naziv"]; ?></li>
							</ul>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<h1><?php echo $rezultat_ispis["naziv"]; ?></h1>
						</div>
					</div>
				</div>
			</section>

			<div class="container">
				<div class="row">
					<div class="col-md-12">
					<?php
					while($red = mysqli_fetch_array($rezultat2)){
						if (isset($_SESSION["tip_id"]) AND $_SESSION["tip_id"] == 0){
							echo "<a  class='fadeInLeft' href='editiranje-vijesti.php?vijest_id={$red["vijest_id"]}'><button type='button' class='btn btn-danger'><i class='fa fa-edit'></i> Uredi</button></a>";
						}
						echo "<p class='datum-vijesti fadeInLeft'><i class='fa fa-calendar'></i> ".$pretvori_datum_ddmmgggg."</p>";
						if (!empty($red["slika"])){
						echo "
							<div class='omotac-slike-vijesti-detaljna fadeInLeft'>
								<a href='".$red["slika"]."' data-fancybox><img src='".$red["slika"]."' class='img-responsive detaljna-slika' alt='".$red[naziv]."' />
									<div class='overlay'>
									    <div class='btn-procitaj-vise'><i class='fa fa-plus'></i></div>
									</div>
								</a>
							</div>";
						}
						echo "
						<p class='detaljna-tekst fadeInRight'><b>".$red["kratki_tekst"]."</b><br />".$red["tekst"]."</p>";
					}
					?>
					</div>
				</div>
			</div>
			<div class="clear50"></div>
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<h3 class="fadeInLeft"><i class="fa fa-comments"></i> Komentari:</h3>
					</div>
					<?php
					$upit = "SELECT * FROM komentar WHERE vijest_id = {$_GET["vijest_id"]} ORDER BY datum DESC, vrijeme DESC";
					$rezultat = izvrsiUpit($upit);
					

					if(!empty($_SESSION["korisnik_id"])){
						echo "
						<form id='komentiranje' method='POST' action='".$_SERVER['PHP_SELF']."?vijest_id=".$_GET["vijest_id"]."'>
							<div class='col-md-12 fadeInLeft'>
								<textarea name='tekst_komentara' placeholder='Komentirajte ovu vijest...'' class='form-control form-control-input' rows='4'></textarea>";
									if(isset($greska) && !empty($greska)){
										echo "<div class='info-greska'>".$greska."</div>";
									}
									if(isset($poruka) && !empty($poruka)){
										echo "<h3 class='info-obavijest'>".$poruka."</h3>";
									}
									echo "
									<input type='hidden' value='".$_SESSION["korisnik_id"]."' name='korisnik_id' />
									<input type='hidden' value='".date('Y-m-d')."' name='datum' />
									<input type='hidden' value='".time('H:i:s')."' name='vrijeme' />
									<input class='btn btn-primary' style='margin-top:20px; margin-bottom: 20px; float:right;' name='submit' type='submit' value='Komentiraj'/>
							</div>
						</form>";
					}else{
						echo "
						<div class='col-md-12'>
							<p class='fadeInLeft'>Morate se prijaviti kako bi mogli komentirati vijesti <a class='btn btn-primary' href='prijava.php'><i class='fa fa-sign-in'></i> Prijavi se</a></p>
						</div>
						<div class='clear50'></div>";
					}
					?>
				</div>
				<?php

				while($red = mysqli_fetch_array($rezultat)){

				$upit = "SELECT * FROM korisnik WHERE korisnik_id = {$red["korisnik_id"]}";
				$rezultat2 = izvrsiUpit($upit);
				$rezultat_ispis_korisnik = mysqli_fetch_array($rezultat2);
				$pretvori_datum_ddmmgggg_komentar = date('d.m.Y.',strtotime($red["datum"]));
				echo "
				<div class='row'>
					<div class='fadeInLeft'>
						<div class='col-xs-3 col-sm-1'>
							<div class='thumbnail'>";
								if(!empty($rezultat_ispis_korisnik["slika"])){
								echo "<img class='img-responsive user-photo' src='".$rezultat_ispis_korisnik["slika"]."'/>";
								}else{
								echo "<img class='img-responsive user-photo' src='korisnici/korisnik.jpg'/>";						
								}

							echo "</div>
						</div>

						<div class='col-xs-9 col-sm-11'>
							<div class='panel panel-default'>
								<div class='panel-heading'>
									<strong>".$rezultat_ispis_korisnik["ime"]." ".$rezultat_ispis_korisnik["prezime"]." </strong> / <span class='text-muted'><i class='fa fa-calendar'></i> ".$pretvori_datum_ddmmgggg_komentar."</span>
								</div>
								<div class='panel-body'>".$red["tekst"]."</div>
							</div>
						</div>
					</div>
				</div>";
				}
				?>
			</div>
			<div class="clear50"></div>
			
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<ul class="breadcrumb">
							<li><a href="index.php">Naslovnica</a></li>
							<li><a href="politika.php">Politika</a></li>
							<li class="active"><?php echo $rezultat_ispis["naziv"]; ?></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		
		<?php include("podnozje.php"); ?>
	
		<script src="third-party/bootstrap/js/bootstrap.min.js"></script>
		<script src="third-party/jquery/jquery.min.js"></script>
		<script src="third-party/fancybox-master/dist/jquery.fancybox.min.js"></script>
		<script src="js/whenInViewport.js"></script>
		<script src="js/app.js"></script>
</body>
</html>
<?php
	zatvoriVezuNaBazu($veza);
?>