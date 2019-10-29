
-- -----------------------------------------------------
-- Drop triplice
-- -----------------------------------------------------
DROP DATABASE IF EXISTS `triplice` ;

-- -----------------------------------------------------
-- Create database triplice
-- -----------------------------------------------------
CREATE DATABASE IF NOT EXISTS `triplice` DEFAULT CHARACTER SET utf8 ;
USE `triplice` ;

-- -----------------------------------------------------
-- Table `triplice`.`exerciseStatus`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `triplice`.`exerciseStatus` (
  `idExerciseStatus` INT NOT NULL AUTO_INCREMENT,
  `status` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idExerciseStatus`)
);


-- -----------------------------------------------------
-- Table `triplice`.`exercises`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `triplice`.`exercises` (
  `idExercice` INT NOT NULL AUTO_INCREMENT,
  `name` TEXT NOT NULL,
  `fkExerciseStatus` INT NOT NULL,
  PRIMARY KEY (`idExercice`),
  CONSTRAINT `fk_exercises_exerciseStatus1`
    FOREIGN KEY (`fkExerciseStatus`)
    REFERENCES `triplice`.`exerciseStatus` (`idExerciseStatus`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
);


-- -----------------------------------------------------
-- Table `triplice`.`questionTypes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `triplice`.`questionTypes` (
  `idQuestionType` INT NOT NULL AUTO_INCREMENT,
  `type` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idQuestionType`)
);


-- -----------------------------------------------------
-- Table `triplice`.`questions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `triplice`.`questions` (
  `idQuestion` INT NOT NULL AUTO_INCREMENT,
  `label` TEXT NOT NULL,
  `fkExercice` INT NOT NULL,
  `fkQuestionType` INT NOT NULL,
  PRIMARY KEY (`idQuestion`),
  CONSTRAINT `fk_questions_exercises1`
    FOREIGN KEY (`fkExercice`)
    REFERENCES `triplice`.`exercises` (`idExercice`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_questions_questionTypes1`
    FOREIGN KEY (`fkQuestionType`)
    REFERENCES `triplice`.`questionTypes` (`idQuestionType`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
);


-- -----------------------------------------------------
-- Table `triplice`.`takes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `triplice`.`takes` (
  `idTake` INT NOT NULL AUTO_INCREMENT,
  `saveTime` DATETIME NOT NULL,
  PRIMARY KEY (`idTake`)
);


-- -----------------------------------------------------
-- Table `triplice`.`answers`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `triplice`.`answers` (
  `idAnswer` INT NOT NULL AUTO_INCREMENT,
  `content` TEXT NULL,
  `fkQuestion` INT NOT NULL,
  `fkTake` INT NOT NULL,
  PRIMARY KEY (`idAnswer`),
  CONSTRAINT `fk_answers_questions1`
    FOREIGN KEY (`fkQuestion`)
    REFERENCES `triplice`.`questions` (`idQuestion`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_answers_takes1`
    FOREIGN KEY (`fkTake`)
    REFERENCES `triplice`.`takes` (`idTake`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
);
