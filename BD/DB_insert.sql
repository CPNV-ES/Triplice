USE `Triplice`;

-- ----------------------------------
-- Exercice status
-- ----------------------------------
INSERT INTO exerciseStatus(`status`) 
VALUES
	("Building"),
	("Answering"),
	("Closed");

-- ----------------------------------
-- 3 News exercices 
-- ----------------------------------
INSERT INTO exercises(`name`,fkExerciseStatus) 
VALUES
	("Calculer avec l'algèbre",1),
	("Test de maths",2),
	("Test blanc",3);

-- ----------------------------------
-- Questions Types 
-- ----------------------------------
INSERT INTO questiontypes(`type`) 
VALUES
	("Single line text"),
	("Multi-line text"),
	("List of single lines");

-- ----------------------------------
-- Create questions for exercices
-- ----------------------------------
INSERT INTO questions(`label`,fkExercice,fkQuestionType) 
VALUES
	("(a+b)(a-b)=",1,3),
	("1+1=",2,1),
	("5/0=",2,1),
	("14+1",2,1),
	("Que feriez-vous si vous pouviez voler?",3,2),
	("Que faut-il savoir pour marcher?",3,2),
	("Quel âge a Pierre s'il est né le 06.05.1998 et que nous sommes le 05.10.2019",3,1);
	
-- ----------------------------------
-- Takes status
-- ----------------------------------
INSERT INTO Takes(`saveTime`) 
VALUES
	("2019-05-10 10:00:23"),
	("2019-05-10 08:57:23"),
	("2019-09-10 08:30:05");
	
-- ----------------------------------
-- Answers of questions
-- ----------------------------------
	INSERT INTO answers(`content`,fkQuestion,fkTake) 
VALUES
	("2",2,1),
	("1",3,1),
	("je crois que c'est 15",4,1),
	('bein voyager au tour du monde 
c''est logique quoi...',5,2),
	("idk",6,2),
	("20 ou plus",7,2),
	("",5,3),
	("équilibre",6,3),
	("",7,3);

	
