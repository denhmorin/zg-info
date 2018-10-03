<?php
	function spojiSeNaBazu(){
		$posluzitelj = "localhost";
		$port = 3306;
		$korime = "root";
		$lozinka = "";
		$baza = "zg_info_denis_hmorinski";
		$znakovi = "utf8";
	
		$veza = mysqli_connect($posluzitelj,$korime,$lozinka);
		if(!$veza)
		{
			echo "Problem kod spajanja na bazu!" . mysql_error();
			exit();
		}
		mysqli_select_db($veza, $baza);
		if(!mysqli_select_db($veza, $baza)){
			echo "Problem kod selektiranja baze podataka!" . mysqli_error();
		}
		mysqli_set_charset($veza, "utf8");
		if(!mysqli_set_charset($veza, "utf8")){
			echo "Problem kod postavljanja znakova! " . mysql_error();
			exit;
		}
		return $veza;
	}
	
	function izvrsiUpit($upit){
		global $veza;
		$rezultat = mysqli_query($veza, $upit);
		return $rezultat;
	}
	
	function izvrsiUpit2($upit2){
		global $veza;
		$rezultat2 = mysqli_query($veza, $upit2);
		return $rezultat2;
	}
	
	function zatvoriVezuNaBazu($veza){
		mysqli_close($veza);
	}
?>