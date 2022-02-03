-- MySQL Script generated by MySQL Workbench
-- Tue Jan  2 00:09:40 2018
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema bd_personale
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `bd_personale` ;

-- -----------------------------------------------------
-- Schema bd_personale
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `bd_personale` DEFAULT CHARACTER SET utf8 ;
USE `bd_personale` ;

-- -----------------------------------------------------
-- Table `bd_personale`.`usuarios`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `bd_personale`.`usuarios` ;

CREATE TABLE IF NOT EXISTS `bd_personale`.`usuarios` (
  `id_usuario` INT NOT NULL AUTO_INCREMENT,
  `usuario` VARCHAR(150) NULL,
  `identificacao` VARCHAR(50) NULL,
  `email` VARCHAR(150) NULL,
  `data_cadastro` DATE NULL,
  `senha` TEXT NULL,
  `status` INT NULL,
  `nome_completo` VARCHAR(250) NULL,
  `tel` VARCHAR(45) NULL,
  `cel` VARCHAR(45) NULL,
  `logradouro` VARCHAR(250) NULL,
  `num_residencia` VARCHAR(45) NULL,
  `cidade` VARCHAR(250) NULL,
  `uf` VARCHAR(10) NULL,
  `bairro` VARCHAR(250) NULL,
  `cep` VARCHAR(10) NULL,
  `complemento` VARCHAR(250) NULL,
  `token` VARCHAR(250) NULL,
  `img_profile` VARCHAR(250) NULL,
  `outras_infos` VARCHAR(250) NULL,
  `conectado` INT NULL,
  PRIMARY KEY (`id_usuario`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bd_personale`.`filiais`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `bd_personale`.`filiais` ;

CREATE TABLE IF NOT EXISTS `bd_personale`.`filiais` (
  `id_filial` INT NOT NULL AUTO_INCREMENT,
  `codigo` VARCHAR(45) NULL,
  `nome` VARCHAR(150) NULL,
  `endereco` VARCHAR(150) NULL,
  `tel_1` VARCHAR(45) NULL,
  `tel_2` VARCHAR(45) NULL,
  `email` VARCHAR(150) NULL,
  `site` VARCHAR(150) NULL,
  `status` INT NULL,
  PRIMARY KEY (`id_filial`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bd_personale`.`grupos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `bd_personale`.`grupos` ;

CREATE TABLE IF NOT EXISTS `bd_personale`.`grupos` (
  `id_grupo` INT NOT NULL AUTO_INCREMENT,
  `fk_filial` INT NULL,
  `nome` VARCHAR(100) NULL,
  `cod` VARCHAR(45) NULL,
  `descricao` VARCHAR(150) NULL,
  `data_criacao` DATE NULL,
  `status` INT NULL,
  `admin` INT NULL,
  PRIMARY KEY (`id_grupo`),
  INDEX `fk_filial_idx` (`fk_filial` ASC),
  CONSTRAINT `fk_filial`
    FOREIGN KEY (`fk_filial`)
    REFERENCES `bd_personale`.`filiais` (`id_filial`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bd_personale`.`usuarios_x_grupos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `bd_personale`.`usuarios_x_grupos` ;

CREATE TABLE IF NOT EXISTS `bd_personale`.`usuarios_x_grupos` (
  `id_usuario_x_grupo` INT NOT NULL AUTO_INCREMENT,
  `fk_usuario` INT NULL,
  `fk_grupo` INT NULL,
  PRIMARY KEY (`id_usuario_x_grupo`),
  INDEX `fk_usuario_idx` (`fk_usuario` ASC),
  INDEX `fk_grupo_idx` (`fk_grupo` ASC),
  CONSTRAINT `fk_usuario_x5354435x`
    FOREIGN KEY (`fk_usuario`)
    REFERENCES `bd_personale`.`usuarios` (`id_usuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_grupo_x5354435x`
    FOREIGN KEY (`fk_grupo`)
    REFERENCES `bd_personale`.`grupos` (`id_grupo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bd_personale`.`acessos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `bd_personale`.`acessos` ;

CREATE TABLE IF NOT EXISTS `bd_personale`.`acessos` (
  `id_acesso` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(200) NULL,
  `tipo` ENUM('VER_ED_REM','SIM_NAO') NULL,
  `cod` VARCHAR(45) NULL,
  `ordem` INT NULL,
  PRIMARY KEY (`id_acesso`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bd_personale`.`grupos_x_acessos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `bd_personale`.`grupos_x_acessos` ;

CREATE TABLE IF NOT EXISTS `bd_personale`.`grupos_x_acessos` (
  `id_grupo_x_acesso` INT NOT NULL AUTO_INCREMENT,
  `fk_grupo` INT NULL,
  `fk_acesso` INT NULL,
  `valor` VARCHAR(45) NULL,
  PRIMARY KEY (`id_grupo_x_acesso`),
  INDEX `fk_acesso_idx` (`fk_acesso` ASC),
  INDEX `fk_grupo_idx` (`fk_grupo` ASC),
  CONSTRAINT `fk_grupo_2x`
    FOREIGN KEY (`fk_grupo`)
    REFERENCES `bd_personale`.`grupos` (`id_grupo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_acesso_2x`
    FOREIGN KEY (`fk_acesso`)
    REFERENCES `bd_personale`.`acessos` (`id_acesso`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bd_personale`.`departamentos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `bd_personale`.`departamentos` ;

CREATE TABLE IF NOT EXISTS `bd_personale`.`departamentos` (
  `id_departamento` INT NOT NULL AUTO_INCREMENT,
  `fk_filial` INT NULL,
  `nome` VARCHAR(150) NULL,
  `codigo` VARCHAR(45) NULL,
  `status` INT NULL,
  PRIMARY KEY (`id_departamento`),
  INDEX `fk_filial_departamento_idx` (`fk_filial` ASC),
  CONSTRAINT `fk_filial_departamento`
    FOREIGN KEY (`fk_filial`)
    REFERENCES `bd_personale`.`filiais` (`id_filial`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bd_personale`.`cargos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `bd_personale`.`cargos` ;

CREATE TABLE IF NOT EXISTS `bd_personale`.`cargos` (
  `id_cargo` INT NOT NULL AUTO_INCREMENT,
  `codigo` VARCHAR(45) NULL,
  `nome` VARCHAR(150) NULL,
  `status` INT NULL,
  `fk_departamento` INT NULL,
  PRIMARY KEY (`id_cargo`),
  INDEX `fk_departamento_cargo_idx` (`fk_departamento` ASC),
  CONSTRAINT `fk_departamento_cargo`
    FOREIGN KEY (`fk_departamento`)
    REFERENCES `bd_personale`.`departamentos` (`id_departamento`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bd_personale`.`usuarios_x_cargos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `bd_personale`.`usuarios_x_cargos` ;

CREATE TABLE IF NOT EXISTS `bd_personale`.`usuarios_x_cargos` (
  `id_usuario_cargo` INT NOT NULL AUTO_INCREMENT,
  `fk_usuario` INT NULL,
  `fk_cargo` INT NULL,
  `padrao` INT NULL,
  PRIMARY KEY (`id_usuario_cargo`),
  INDEX `fk_usuario_cargo_idx` (`fk_usuario` ASC),
  INDEX `fk_cargo_usuario_idx` (`fk_cargo` ASC),
  CONSTRAINT `fk_usuario_x_cargo`
    FOREIGN KEY (`fk_usuario`)
    REFERENCES `bd_personale`.`usuarios` (`id_usuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_cargo_x_usuario`
    FOREIGN KEY (`fk_cargo`)
    REFERENCES `bd_personale`.`cargos` (`id_cargo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
