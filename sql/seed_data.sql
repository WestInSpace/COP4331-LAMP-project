-- COP4331 Contacts Manager Test Data

USE `ContactsAppDB`;

-- Test users
INSERT INTO `Users` (`FirstName`, `LastName`, `Login`, `Password`, `Role`, `IsDisabled`)
VALUES
('Admin', 'User', 'admin', 'TEST_HASH', 'Admin', FALSE),
('Test', 'User', 'testuser', 'TEST_HASH', 'User', FALSE);

-- Test contacts
INSERT INTO `Contacts` (`FirstName`, `LastName`, `Phone`, `Email`, `UserID`)
VALUES
('John', 'Smith', '407-555-0101', 'john@example.com', 2),
('Jane', 'Doe', '407-555-0102', 'jane@example.com', 2);
