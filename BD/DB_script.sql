
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
  `idExercise` INT NOT NULL AUTO_INCREMENT,
  `name` TEXT NOT NULL,
  `fkExerciseStatus` INT NOT NULL,
  PRIMARY KEY (`idExercise`),
  CONSTRAINT `fk_exercises_exerciseStatus1`
    FOREIGN KEY (`fkExerciseStatus`)
    REFERENCES `triplice`.`exerciseStatus` (`idExerciseStatus`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
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
  `minimumLength` INT NOT NULL DEFAULT 1,
  `order` INT NOT NULL,
  `fkExercise` INT NOT NULL,
  `fkQuestionType` INT NOT NULL,
  PRIMARY KEY (`idQuestion`),
  CONSTRAINT `fk_questions_exercises1`
    FOREIGN KEY (`fkExercise`)
    REFERENCES `triplice`.`exercises` (`idExercise`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_questions_questionTypes1`
    FOREIGN KEY (`fkQuestionType`)
    REFERENCES `triplice`.`questionTypes` (`idQuestionType`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
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
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_answers_takes1`
    FOREIGN KEY (`fkTake`)
    REFERENCES `triplice`.`takes` (`idTake`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
);
