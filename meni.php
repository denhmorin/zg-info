<a href="index.php"><h1 class="naslov-stranice">ZG<span class="info">info</span></h1></a>
<?php if(!isset($_SESSION["korisnik_id"])){ ?>
      <a class="btn btn-prijava btn-primary" href="prijava.php" title="Prijava | ZG info"><i class="fa fa-sign-in"></i> Prijava</a></div>
    <?php }else{ ?>
      <div class="odjava">
        <img style="height: 34px; width: auto; float: left; margin-right: 8px; margin-top: 0;" src="<?php echo $_SESSION["slika"];?>"/>
        <span style="padding-top: 14px;display: inline-block; line-height: 0.5; margin-right: 8px;"><?php echo $_SESSION["korisnicko_ime"]; ?></span>
        <a href="prijava.php?odjava" title="Odjava | ZG info"><button type="button" class="btn btn-danger"><i class="fa fa-sign-out"></i> Odjava</button></a>
      </div>
    <?php } ?>
<nav class="navbar navbar-default">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#izbornik" aria-expanded="false">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>

    <div class="collapse navbar-collapse" id="izbornik">
      <ul class="nav navbar-nav navbar-center">
        <li class="<?php if(basename($_SERVER["SCRIPT_NAME"]) == "index.php"){echo "active";} ?>"><a href="index.php"><i class="fa fa-home"></i> Naslovnica</a></li>
        <li class="<?php if(basename($_SERVER["SCRIPT_NAME"]) == "politika.php" OR basename($_SERVER["SCRIPT_NAME"]) == "vijest-politika.php"){echo "active";} ?>"><a href="politika.php">Politika</a></li>
        <li class="<?php if(basename($_SERVER["SCRIPT_NAME"]) == "sport.php" OR basename($_SERVER["SCRIPT_NAME"]) == "vijest-sport.php"){echo "active";} ?>"><a href="sport.php">Sport</a></li>
        <li class="<?php if(basename($_SERVER["SCRIPT_NAME"]) == "biznis.php" OR basename($_SERVER["SCRIPT_NAME"]) == "vijest-biznis.php"){echo "active";} ?>"><a href="biznis.php">Biznis</a></li>
        <li class="<?php if(basename($_SERVER["SCRIPT_NAME"]) == "estrada.php" OR basename($_SERVER["SCRIPT_NAME"]) == "vijest-estrada.php"){echo "active";} ?>"><a href="estrada.php">Estrada</a></li>
        <li class="<?php if(basename($_SERVER["SCRIPT_NAME"]) == "dogadanja.php" OR basename($_SERVER["SCRIPT_NAME"]) == "vijest-dogadanja.php"){echo "active";} ?>"><a href="dogadanja.php">Događanja</a></li>
        <?php
        if (isset($_SESSION["korisnik_id"]) AND $_SESSION["tip_id"] == 0){
          echo "<li class='";
            if(basename($_SERVER["SCRIPT_NAME"]) == "korisnici.php"){echo "active";}
            echo "'><a href='korisnici.php'>Korisnici</a></li>";
        }
        ?>
      </ul>

    </div>
  </div>
</nav>