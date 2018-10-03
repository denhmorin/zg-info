<?php
session_start();
if (!isset($_SESSION["korisnik_id"]) OR $_SESSION["tip_id"] != 0){
	header('Location: index.php');
}

include_once("baza.php");
error_reporting( error_reporting() & ~E_NOTICE);
$veza = spojiSeNaBazu();

if(isset($_POST["filtriranje_prezime"])){
		$filtriranjePrezime = $_POST["filtriranjePrezime"];
		$greska .= "";
		if(!isset($filtriranjePrezime) || empty($filtriranjePrezime)){
			$greska .= "Unesite prezime ili dio prezimena po kojem želite filtrirati<br>";
		}
		if(empty($greska)){
			$poruka = "Korisnici čije prezime sadrži: ".$filtriranjePrezime;
		}
}
if(isset($_POST["filtriranje_tip"])){
		$upit = "SELECT * FROM tip_korisnika WHERE tip_id = '".$_POST["filtriranjeTipKorisnika"]."'";;
		$tipKorisnika = izvrsiUpit($upit);
		while($red = mysqli_fetch_array($tipKorisnika)){
			$filtriranjeNazivKorisnika = $red["naziv"];
		}
		$filtriranjeTipKorisnika = $_POST["filtriranjeTipKorisnika"];
		$greska .= "";
		if(!isset($filtriranjeTipKorisnika) || $filtriranjeTipKorisnika == "" || $filtriranjeTipKorisnika == 'tip-nije-definiran'){
			$greska .= "Odaberite tip korisnika po kojem želite filtrirati ".$filtriranjeTipKorisnika."<br>";
		}
		if(empty($greska)){
			$poruka = "Korisnici pod tipom korisnika: ".$filtriranjeNazivKorisnika;
		}
}
if(isset($_GET["obrisikorisnika"])) {
	$upit="DELETE FROM korisnik WHERE korisnik_id={$_GET["obrisikorisnika"]}";
	izvrsiUpit($upit);
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
        <title>ZGinfo | Korisnici</title>
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
								<li class="active">Korisnici</li>
							</ul>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<h1>Korisnici</h1>
						</div>
					</div>
				</div>
			</section>
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<table class="table">
							<thead>
								<form id="filtrirajPrezime" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
								<tr style="background: #eee;">
									<td class="small-display-none"><label for="filtriranjePrezime">Prezime: </label></td>
									<td><input class="form-control form-control-input" placeholder="Prezime" name="filtriranjePrezime" id="filtriranjePrezime" type="text"></td>
									<td class="p-none">
										<input class="form-control btn-primary" style="height: 50px;" name="filtriranje_prezime" type="submit" value="Filtriraj"/>
									</td>
								</tr>
								</form>
								<form id="filtrirajTipKorisnika" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
								<tr style="background: #eee;">
									<td class="small-display-none"><label for="filtriranjeTipKorisnika">Tip korisnika: </label></td>
									<td>
										<select class="form-control" style="padding-left: 15px;" name="filtriranjeTipKorisnika" id="filtriranjeTipKorisnika">
											<option value="tip-nije-definiran" selected="selected">----- Odaberite tip korisnika -----</option>
										<?php
										$upit = "SELECT * FROM tip_korisnika";
										$tipKorisnika = izvrsiUpit($upit);
											while($red = mysqli_fetch_array($tipKorisnika)){
												echo "<option value=\"".$red["tip_id"]."\"";
												if($rezultat_ispis['tip_id'] == $red["tip_id"])
												{
													echo " selected=\"selected\" ";
												}
												echo ">".$red["naziv"]."</option>";
											}
										?>
										</select>
									</td>
									<td class="p-none">
										<input class="form-control btn-primary" style="height: 50px;" name="filtriranje_tip" type="submit" value="Filtriraj"/>
									</td>
								</tr>
								</form>
							</thead>
						</table>
						<?php
						echo "<a class='btn btn-primary' style='margin-bottom: 20px;' href='korisnici.php' title='Prikaži sve korisnike'><i class='fa fa-refresh'></i> Prikaži sve korisnike</a>";
						echo "<a class='btn btn-primary' style='margin-bottom: 20px; float: right;' href='korisnici-editiranje.php?tipid=".$tipid."' title='Dodaj novog korisnika'><i class='fa fa-plus'></i> Dodaj novog korisnika</a>";
						if(isset($greska) && !empty($greska)){
							echo "<div class='info-greska'>".$greska."</div>";
						}
						?>	
						<div class="table-responsive">
							<table class="table">
								<thead>
									<tr>
										<td colspan="9" class="info-korisniku">
											<h4>
											<?php
												if(isset($poruka) && !empty($poruka)){
													echo $poruka;
												}else {
													echo "Svi korisnici";
												}
												?>
											</h4>
										</td>
									</tr>
									<tr>
										<td></td>
										<td><b>ID</b></td>
										<td><b>Ime</b></td>
										<td><b>Prezime</b></td>
										<td><b>Korisničko ime</b></td>
										<td><b>lozinka</b></td>
										<td><b>E-mail</b></td>
										<td><b>Tip</b></td>
										<td><b>Status</b></td>
										<td><b>Opcije</b></td>
									</tr>
								</thead>
								<tbody>
								<?php
								if(isset($_POST["filtriranjeTipKorisnika"]) && $_POST["filtriranjeTipKorisnika"] != "tip-nije-definiran"){
									$upit = "SELECT * FROM korisnik WHERE tip_id = '".$_POST["filtriranjeTipKorisnika"]."'";
								}else if(isset($_POST["filtriranjePrezime"]) && !empty($_POST["filtriranjePrezime"])){
									$upit = "SELECT * FROM korisnik WHERE prezime LIKE '%".$_POST["filtriranjePrezime"]."%'";
								}else{
									$upit = "SELECT * FROM korisnik";
								}
								$rezultat = izvrsiUpit($upit);
								while($red = mysqli_fetch_array($rezultat)){
									if($red["slika"] == "" || !file_exists($red["slika"])){
										$red["slika"] = "korisnici/korisnik.jpg";
									}
									echo 
									"<tr>
										<td style='text-align: center;'><img style='height: 50px; width: auto;' src='".$red["slika"]."' /></td>
										<td>".$red["korisnik_id"]."</td>
										<td>".$red["ime"]."</td>
										<td>".$red["prezime"]."</td>
										<td>".$red["korisnicko_ime"]."</td>
										<td>".$red["lozinka"]."</td>
										<td>".$red["email"]."</td>
										<td>".$red["tip_id"]."</td>
										<td>"; if($red["status"] == 1){ echo "Aktivan";}else { echo "Blokiran"; } echo"</td>
										<td>
											<a class='btn btn-danger btn-xs' onclick=\"return confirm('Jeste li sigurni da želite obrisati korisnika?');\" href=\"korisnici.php?obrisikorisnika=".$red["korisnik_id"]."\"><i class='fa fa-remove'></i> Obriši</a>
											<a class='btn btn-primary btn-xs' href='korisnici-editiranje.php?korisnikid=".$red["korisnik_id"]."' title='Uredi korisnika'><i class='fa fa-edit'></i> Uredi</a>
										</td>
									</tr>";
								}
								?>	
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="container">
				<div class="row">
					<div class="col-md-12">


					</div>	
				</div>
			</div>
			<div class="clear50"></div>
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<ul class="breadcrumb">
							<li><a href="index.php">Naslovnica</a></li>
							<li class="active">Korisnici</li>
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