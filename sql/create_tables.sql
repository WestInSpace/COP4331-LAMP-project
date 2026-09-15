-- COP4331 Contacts Manager Database

CREATE DATABASE IF NOT EXISTS `ContactsAppDB`
    DEFAULT CHARACTER SET utf8mb4
    DEFAULT COLLATE utf8mb4_unicode_ci;

USE `ContactsAppDB`;

-- Users
CREATE TABLE IF NOT EXISTS `Users` (
    `ID` INT NOT NULL AUTO_INCREMENT,
    `FirstName` VARCHAR(50) NOT NULL,
    `LastName` VARCHAR(50) NOT NULL,
    `Login` VARCHAR(50) NOT NULL,
    `Password` VARCHAR(255) NOT NULL,
    `Role` ENUM('Admin', 'User') NOT NULL DEFAULT 'User',
    `IsDisabled` BOOLEAN NOT NULL DEFAULT FALSE,
    PRIMARY KEY (`ID`),
    UNIQUE KEY `idx_users_login` (`Login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Contacts
CREATE TABLE IF NOT EXISTS `Contacts` (
    `ID` INT NOT NULL AUTO_INCREMENT,
    `FirstName` VARCHAR(50) NOT NULL,
    `LastName` VARCHAR(50) NOT NULL,
    `Phone` VARCHAR(25),
    `Email` VARCHAR(100),
    `UserID` INT NOT NULL,
    PRIMARY KEY (`ID`),
    INDEX `idx_contacts_userid` (`UserID`),
    INDEX `idx_contacts_name` (`LastName`, `FirstName`),
    CONSTRAINT `fk_contacts_user`
        FOREIGN KEY (`UserID`) REFERENCES `Users` (`ID`)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Application database user
CREATE USER IF NOT EXISTS 'ContactsAppUser'@'localhost'
    IDENTIFIED BY 'CHANGE_ME';

GRANT ALL PRIVILEGES ON `ContactsAppDB`.* TO 'ContactsAppUser'@'localhost';

CREATE USER IF NOT EXISTS 'ContactsAppUser'@'%'
    IDENTIFIED BY 'CHANGE_ME';

GRANT ALL PRIVILEGES ON `ContactsAppDB`.* TO 'ContactsAppUser'@'%';

FLUSH PRIVILEGES;
