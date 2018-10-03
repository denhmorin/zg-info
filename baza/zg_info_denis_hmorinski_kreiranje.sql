SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `zg_info_denis_hmorinski` DEFAULT CHARACTER SET utf8;
USE `zg_info_denis_hmorinski` ;

/* Tablica `zg_info_denis_hmorinski`.`tip_korisnika` */
CREATE TABLE IF NOT EXISTS `zg_info_denis_hmorinski`.`tip_korisnika` (
  `tip_id` INT(10) NOT NULL AUTO_INCREMENT,
  `naziv` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`tip_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

/* Tablica `zg_info_denis_hmorinski`.`korisnik` */
CREATE TABLE IF NOT EXISTS `zg_info_denis_hmorinski`.`korisnik` (
  `korisnik_id` INT(10) NOT NULL AUTO_INCREMENT,
  `tip_id` INT(10) NOT NULL,
  `status` INT(10) NOT NULL,
  `korisnicko_ime` VARCHAR(50) NOT NULL,
  `lozinka` VARCHAR(32) NOT NULL,
  `ime` VARCHAR(100) NULL,
  `prezime` VARCHAR(100) NULL,
  `email` VARCHAR(50) NULL,
  `slika` VARCHAR(200) NULL,
  PRIMARY KEY (`korisnik_id`),
  UNIQUE INDEX `korisnicko_ime_UNIQUE` (`korisnicko_ime` ASC),
  INDEX `fk_korisnik_tip_korisnika_idx` (`tip_id` ASC),
  CONSTRAINT `fk_korisnik_tip_korisnika`
    FOREIGN KEY (`tip_id`)
    REFERENCES `zg_info_denis_hmorinski`.`tip_korisnika` (`tip_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

/* Tablica `zg_info_denis_hmorinski`.`vrsta` */
CREATE TABLE IF NOT EXISTS `zg_info_denis_hmorinski`.`vrsta` (
  `vrsta_id` INT(10) NOT NULL AUTO_INCREMENT,
  `naziv` VARCHAR(45) NOT NULL,
  `korisnik_id` INT(10) NULL,
  PRIMARY KEY (`vrsta_id`),
  INDEX `fk_vrsta_korisnik1_idx` (`korisnik_id` ASC),
  CONSTRAINT `fk_vrsta_korisnik1`
    FOREIGN KEY (`korisnik_id`)
    REFERENCES `zg_info_denis_hmorinski`.`korisnik` (`korisnik_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

/* Tablica `zg_info_denis_hmorinski`.`vijest` */
CREATE TABLE IF NOT EXISTS `zg_info_denis_hmorinski`.`vijest` (
  `vijest_id` INT(10) NOT NULL AUTO_INCREMENT,
  `vrsta_id` INT(10) NOT NULL,
  `datum` DATE NOT NULL,
  `naziv` VARCHAR(120) NOT NULL,
  `tekst` VARCHAR(10000) NOT NULL,
  `kratki_tekst` VARCHAR(120) NOT NULL,
  `slika` VARCHAR(200) NOT NULL,
  `broj_pregleda` INT(10) NOT NULL,
  INDEX `fk_vijesti_vrsta1_idx` (`vrsta_id` ASC),
  PRIMARY KEY (`vijest_id`),
  CONSTRAINT `fk_vijesti_vrsta1`
    FOREIGN KEY (`vrsta_id`)
    REFERENCES `zg_info_denis_hmorinski`.`vrsta` (`vrsta_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

/* Tablica `zg_info_denis_hmorinski`.`komentar` */
CREATE TABLE IF NOT EXISTS `zg_info_denis_hmorinski`.`komentar` (
  `komentar_id` INT(10) NOT NULL AUTO_INCREMENT,
  `vijest_id` INT(10) NOT NULL,
  `korisnik_id` INT(10) NOT NULL,
  `datum` DATE NOT NULL,
  `vrijeme` TIME NOT NULL,
  `tekst` TEXT NOT NULL,
  INDEX `fk_komentar_vijest1_idx` (`vijest_id` ASC),
  INDEX `fk_komentar_korisnik1_idx` (`korisnik_id` ASC),
  PRIMARY KEY (`komentar_id`),
  CONSTRAINT `fk_komentar_vijest1`
    FOREIGN KEY (`vijest_id`)
    REFERENCES `zg_info_denis_hmorinski`.`vijest` (`vijest_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_komentar_korisnik1`
    FOREIGN KEY (`korisnik_id`)
    REFERENCES `zg_info_denis_hmorinski`.`korisnik` (`korisnik_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
