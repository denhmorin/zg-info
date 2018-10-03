<?php
session_start();
if(!isset($_SESSION["korisnik_id"])){
}
if(isset($_GET["odjava"]) || isset($_POST["odjavaKorisnika"])){
	unset($_SESSION["korisnik_id"]);
	unset($_SESSION["ime"]);
	session_destroy();
	header('Location: index.php');
}

include_once("baza.php");
error_reporting( error_reporting() & ~E_NOTICE);
$veza = spojiSeNaBazu();

if(isset($_POST["prijavaKorisnika"])){
	$greska_prijava_kor_ime = "";
	$greska_prijava_lozinka = "";
	$greska_prijava = "";
	if(empty($_POST["korisnicko_ime"])){
		$greska_prijava_kor_ime = "Molim unesite korisničko ime.";
	}
	if (empty($_POST["lozinka"])){
		$greska_prijava_lozinka = "Molim unesite lozinku.";
	}
	if (!empty($_POST["korisnicko_ime"]) AND !empty($_POST["lozinka"])){
		include_once("baza.php");
		error_reporting( error_reporting() & ~E_NOTICE);
		$veza = spojiSeNaBazu();
		
		$upit = "SELECT * FROM korisnik WHERE korisnicko_ime='".$_POST["korisnicko_ime"]."' AND lozinka='".$_POST["lozinka"]."'";
		$rezultat = izvrsiUpit($upit);
		$broj = mysqli_num_rows($rezultat);
		if($broj === 1){
			setcookie("prijava",$_POST["korisnicko_ime"]);
			zatvoriVezuNaBazu($veza);
			$red = mysqli_fetch_array($rezultat);
			if($red["slika"] == "" || !file_exists($red["slika"])){
				$red["slika"] = "korisnici/korisnik.jpg";
			}
			session_start();
			$_SESSION["korisnik_id"] = $red["korisnik_id"];
			$_SESSION["tip_id"] = $red["tip_id"];
			$_SESSION["ime"] = $red["ime"];
			$_SESSION["prezime"] = $red["prezime"];
			$_SESSION["korisnicko_ime"] = $red["korisnicko_ime"];
			$_SESSION["slika"] = $red["slika"];
			$_SESSION["status"] = $red["status"];
			if ($_SESSION["status"] == "0") {
				unset($_SESSION["korisnik_id"]);
				unset($_SESSION["ime"]);
				session_destroy();
				header('Location: blokiran-racun.php');
			} else {
			header('Location: index.php');
			exit();
			}
		}else{
			zatvoriVezuNaBazu($veza);
			$greska_prijava = "Korisničko ime ili lozinka nisu ispravni.";
		}
	}
}

if(isset($_POST["registracijaKorisnika"])){
	$reg_ime = $_POST["reg_ime"];
	$reg_prezime = $_POST["reg_prezime"];
	$reg_kor_ime = $_POST["reg_kor_ime"];
	$reg_lozinka = $_POST["reg_lozinka"];
	$reg_email = $_POST["reg_email"];
	$reg_tip_korisnika = 1;
	$reg_slika = $_POST["reg_slika"];
	$reg_status = 1;
	$greska_reg_ime = "";
	$greska_reg_prezime = "";
	$greska_reg_kor_ime = "";
	$greska_reg_lozinka = "";
	$greska_reg_email = "";
	$greska_reg_slika = "";
	$greska_reg = "";

	if(!isset($reg_ime) || empty($reg_ime)){
		$greska_reg_ime .= "Unesite ime!<br>";
	}
	if(!isset($reg_prezime) || empty($reg_prezime)){
		$greska_reg_prezime .= "Unesite prezime!<br>";
	}
	if(empty($reg_email)){
		$greska_reg_email .= "Unesite Email!<br>";
	}
	if(!empty($reg_email)){
		if(!filter_var($reg_email, FILTER_VALIDATE_EMAIL)){
			$greska_reg_email .= "Format E-maila nije ispravan<br>";
		}
	}
	if(!isset($reg_kor_ime) || empty($reg_kor_ime)){
		$greska_reg_kor_ime .= "Unesite korisničko ime!<br>";
	}
	if(!empty($reg_kor_ime)){
		$upit = "SELECT korisnik.korisnicko_ime FROM korisnik WHERE korisnicko_ime = '".$reg_kor_ime."' LIMIT 1";
		$rezultat = izvrsiUpit($upit);
		if($red = mysqli_fetch_array($rezultat)){
			$greska_reg_kor_ime .= "Korisničko ime je zauzeto, odaberite drugo korisničko ime!<br>";
		}
	}
	if(!isset($reg_lozinka) || empty($reg_lozinka)){
		$greska_reg_lozinka .= "Unesite lozinku<br>";
	}
	if(!empty($reg_email)){
		if(!filter_var($reg_email, FILTER_VALIDATE_EMAIL)){
			$greska_reg_email .= "Format E-maila nije ispravan<br>";
		}
	}
	if(!isset($_FILES['reg_slika']) || $_FILES['reg_slika']['error'] == UPLOAD_ERR_NO_FILE){
	}else{	
		$file_name = $_FILES['reg_slika']['name'];
		$file_size = $_FILES['reg_slika']['size'];
		$file_tmp = $_FILES['reg_slika']['tmp_name'];
		$file_type = $_FILES['reg_slika']['type'];
		$file_ext = strtolower(end(explode('.',$_FILES['reg_slika']['name'])));
		$expensions= array("jpeg","jpg","png");
		if(in_array($file_ext,$expensions)=== false){
			$greska_reg_slika .= "Format slike nije dozvoljen, molim učitajte JPG ili PNG format.<br/>";
		}
		if($file_size > 2097152) {
			$greska_reg_slika .= "Veličina slike mora biti manja od 2 MB.<br/>";
		}
		if(empty($greska_reg_slika)==true) {
			move_uploaded_file($file_tmp,"korisnici/".$file_name);
			$reg_slika = "korisnici/".$file_name;
		}
	}
	$greska_reg = array($greska_reg_ime, $greska_reg_prezime, $greska_reg_kor_ime, $greska_reg_email, $greska_reg_lozinka, $greska_reg_slika);
	if(count($greska_reg) AND empty($greska_reg)){
		$poruka="Uspješno ste se registrirali.";
		$upit = "INSERT INTO korisnik (tip_id, status, korisnicko_ime, lozinka, ime, prezime, email, slika) VALUES ('".$reg_tip_korisnika."','".$reg_status."', '".$reg_kor_ime."', '".$reg_lozinka."', '".$reg_ime."', '".$reg_prezime."', '".$reg_email."', '".$reg_slika."')";
		izvrsiUpit($upit);
	}
}
?>
<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>ZGinfo | Prijava</title>
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
								<li class="active">Prijava</li>
							</ul>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<h1>Prijava</h1>
						</div>
					</div>
				</div>
			</section>

			<div class="container">
				<div class="row">
					<div class="col-md-6 col-sm-12">
						<h2>Prijava</h2>
						<form id="prijava" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
							<label for="korisnicko_ime">*Korisničko ime:</label>
							<input class="form-control form-control-input" placeholder="Korisničko ime" name="korisnicko_ime" id="korisnicko_ime" type="text">
							<?php
								if(isset($greska_prijava_kor_ime) && !empty($greska_prijava_kor_ime)){
									echo "<div class='info-greska'>".$greska_prijava_kor_ime."</div>";
								}
							?>
							<label for="lozinka">*lozinka</label>
							<input class="form-control form-control-input" placeholder="Lozinka" name="lozinka" id="lozinka" type="password">
							<?php
								if(isset($greska_prijava_lozinka) && !empty($greska_prijava_lozinka)){
									echo "<div class='info-greska'>".$greska_prijava_lozinka."</div>";
								}
								if(isset($greska_prijava) && !empty($greska_prijava)){
									echo "<div class='info-greska'>".$greska_prijava."</div>";
								}
							?>
							<br/>
							<?php
							if(!isset($_SESSION["id"])){
								echo "<input class='btn btn-primary btn-lg btn-block' name='prijavaKorisnika' type='submit' value='Prijava'/>";
							}else{
								echo "<input class='btn btn-primary btn-lg btn-block' name='odjavaKorisnika' type='submit' value='" . $_SESSION["ime"] . " - Odjava'/>";
							}
							?>
							<?php
							if(isset($greska) && !empty($greska)){
								echo "<div class='info-greska'>".$greska."</div>";
							}
							?>
						</form>
					</div>
					<div class="col-md-6 col-sm-12">
						<h2>Registracija</h2>
						<form id="registracija" method="POST" enctype="multipart/form-data" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
							<div class="form-group">
								<label for="reg_ime">*Ime:</label>
								<input class="form-control form-control-input" placeholder="Ime" name="reg_ime" id="reg_ime" type="text">
								<?php
									if(isset($greska_reg_ime) && !empty($greska_reg_ime)){
										echo "<div class='info-greska'>".$greska_reg_ime."</div>";
									}
								?>
							</div>

							<div class="form-group">
								<label for="reg_prezime">*Prezime:</label>
								<input class="form-control form-control-input" placeholder="Prezime" name="reg_prezime" id="reg_prezime" type="text">
								<?php
									if(isset($greska_reg_prezime) && !empty($greska_reg_prezime)){
										echo "<div class='info-greska'>".$greska_reg_prezime."</div>";
									}
								?>
							</div>

							<div class="form-group">
								<label for="reg_kor_ime">*Korisničko ime:</label>
								<input class="form-control form-control-input" placeholder="Korisničko ime" name="reg_kor_ime" id="reg_kor_ime" type="text">
								<?php
									if(isset($greska_reg_kor_ime) && !empty($greska_reg_kor_ime)){
										echo "<div class='info-greska'>".$greska_reg_kor_ime."</div>";
									}
								?>
							</div>

							<div class="form-group">
								<label for="reg_email">*Email:</label>
								<input class="form-control form-control-input" placeholder="Email" name="reg_email" id="reg_email" type="text">
								<?php
									if(isset($greska_reg_email) && !empty($greska_reg_email)){
										echo "<div class='info-greska'>".$greska_reg_email."</div>";
									}
								?>
							</div>

							<div class="form-group">
								<label for="reg_lozinka">*Lozinka:</label>
								<input class="form-control form-control-input" placeholder="Lozinka" name="reg_lozinka" id="reg_lozinka" type="password">
								<?php
									if(isset($greska_reg_lozinka) && !empty($greska_reg_lozinka)){
										echo "<div class='info-greska'>".$greska_reg_lozinka."</div>";
									}
								?>
							</div>

							<div class="form-group">
								<label for="reg_slika">Slika:</label>
								<input class="form-control" name="reg_slika" accept="img/*" id="reg_slika" type="file" value="<?php echo '$reg_slika';?>" style="background: #f0f0f0;" />
								<?php
									if(isset($greska_reg_slika) && !empty($greska_reg_slika)){
										echo "<div class='info-greska'>".$greska_reg_slika."</div>";
									}
								?>
							</div>

							<br/>
							<input class="btn btn-primary btn-lg btn-block" id="registracijaKorisnika" name="registracijaKorisnika" type="submit" value="Registriraj"/>
							<?php
							if(isset($poruka) && !empty($poruka)){
							echo "<h3 class='info-obavijest'>".$poruka."</h3>";
							}
							?>
							<script type="text/javascript">
								$('#registracija').ajaxSubmit({success: function(){ /* refresh div */ }); 
							</script>
						</form>
					</div>
				</div>
			</div>
			<div class="clear50"></div>
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<ul class="breadcrumb">
							<li><a href="index.php">Naslovnica</a></li>
							<li class="active">Politika</li>
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