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
  `amministratore` TINYINT NULL DEFAULT 0,
  `imgprofilo` VARCHAR(100) DEFAULT 'default-avatar.png',
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
  `likes` INT NOT NULL DEFAULT 0,
  `anonimo` TINYINT NOT NULL DEFAULT 0,
  `utente` INT NOT NULL,
  PRIMARY KEY (`idpost`),
  INDEX `fk_post_utente_idx` (`utente` ASC),
  CONSTRAINT `fk_post_utente`
    FOREIGN KEY (`utente`)
    REFERENCES `spotted_db`.`utente` (`idutente`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `spotted_db`.`tag`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `spotted_db`.`tag` (
  `idtag` INT NOT NULL AUTO_INCREMENT,
  `nometag` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`idtag`),
  UNIQUE INDEX `nometag_UNIQUE` (`nometag` ASC)
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `spotted_db`.`post_tag`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `spotted_db`.`post_tag` (
  `post` INT NOT NULL,
  `tag` INT NOT NULL,
  PRIMARY KEY (`post`, `tag`),
  INDEX `fk_post_tag_tag_idx` (`tag` ASC),
  INDEX `fk_post_tag_post_idx` (`post` ASC),
  CONSTRAINT `fk_post_tag_post`
    FOREIGN KEY (`post`)
    REFERENCES `spotted_db`.`post` (`idpost`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_post_tag_tag`
    FOREIGN KEY (`tag`)
    REFERENCES `spotted_db`.`tag` (`idtag`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `spotted_db`.`commento`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `spotted_db`.`commento` (
  `idcommento` INT NOT NULL AUTO_INCREMENT,
  `testocommento` TEXT NOT NULL,
  `datacommento` DATETIME NOT NULL,
  `nomeautore` VARCHAR(100) NOT NULL,
  `post` INT NOT NULL,
  `utente` INT NULL,
  PRIMARY KEY (`idcommento`),
  INDEX `fk_commento_post_idx` (`post` ASC),
  INDEX `fk_commento_utente_idx` (`utente` ASC),
  CONSTRAINT `fk_commento_post`
    FOREIGN KEY (`post`)
    REFERENCES `spotted_db`.`post` (`idpost`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_commento_utente`
    FOREIGN KEY (`utente`)
    REFERENCES `spotted_db`.`utente` (`idutente`)
    ON DELETE SET NULL
    ON UPDATE NO ACTION
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `spotted_db`.`user_likes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `spotted_db`.`user_likes` (
  `utente` INT NOT NULL,
  `post` INT NOT NULL,
  PRIMARY KEY (`utente`, `post`),
  INDEX `fk_user_likes_post_idx` (`post` ASC),
  INDEX `fk_user_likes_utente_idx` (`utente` ASC),
  CONSTRAINT `fk_user_likes_utente`
    FOREIGN KEY (`utente`)
    REFERENCES `spotted_db`.`utente` (`idutente`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_likes_post`
    FOREIGN KEY (`post`)
    REFERENCES `spotted_db`.`post` (`idpost`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `spotted_db`.`segnalazione`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `spotted_db`.`segnalazione` (
  `idsegnalazione` INT NOT NULL AUTO_INCREMENT,
  `motivo` VARCHAR(100) NOT NULL,
  `descrizione` TEXT NULL,
  `datasegnalazione` DATETIME NOT NULL,
  `stato` ENUM('pending', 'reviewed', 'resolved', 'dismissed') DEFAULT 'pending',
  `post` INT NULL,
  `commento` INT NULL,
  `utente_segnalante` INT NOT NULL,
  `utente_segnalato` INT NULL,
  PRIMARY KEY (`idsegnalazione`),
  INDEX `fk_segnalazione_post_idx` (`post` ASC),
  INDEX `fk_segnalazione_commento_idx` (`commento` ASC),
  CONSTRAINT `fk_segnalazione_post`
    FOREIGN KEY (`post`)
    REFERENCES `spotted_db`.`post` (`idpost`)
    ON DELETE CASCADE,
  CONSTRAINT `fk_segnalazione_commento`
    FOREIGN KEY (`commento`)
    REFERENCES `spotted_db`.`commento` (`idcommento`)
    ON DELETE CASCADE,
  CONSTRAINT `fk_segnalazione_utente_segnalante`
    FOREIGN KEY (`utente_segnalante`)
    REFERENCES `spotted_db`.`utente` (`idutente`)
    ON DELETE CASCADE,
  CONSTRAINT `fk_segnalazione_utente_segnalato`
    FOREIGN KEY (`utente_segnalato`)
    REFERENCES `spotted_db`.`utente` (`idutente`)
    ON DELETE SET NULL
) ENGINE = InnoDB;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
