-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, 
    SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema spotted_db
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `spotted_db` DEFAULT CHARACTER SET utf8;
USE `spotted_db`;

-- -----------------------------------------------------
-- Table `spotted_db`.`utente`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `spotted_db`.`utente` (
  `idutente` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(100) NOT NULL,
  `password` VARCHAR(512) NOT NULL,
  `nome` VARCHAR(45) NOT NULL,
  `attivo` TINYINT NULL DEFAULT 0,
  PRIMARY KEY (`idutente`)
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `spotted_db`.`post`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `spotted_db`.`post` (
  `idpost` INT NOT NULL AUTO_INCREMENT,
  `titolopost` VARCHAR(100) NOT NULL,
  `testopost` MEDIUMTEXT NOT NULL,
  `datapost` DATE NOT NULL,
  `anteprimapost` TINYTEXT NOT NULL,
  `imgpost` VARCHAR(100) NOT NULL,
  `utente` INT NOT NULL,
  PRIMARY KEY (`idpost`),
  INDEX `fk_post_utente_idx` (`utente` ASC),
  CONSTRAINT `fk_post_utente`
    FOREIGN KEY (`utente`)
    REFERENCES `spotted_db`.`utente` (`idutente`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE = InnoDB;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
