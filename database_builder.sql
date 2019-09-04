CREATE DATABASE IF NOT EXISTS TypeType;
USE TypeType;

CREATE TABLE IF NOT EXISTS UserEntry(
	UserID int NOT NULL PRIMARY KEY AUTO_INCREMENT,
	UserName varchar(20) NOT NULL,
	Password varchar(20) NOT NULL
);

CREATE TABLE IF NOT EXISTS GeneralData(
	UserID int NOT NULL,
	CreatedTime datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	Speed decimal NOT NULL,
	Mistake int NOT NULL,
	Respond decimal NOT NULL,
	CONSTRAINT UserData_eachtime PRIMARY KEY (UserID, CreatedTime),
	FOREIGN KEY (UserID) REFERENCES UserEntry(UserID)
);

CREATE TABLE IF NOT EXISTS DetailedData(
	UserID int NOT NULL,
	CreatedTime datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	KeyChar char(1) NOT NULL,
	Mistake int NOT NULL,
	Respond decimal NOT NULL,
	Occur int NOT NULL,
	CONSTRAINT UserData_eachTimeChar PRIMARY KEY (UserID, CreatedTime, KeyChar),	
	FOREIGN KEY (UserID) REFERENCES UserEntry(UserID)
);