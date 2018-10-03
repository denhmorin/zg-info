<?php
session_start();

include_once("baza.php");
error_reporting( error_reporting() & ~E_NOTICE);
$veza = spojiSeNaBazu();

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
    </head>
    <body>
    	<header>
    	<?php include("meni.php"); ?>
		</header>

		<div class="body">
			<section class="page-header page-header-dogadanja">
				<div class="container">
					<div class="row">
						<div class="col-md-12">
							<ul class="breadcrumb">
								<li><a href="index.php">Naslovnica</a></li>
								<li class="active">Događanja</li>
							</ul>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<h1>Događanja</h1>
						</div>
					</div>
				</div>
			</section>

			<div class="container">

			<?php
			if (isset($_SESSION["tip_id"]) AND $_SESSION["tip_id"] == 0){
				echo "<div class='row'><div class='col-md-12'><a href='editiranje-vijesti.php?vijest_id='' class='fadeInRight'><button type='button' class='btn btn-primary pull-right'><i class='fa fa-plus-square'></i> Dodaj novu vijest</button></a></div></div><div class='clear20'></div>";
			}
			$upit=mysqli_query($veza, "select * FROM vijest WHERE vrsta_id = 5 ORDER BY datum DESC") or die(mysql_error());
			while($red=mysqli_fetch_array($upit))
			{
			$pretvori_datum_ddmmgggg = date('d.m.Y.',strtotime($red["datum"]));
			  echo "<div class='row'>";
				if (!empty($red["slika"])){
					echo "
					<div class='col-md-4 col-sm-4 col-xs-12'>
						<div class='omotac-slike-vijesti fadeInLeft'>
						<a href='vijest-dogadanja.php?vijest_id=".$red["vijest_id"]."'><img src='".$red["slika"]."' alt='".$red["naziv"]."' class='img-responsive slika-vijesti' />
							<div class='overlay'>
							    <div class='btn-procitaj-vise'>Pročitaj više</div>
							</div>
						</a>
						</div>
					</div>
					<div class='col-md-8 col-sm-8 col-xs-12'>";
				} else {
				echo "
					<div class='col-xs-12'>";
				}
				echo "<div class='okvir-novosti fadeInRight'>
							<p class='datum-vijesti'><i class='fa fa-calendar'></i> ".$pretvori_datum_ddmmgggg."</p>
							<h2 class=''><a href='vijest-dogadanja.php?vijest_id=".$red["vijest_id"]."'>".$red["naziv"]." <span class='crta'>/</span> <span class='tekst'>".$red["kratki_tekst"]."</span></a></h2>";
							if (isset($_SESSION["tip_id"]) AND $_SESSION["tip_id"] == 0){
								echo "<a href='editiranje-vijesti.php?vijest_id={$red["vijest_id"]}'><button type='button' class='btn btn-danger pull-right'><i class='fa fa-edit'></i> Uredi</button></a>";
							}
						echo "</div>
					</div>
				</div>
				<hr>
				";
			}
			?>

			</div>
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<ul class="breadcrumb">
							<li><a href="index.php">Naslovnica</a></li>
							<li class="active">Događanja</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		
		<?php include("podnozje.php"); ?>
	
		<script src="third-party/jquery/jquery.min.js"></script>
		<script src="third-party/bootstrap/js/bootstrap.min.js"></script>
	    <script src="js/whenInViewport.js"></script>
		<script src="js/app.js"></script>
    </body>
</html>
<?php
	zatvoriVezuNaBazu($veza);
?>