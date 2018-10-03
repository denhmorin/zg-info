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
			<div class="container-fluid">
				<div class="row">
					<div class="col-xs-12 col-sm-6 fadeInLeftXL">
						<div class="heading-wrap">
							<h1 class="section-heading">Najnovije</h1>
						</div>

						<div id="carousel-najnovije" class="carousel slide" data-ride="carousel">
						  <div class="carousel-inner">
						  	<?php
						  	$upit = "SELECT * FROM vijest ORDER BY datum DESC";
							$rezultat = izvrsiUpit($upit);
					  		$brojac = 0;
						  	while($red = mysqli_fetch_array($rezultat)){
						  		if (!empty($red["slika"])){
							  		echo "<div class='item ";
							  		if ($brojac == 0) {
							  			echo "active";
							    		$brojac = 1;
							  		}
							  		echo "'>
							    	<a href='";
							    	switch ($red["vrsta_id"]) {
									    case 1:
									        echo "vijest-politika.php?vijest_id=".$red["vijest_id"];
									        break;
									    case 2:
									    	echo "vijest-sport.php?vijest_id=".$red["vijest_id"];
									        break;
									    case 3:
									        echo "vijest-biznis.php?vijest_id=".$red["vijest_id"];
									        break;
									    case 4:
									        echo "vijest-estrada.php?vijest_id=".$red["vijest_id"];
									        break;
									    case 5:
									        echo "vijest-dogadanja.php?vijest_id=".$red["vijest_id"];
									        break;
									}
							    	echo "'>
								      <div class='carousel-slika-okvir'><img class='img-responsive' src='".$red["slika"]."' alt='".$red["naziv"]."'></div>
								      <div class='caption'>
									      <h2 class='carousel-naslov'>".$red["naziv"]."</h2>
									      <p class='carousel-tekst'>
									      	".$red["kratki_tekst"]."
									      </p>
								      </div>
								      <div class='overlay'>
									    <div class='btn-procitaj-vise'>Pročitaj više</div>
									</div>
								    </a>
							    </div>";
							  	}
						  	}
						  	?>
						    
						  </div>
						  <a class="left carousel-control" href="#carousel-najnovije" data-slide="prev">
						    <span class="glyphicon glyphicon-chevron-left"></span>
						    <span class="sr-only">Prethodna</span>
						  </a>
						  <a class="right carousel-control" href="#carousel-najnovije" data-slide="next">
						    <span class="glyphicon glyphicon-chevron-right"></span>
						    <span class="sr-only">Sljedeća</span>
						  </a>
						</div>
					</div>
					
					<div class="col-xs-12 col-sm-6 fadeInRightXL">
						<div class="heading-wrap">
							<h1 class="section-heading">Najčitanije</h1>
						</div>

						<div id="carousel-najcitanije" class="carousel slide" data-ride="carousel">
						  <div class="carousel-inner">
						  	<?php
						  	$upit = "SELECT * FROM vijest ORDER BY broj_pregleda DESC";
							$rezultat = izvrsiUpit($upit);
					  		$brojac = 0;
						  	while($red = mysqli_fetch_array($rezultat)){
						  		if (!empty($red["slika"])){
							  		echo "<div class='item ";
							  		if ($brojac == 0) {
							  			echo "active";
							    		$brojac = 1;
							  		}
							  		echo "'>
							    	<a href='";
							    	switch ($red["vrsta_id"]) {
									    case 1:
									        echo "vijest-politika.php?vijest_id=".$red["vijest_id"];
									        break;
									    case 2:
									    	echo "vijest-sport.php?vijest_id=".$red["vijest_id"];
									        break;
									    case 3:
									        echo "vijest-biznis.php?vijest_id=".$red["vijest_id"];
									        break;
									    case 4:
									        echo "vijest-estrada.php?vijest_id=".$red["vijest_id"];
									        break;
									    case 5:
									        echo "vijest-dogadanja.php?vijest_id=".$red["vijest_id"];
									        break;
									}
							    	echo "'>
								      <div class='carousel-slika-okvir'><img class='img-responsive' src='".$red["slika"]."' alt='".$red["naziv"]."'></div>
								      <div class='caption'>
									      <h2 class='carousel-naslov'>".$red["naziv"]."</h2>
									      <p class='carousel-tekst'>
									      	".$red["kratki_tekst"]."
									      </p>
								      </div>
								      <div class='overlay'>
									    <div class='btn-procitaj-vise'>Pročitaj više</div>
									</div>
								    </a>
							    </div>";
							  	}
						  	}
						  	?>
						    
						  </div>
						  <a class="left carousel-control" href="#carousel-najcitanije" data-slide="prev">
						    <span class="glyphicon glyphicon-chevron-left"></span>
						    <span class="sr-only">Prethodna</span>
						  </a>
						  <a class="right carousel-control" href="#carousel-najcitanije" data-slide="next">
						    <span class="glyphicon glyphicon-chevron-right"></span>
						    <span class="sr-only">Sljedeća</span>
						  </a>
						</div>
					</div>
				</div>
			</div>
			<div class="container-fluid">
				<div class="row" style="padding-left: 15px; padding-right: 15px;">
					<div class="heading-wrap fadeInLeft">
						<h1 class="section-heading">Kategorije vijesti</h1>
					</div>
					<div class="col-md-4 col-sm-6 col-xs-12">
						<div class="row">
							<div class="col-md-12">
								<div class="kategorije-wrap sport fadeInLeftXL">
									<div class="kategorije-wrap-bg">
										<a href="sport.php" class="kategorije-link" alt="Sport | ZGinfo">
											<h3><i class="fa fa-futbol-o"></i> Sport</h3>
										</a>
										<div class="vijesti-kategorija">
											<?php
										  	$upit=mysqli_query($veza, "select * FROM vijest WHERE vrsta_id = 2 ORDER BY datum DESC LIMIT 3") or die(mysql_error());
											while($red=mysqli_fetch_array($upit)){
										  		echo "<a href='vijest-sport.php?vijest_id=".$red["vijest_id"]."'>
												<h4>".$red["naziv"]."</h4>
												</a>";
										  	}
											?>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="kategorije-wrap biznis fadeInLeftXL">
									<div class="kategorije-wrap-bg">
										<a href="biznis.php" class="kategorije-link" alt="Biznis | ZGinfo">
											<h3><i class="fa fa-bar-chart"></i> Biznis</h3>
										</a>
										<div class="vijesti-kategorija">
											<?php
										  	$upit=mysqli_query($veza, "select * FROM vijest WHERE vrsta_id = 3 ORDER BY datum DESC LIMIT 3") or die(mysql_error());
											while($red=mysqli_fetch_array($upit)){
										  		echo "<a href='vijest-biznis.php?vijest_id=".$red["vijest_id"]."'>
												<h4>".$red["naziv"]."</h4>
												</a>";
										  	}
											?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-6 col-xs-12">
						<div class="kategorije-wrap politika fadeInLeft">
							<div class="kategorije-wrap-bg">
								<a href="politika.php" class="kategorije-link" alt="Politika | ZGinfo">
									<h3><i class="fa fa-users"></i> Politika</h3>
								</a>
								<div class="vijesti-kategorija">
									<?php
								  	$upit=mysqli_query($veza, "select * FROM vijest WHERE vrsta_id = 1 ORDER BY datum DESC LIMIT 7") or die(mysql_error());
									while($red=mysqli_fetch_array($upit)){
								  		echo "<a href='vijest-politika.php?vijest_id=".$red["vijest_id"]."'>
										<h4>".$red["naziv"]."</h4>
										</a>";
								  	}
									?>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-12 col-xs-12">
						<div class="row">
							<div class="col-md-12 col-sm-6">
								<div class="kategorije-wrap estrada fadeInRightXL">
									<div class="kategorije-wrap-bg">
										<a href="estrada.php" class="kategorije-link" alt="Estrada | ZGinfo">
											<h3><i class="fa fa-star"></i> Estrada</h3>
										</a>
										<div class="vijesti-kategorija">
											<?php
										  	$upit=mysqli_query($veza, "select * FROM vijest WHERE vrsta_id = 4 ORDER BY datum DESC LIMIT 3") or die(mysql_error());
											while($red=mysqli_fetch_array($upit)){
										  		echo "<a href='vijest-estrada.php?vijest_id=".$red["vijest_id"]."'>
												<h4>".$red["naziv"]."</h4>
												</a>";
										  	}
											?>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-sm-6">
								<div class="kategorije-wrap dogadanja fadeInRightXL">
									<div class="kategorije-wrap-bg">
										<a href="dogadanja.php" class="kategorije-link" alt="Dogadanja | ZGinfo">
											<h3><i class="fa fa-calendar"></i> Događanja</h3>
										</a>
										<div class="vijesti-kategorija">
											<?php
										  	$upit=mysqli_query($veza, "select * FROM vijest WHERE vrsta_id = 5 ORDER BY datum DESC LIMIT 3") or die(mysql_error());
											while($red=mysqli_fetch_array($upit)){
										  		echo "<a href='vijest-dogadanja.php?vijest_id=".$red["vijest_id"]."'>
												<h4>".$red["naziv"]."</h4>
												</a>";
										  	}
											?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="clear50"></div>
		<?php include("podnozje.php"); ?>
	
		<script src="third-party/jquery/jquery.min.js"></script>
		<script src="third-party/bootstrap/js/bootstrap.min.js"></script>
		<script src="js/whenInViewport.js"></script>
		<script src="js/app.js"></script>
    </body>
</html>
