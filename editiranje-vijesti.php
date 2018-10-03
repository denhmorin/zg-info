<?php
session_start();
if (!isset($_SESSION["korisnik_id"]) OR $_SESSION["tip_id"] != 0){
	header('Location: index.php');
}
include_once("baza.php");
error_reporting( error_reporting() & ~E_NOTICE);
$veza = spojiSeNaBazu();

$id_update_vijest = $_GET["vijest_id"];

if(isset($_POST["submit"])){
	$naziv = $_POST["naziv"];
	$vrsta_id = $_POST["vrsta_id"];
	$kratki_tekst = $_POST["kratki_tekst"];
	$tekst = $_POST["tekst"];
	$slika = $_POST["slika"];
	$datum = $_POST["datum"];
	$greska_naziv = "";
	$greska_kategorija = "";
	$greska_datum = "";
	$greska_slika = "";
	if(!isset($naziv) || empty($naziv)){
		$greska_naziv = "Unesite naslov vijesti!<br>";
	}
	if(!isset($vrsta_id) || empty($vrsta_id)){
		$greska_kategorija = "Odaberite kategoriju vijesti!<br>";
	}
	if(!isset($datum) || empty($datum)){
		$greska_datum = "Datum nije ispravan!<br>";
	}
	if(!isset($_FILES['slika']) || $_FILES['slika']['error'] == UPLOAD_ERR_NO_FILE){
		if (isset($_GET['vijest_id']) AND !empty($_GET['vijest_id'])){
			$upit = "SELECT * FROM vijest WHERE vijest_id =".$id_update_vijest;
			$rezultat = izvrsiUpit($upit);
			while($red = mysqli_fetch_array($rezultat)){
				$slika = $red["slika"];
			}
		}
	}else{	
		$file_name = $_FILES['slika']['name'];
		$file_size = $_FILES['slika']['size'];
		$file_tmp = $_FILES['slika']['tmp_name'];
		$file_type = $_FILES['slika']['type'];
		$file_ext = strtolower(end(explode('.',$_FILES['slika']['name'])));
		$expensions= array("jpeg","jpg","png");
		if(in_array($file_ext,$expensions)=== false){
			$greska_slika = "Format slike nije dozvoljen, molim učitajte JPG ili PNG format.<br/>";
		}
		if($file_size > 2097152) {
			$greska_slika = "Veličina slike mora biti manja od 2 MB.<br/>";
		}
		if(empty($greska_slika)==true) {
			move_uploaded_file($file_tmp,"slike/".$file_name);
			$slika = "slike/".$file_name;
		}
	}
	
	if(empty($greska_slika) AND empty($greska_datum) AND empty($greska_kategorija) AND empty($greska_naziv)){
		if (!empty($id_update_vijest)){
			$poruka = "Uspješno ste ažurirali vijest";
			$upit = "UPDATE vijest SET vrsta_id=".$vrsta_id.", datum=".$datum.", naziv='".$naziv."', kratki_tekst='".$kratki_tekst."', tekst='".$tekst."', slika='".$slika."' WHERE vijest_id = ".$id_update_vijest;
			izvrsiUpit($upit);
		} else {
			$poruka = "Uspješno ste dodali novu vijest";
			$upit = "INSERT INTO vijest (vrsta_id, datum, naziv, kratki_tekst, tekst, slika) VALUES ('".$vrsta_id."', '".$datum."', '".$naziv."', '".$kratki_tekst."', '".$tekst."', '".$slika."')";
			izvrsiUpit($upit);
		}
	}
}
	
if(isset($_GET["vijest_id"]) AND !empty($_GET["vijest_id"])){
	$upit = "SELECT * FROM vijest WHERE vijest_id = ".$id_update_vijest;
	$rezultat = izvrsiUpit($upit);
	$rezultat_ispis = mysqli_fetch_array($rezultat);
}

$upit = "SELECT * FROM vrsta";
$vrsta = izvrsiUpit($upit);	
?>
<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>ZGinfo | Editiranje vijesti</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="apple-touch-icon" href="apple-touch-icon.png">
		
        <link rel="stylesheet" href="third-party/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="third-party/bootstrap/css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="third-party/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="stil.css">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    </head>
    <body>
    	<header>
    	<?php include("meni.php"); ?>
		</header>

		<div class="body">
			<section class="page-header">
				<div class="container">
					<div class="row">
						<div class="col-md-12">
							<ul class="breadcrumb">
								<li><a href="index.php">Naslovnica</a></li>
								<li class="active">Editiranje vijesti</li>
							</ul>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<h1>Editiranje vijesti</h1>
						</div>
					</div>
				</div>
			</section>

			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<form name="editiranje-vijesti" id="editiranje-vijesti" method="POST" enctype="multipart/form-data" action="<?php echo $_SERVER["PHP_SELF"]."?vijest_id=".$id_update_vijest; ?>" >
							<div class="form-group">
								<label for="naziv">*Naslov vijesti</label>
								<input class="form-control" name="naziv" id="naziv" type="text" maxlength="120" value="<?php echo $rezultat_ispis["naziv"]; ?>"/>
								<?php
									if(isset($greska_naziv) && !empty($greska_naziv)){
										echo "<div class='info-greska'>".$greska_naziv."</div>";
									}
								?>
							</div>
							<div class="form-group">
								<label for="vrsta_id">*Kategorija vijesti</label>
								<select class="form-control" name="vrsta_id" id="vrsta_id">
									<?php
										while($red = mysqli_fetch_array($vrsta))
										{
											echo "<option value=\"".$red["vrsta_id"]."\"";
											if($rezultat_ispis["vrsta_id"] == $red["vrsta_id"]) {
												echo " selected=\"selected\" ";
											}
											echo ">".$red["naziv"]."</option>";
										}
									?>
								</select>
								<?php
									if(isset($greska_kategorija) && !empty($greska_kategorija)){
										echo "<div class='info-greska'>".$greska_kategorija."</div>";
									}
								?>
							</div>
							<div class="form-group">
								<label for="kratki_tekst">Kratki tekst vijesti</label>
								<input class="form-control" name="kratki_tekst" id="kratki_tekst" type="text" maxlength="120" value="<?php echo $rezultat_ispis["kratki_tekst"]; ?>"/>
							</div>
							<div class="form-group">
								<label for="tekst">Tekst vijesti</label>
								<textarea class="form-control" name="tekst" id="tekst" placeholder="Unesite tekst vijesti" rows="10"><?php echo $rezultat_ispis["tekst"]; ?></textarea>
							</div>
							<div class="form-group">
								<label for="slika">Slika</label>
								<?php
								if (!empty($rezultat_ispis["slika"])){
									echo "<br/><p>Trenutna slika:</p><img style='height: auto; width: 200px;' src='".$rezultat_ispis["slika"]."' />";
								}
								?>
								<input class="form-control" name="slika" accept="img/*" id="slika" type="file" style="background: #f0f0f0;" value="<?php echo $rezultat_ispis["slika"]; ?>" />
								<?php
									if(isset($greska_slika) && !empty($greska_slika)){
										echo "<div class='info-greska'>".$greska_slika."</div>";
									}
								?>
							</div>
							<input type="hidden" name="datum" id="datum" value="<?php if(!empty($rezultat_ispis["datum"])) { echo $rezultat_ispis["datum"]; }else { echo date('Y-m-d'); } ?>" />
							<input class="btn btn-primary btn-lg" type="submit" name="submit" id="submit" value="Spremi" />
						</form>
						<?php
							if(isset($poruka) && !empty($poruka)){
								echo "<h3 class='info-obavijest'>".$poruka."</h3>";
							}
						?>
						<br/>
					</div>
				</div>
			</div>
			<div class="clear50"></div>
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<ul class="breadcrumb">
							<li><a href="index.php">Naslovnica</a></li>
							<li class="active">Editiranje vijesti</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		
		<?php include("podnozje.php"); ?>
	
		<script src="third-party/jquery/jquery.min.js"></script>
		<script src="third-party/bootstrap/js/bootstrap.min.js"></script>
    </body>
</html>
<?php
	zatvoriVezuNaBazu($veza);
?>