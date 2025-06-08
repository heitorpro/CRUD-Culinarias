-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,
NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema gerenciador_receitas
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema gerenciador_receitas
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `gerenciador_receitas` DEFAULT CHARACTER SET utf8 ;
USE `gerenciador_receitas` ;

-- -----------------------------------------------------
-- Table `gerenciador_receitas`.`receitas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `gerenciador_receitas`.`receitas` (
  `idReceita` INT(11) NOT NULL AUTO_INCREMENT,
  `nome_receita` VARCHAR(255) NOT NULL,
  `categoria` VARCHAR(100) NOT NULL,
  `tempo_preparo_minutos` INT(11) NULL DEFAULT NULL,
  `rendimento` VARCHAR(100) NULL DEFAULT NULL,
  `instrucoes_preparo` TEXT NOT NULL,
  PRIMARY KEY (`idReceita`))
ENGINE = InnoDB
AUTO_INCREMENT = 6
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `gerenciador_receitas`.`ingredientes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `gerenciador_receitas`.`ingredientes` (
  `idIngrediente` INT(11) NOT NULL AUTO_INCREMENT,
  `nome_ingrediente` VARCHAR(255) NOT NULL,
  `quantidade` VARCHAR(100) NULL DEFAULT NULL,
  `unidade_medida` VARCHAR(50) NULL DEFAULT NULL,
  `fk_idReceita` INT(11) NOT NULL,
  PRIMARY KEY (`idIngrediente`),
  INDEX `fk_ingredientes_receitas_idx` (`fk_idReceita` ASC) VISIBLE,
  CONSTRAINT `fk_ingredientes_receitas`
    FOREIGN KEY (`fk_idReceita`)
    REFERENCES `gerenciador_receitas`.`receitas` (`idReceita`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 10
DEFAULT CHARACTER SET = utf8;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
