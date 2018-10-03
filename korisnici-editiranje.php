<?php
session_start();
if (!isset($_SESSION["korisnik_id"]) OR $_SESSION["tip_id"] != 0){
	header('Location: index.php');
}

include_once("baza.php");
error_reporting( error_reporting() & ~E_NOTICE);
$veza = spojiSeNaBazu();

$id_update_korisnik = $_GET["korisnikid"];

if(isset($_POST["submit"])){
	$ime = $_POST["ime"];
	$prezime = $_POST["prezime"];
	$korisnickoIme = $_POST["korisnickoIme"];
	$lozinka = $_POST["lozinka"];
	$email = $_POST["email"];
	$tipKorisnika = $_POST["tipKorisnika"];
	$slika = $_POST["slika"];
	$status = $_POST["status"];
	$greska_kor_ime = "";
	$greska_lozinka = "";
	$greska_tip_korisnika = "";
	$greska_email = "";
	$greska_slika = "";
	$greska_status = "";
	if(!isset($ime) || empty($ime)){
		$greska_ime = "Unesite ime!";
	}
	if(!isset($prezime) || empty($prezime)){
		$greska_prezime = "Unesite prezime!";
	}
	if(!isset($korisnickoIme) || empty($korisnickoIme)){
		$greska_kor_ime = "Unesite korisničko ime!";
	}
	if(!empty($korisnickoIme)){
		$upit = "SELECT korisnik.korisnicko_ime FROM korisnik WHERE korisnicko_ime = '".$korisnickoIme."' AND NOT korisnik_id = '".$id_update_korisnik."' LIMIT 1";
		$rezultat = izvrsiUpit($upit);
		if($red = mysqli_fetch_array($rezultat)){
			$greska_kor_ime = "Korisničko ime je zauzeto, odaberite drugo korisničko ime!";
		}
	}
	if(!isset($lozinka) || empty($lozinka)){
		$greska_lozinka = "Unesite lozinku";
	}
	if(!isset($tipKorisnika) || $tipKorisnika == "" || $tipKorisnika == "tip-nije-definiran"){
		$greska_tip_korisnika = "Odaberite tip korisnika";
	}
	if(!empty($email)){
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			$greska_email = "Format E-maila nije ispravan";
		}
	}
	if(!isset($_FILES['slika']) || $_FILES['slika']['error'] == UPLOAD_ERR_NO_FILE){
		if (isset($_GET['korisnik_id']) AND !empty($_GET['korisnik_id'])){
			$upit = "SELECT * FROM korisnik WHERE korisnik_id =".$id_update_korisnik;
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
			$greska_slika= "Format slike nije dozvoljen, molim učitajte JPG ili PNG format.<br/>";
		}
		if($file_size > 2097152) {
			$greska_slika= "Veličina slike mora biti manja od 2 MB.<br/>";
		}
		if(empty($greska_slika)==true) {
			move_uploaded_file($file_tmp,"korisnici/".$file_name);
			$slika = "korisnici/".$file_name;
		}
	}
	if ($status !=0 AND $status !=1){
		$greska_status = "Status nije ispravno unešen! Value može biti \"1\" Aktivan ili \"0\" Blokiran";
	}elseif(!isset($status)){
		$greska_status = "Odaberite status!" .$status;
	} 
	if(empty($greska_ime) AND empty($greska_prezime) AND empty($greska_kor_ime) AND empty($greska_email) AND empty($greska_lozinka) AND empty($greska_slika) AND empty($greska_status)){
		if (!empty($id_update_korisnik)){
			$poruka = "Uspješno ste ažurirali korisnika";
			$upit = "UPDATE korisnik SET tip_id=".$tipKorisnika.", status='".$status."', korisnicko_ime='".$korisnickoIme."', lozinka='".$lozinka."', ime='".$ime."', prezime='".$prezime."', email='".$email."', slika='".$slika."' WHERE korisnik_id = ".$id_update_korisnik;
			izvrsiUpit($upit);
		}else{
			$poruka="Uspješno ste dodali novog korisnika";
			$upit = "INSERT INTO korisnik (tip_id, status, korisnicko_ime, lozinka, ime, prezime, email, slika) VALUES ('".$tipKorisnika."','".$status."', '".$korisnickoIme."', '".$lozinka."', '".$ime."', '".$prezime."', '".$email."', '".$slika."')";
			izvrsiUpit($upit);
		}
	}
}
if(isset($_GET["korisnikid"]) AND !empty($_GET["korisnikid"])){
	$upit = "SELECT * FROM korisnik WHERE korisnik_id = ".$id_update_korisnik;
	$rezultat = izvrsiUpit($upit);
	$rezultat_ispis = mysqli_fetch_array($rezultat);
}
$upit = "SELECT * FROM tip_korisnika";
$tipovi_korisnika = izvrsiUpit($upit);			
?>
<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>ZGinfo | Editiranje korisnika</title>
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
								<li><a href="korisnici.php">Korisnici</a></li>
								<li class="active">Editiranje korisnika</li>
							</ul>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<h1>Editiranje korisnika</h1>
						</div>
					</div>
				</div>
			</section>

			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<form name="korisnici" id=korisinci" method="POST" enctype="multipart/form-data" action="<?php echo $_SERVER["PHP_SELF"]."?korisnikid=".$id_update_korisnik; ?>" >
							<div class="form-group">
								<label for="ime"><i class="fa fa-user"></i> *Ime</label>
								<input class="form-control form-control-input" type="text" name="ime" id="ime" value="<?php echo $rezultat_ispis["ime"]; ?>">
								<?php
									if(isset($greska_ime) && !empty($greska_ime)){
										echo "<div class='info-greska'>".$greska_ime."</div>";
									}
								?>
							</div>
							<div class="form-group">
								<label for="prezime"><i class="fa fa-user"></i> *Prezime</label>
								<input class="form-control form-control-input" name="prezime" id="prezime" type="text" value="<?php echo $rezultat_ispis["prezime"]; ?>" />
								<?php
									if(isset($greska_prezime) && !empty($greska_prezime)){
										echo "<div class='info-greska'>".$greska_prezime."</div>";
									}
								?>
							</div>
							<div class="form-group">
								<label for="korisnickoIme"><i class="fa fa-user-secret"></i> *Korisničko ime</label>
								<input class="form-control form-control-input" name="korisnickoIme" id="korisnickoIme" type="text" value="<?php echo $rezultat_ispis["korisnicko_ime"]; ?>" />
								<?php
									if(isset($greska_kor_ime) && !empty($greska_kor_ime)){
										echo "<div class='info-greska'>".$greska_kor_ime."</div>";
									}
								?>
							</div>
							<div class="form-group">
								<label for="lozinka"><i class="fa fa-puzzle-piece"></i> *Lozinka</label>
								<input class="form-control form-control-input" name="lozinka" id="lozinka" type="text" value="<?php echo $rezultat_ispis["lozinka"]; ?>" />
								<?php
									if(isset($greska_lozinka) && !empty($greska_lozinka)){
										echo "<div class='info-greska'>".$greska_lozinka."</div>";
									}
								?>
							</div>
							<div class="form-group">
								<label for="email"><i class="fa fa-envelope"></i> *E-mail</label>
								<input class="form-control form-control-input" name="email" id="email" type="email" value="<?php echo $rezultat_ispis["email"]; ?>" />
								<?php
									if(isset($greska_email) && !empty($greska_email)){
										echo "<div class='info-greska'>".$greska_email."</div>";
									}
								?>
							</div>
							<div class="form-group">
								<label for="status"><i class="fa fa-toggle-on"></i> *Status</label><br>
								<input class="" name="status" id="status" type="radio" value="1" <?php if (isset($rezultat_ispis["status"]) AND $rezultat_ispis["status"] == "1"){ echo "checked='checked'"; } ?> /> Aktivan
								<input class="" name="status" id="status" type="radio" value="0" <?php if (isset($rezultat_ispis["status"]) AND $rezultat_ispis["status"] == "0"){ echo "checked='checked'"; } ?> /> Blokiran
								<?php
									if(isset($greska_status) && !empty($greska_status)){
										echo "<div class='info-greska'>".$greska_status."</div>";
									}
								?>
							</div>
							<div class="form-group">
								<label for="slika"><i class="fa fa-photo"></i> Slika</label>
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
							<div class="form-group">
								<label for="tipKorisnika"><i class="fa fa-tasks"></i> *Tip korisnika</label>
								<select class="form-control" name="tipKorisnika" id="tipKorisnika">
									<option value="tip-nije-definiran" selected="selected">----- Odaberite tip korisnika -----</option>
									<?php
									while($red = mysqli_fetch_array($tipovi_korisnika)){
										echo "<option value=\"".$red["tip_id"]."\"";
										if($rezultat_ispis['tip_id'] == $red["tip_id"])
										{
											echo " selected=\"selected\" ";
										}
										echo ">".$red["naziv"]."</option>";
									}
									?>
								</select>
								<?php
									if(isset($greska_tip_korisnika) && !empty($greska_tip_korisnika)){
										echo "<div class='info-greska'>".$greska_tip_korisnika."</div>";
									}
								?>
							</div>
							<br/>						
							<input name="korisnikId" id="korisnikId" type="hidden" value="<?php echo $rezultat_ispis["korisnik_id"]; ?>" />
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
							<li><a href="korisnici.php">Korisnici</a></li>
							<li class="active">Editiranje korisnika</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		
		<?php include("podnozje.php"); ?>
	
		<script src="third-party/jquery/jquery.min.js"></script>
		<script src="third-party/bootstrap/js/bootstrap.min.js"></script>
</html>
<?php
	zatvoriVezuNaBazu($veza);
?>