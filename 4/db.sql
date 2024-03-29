CREATE TABLE Person (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  full_name varchar(128) NOT NULL DEFAULT '',
  email varchar(128) NOT NULL DEFAULT '',
  birth_year int(10) NOT NULL DEFAULT 0,
  is_male BOOLEAN NOT NULL DEFAULT 1,
  limbs_amount int(1) NOT NULL DEFAULT 4,
  biography varchar(256) NOT NULL DEFAULT '',
  PRIMARY KEY (id)
);

CREATE TABLE Ability (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  _name varchar(128) NOT NULL,
  PRIMARY KEY (id)
);

INSERT INTO Ability (_name) VALUES ('Superstrength');
INSERT INTO Ability (_name) VALUES ('Superintellect');
INSERT INTO Ability (_name) VALUES ('Invinsibility');

CREATE TABLE Person_Ability (
  person_id int(10) unsigned NOT NULL,
  ability_id int(10) unsigned NOT NULL,
  FOREIGN KEY (person_id)  REFERENCES Person (id),
  FOREIGN KEY (ability_id) REFERENCES Ability (id)
);
