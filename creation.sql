/*drop tables to reset*/
drop table ADDRESSES cascade constraints;
drop table CLIENTS cascade constraints;
drop table DRIVER cascade constraints;
drop table EMPLOYEES cascade constraints;
drop table MAIL cascade constraints;
drop table MAILBOXES cascade constraints;
drop table MANAGER cascade constraints;
drop table PAYMENT cascade constraints;
drop table POSTALCODE cascade constraints;
drop table POSTOFFICEBRANCH cascade constraints;
drop table TRUCK cascade constraints;

/*start creating tables*/
CREATE TABLE PostalCode (
    PostalCode VARCHAR2(20),
    Country VARCHAR2(20) NOT NULL,
    City VARCHAR2(20) NOT NULL,
    Province VARCHAR2(2) NOT NULL,
    PRIMARY KEY (PostalCode, Country)
);

CREATE TABLE Clients (
    ClientID INT PRIMARY KEY,
    ClientName VARCHAR2(255) NOT NULL,
    ClientUnitNum VARCHAR2(50),
    ClientStreet VARCHAR2(255),
    ClientPostCode VARCHAR2(20),
    ClientCountry VARCHAR2(100),
    PhoneNumber INT NOT NULL,
    Email VARCHAR2(255) NOT NULL
);


CREATE TABLE PostOfficeBranch (
    BranchID INT PRIMARY KEY,
    UnitNum VARCHAR2(20) NOT NULL,
    Street VARCHAR2(20) NOT NULL,
    PostalCode VARCHAR2(20) NOT NULL,
    BranchManager INT NOT NULL UNIQUE,
    Country VARCHAR2(20)
);

CREATE TABLE MailBoxes (
    MailBoxID INT PRIMARY KEY,
    PostOfficeBranch INT NOT NULL,
    Street VARCHAR2(20) NOT NULL,
    PostalCode VARCHAR2(20) NOT NULL   
);

CREATE TABLE Payment (
    TransactionID INT PRIMARY KEY,
    Payer INT NOT NULL,
    Mail INT NOT NULL,
    Price FLOAT NOT NULL
);
                        
CREATE TABLE Truck (
    LicensePlate VARCHAR2(20) PRIMARY KEY,
    Branch INT NOT NULL,
    MaintenanceDate DATE NOT NULL
);

CREATE TABLE Employees (
    EmployeeID INT PRIMARY KEY,
    EmployeeName VARCHAR2(20) NOT NULL,
    WorksAtBranch INT NOT NULL,
    Department VARCHAR2(20)
);

CREATE TABLE Manager (
    EmployeeID INT PRIMARY KEY,
    ManagesBranch INT NOT NULL UNIQUE
);

CREATE TABLE Driver (
    EmployeeID INT PRIMARY KEY,
    LicenseNumber INT UNIQUE NOT NULL
);

CREATE TABLE Addresses (
    UnitNum VARCHAR2(20),
    Street VARCHAR2(20),
    PostalCode VARCHAR2(7),
    Country VARCHAR2(20),
    BuildingType VARCHAR2(20),
    Resident INT,
    LocalPostOffice INT NOT NULL,
    PRIMARY KEY (UnitNum, Street, PostalCode, Country)
);

CREATE TABLE Mail (
    TrackingID INT PRIMARY KEY,
    CurrentBranch INT NOT NULL,
    SenderUnitNum VARCHAR2(20),
    SenderStreet VARCHAR2(20),
    SenderPostCode VARCHAR2(20),
    SenderCountry VARCHAR2(20),
    NextUnitNum VARCHAR2(20) NOT NULL,
    NextStreet VARCHAR2(20) NOT NULL,
    NextPostCode VARCHAR2(20) NOT NULL,
    NextCountry VARCHAR2(20) NOT NULL,
    FinalUnitNum VARCHAR2(20) NOT NULL,
    FinalStreet VARCHAR2(20) NOT NULL,
    FinalPostCode VARCHAR2(20) NOT NULL,
    FinalCountry VARCHAR2(20) NOT NULL,
    Sender INT,
    Receiver INT NOT NULL,
    MailWeight FLOAT,
    DeliveryType VARCHAR2(20) NOT NULL,
    Notes VARCHAR2(200)
);


/*insert dummy data*/
INSERT ALL 
    INTO PostalCode (PostalCode, Country, City, Province) VALUES ('A1A 1A1', 'Canada', 'Vancouver', 'BC')
    INTO PostalCode (PostalCode, Country, City, Province) VALUES ('V6M 1YZ', 'Canada', 'Vancouver', 'BC')
    INTO PostalCode (PostalCode, Country, City, Province) VALUES ('V5E 1G5', 'Canada', 'Burnaby', 'BC')
    INTO PostalCode (PostalCode, Country, City, Province) VALUES ('V3V 3K5', 'Canada', 'Surrey', 'BC')
    INTO PostalCode (PostalCode, Country, City, Province) VALUES ('B2B 2B2', 'Canada', 'Toronto', 'ON')
    INTO PostalCode (PostalCode, Country, City, Province) VALUES ('L4W 5A6', 'Canada', 'Toronto', 'ON')
    INTO PostalCode (PostalCode, Country, City, Province) VALUES ('C3C 3C3', 'Canada', 'Ottawa', 'ON')
    INTO PostalCode (PostalCode, Country, City, Province) VALUES ('D4D 4D4', 'Canada', 'Montreal', 'QC')
    INTO PostalCode (PostalCode, Country, City, Province) VALUES ('E5E 5E5', 'Canada', 'Edmonton', 'AB')
    SELECT 1 FROM DUAL;

INSERT ALL
    INTO Clients (ClientID, ClientName, ClientUnitNum, ClientStreet, ClientPostCode, ClientCountry, PhoneNumber, Email) VALUES 
        (1111, 'John Doe', '101', 'Main St', 'A1A 1A1', 'Canada', 1234567890, 'john@example.com')
    INTO Clients (ClientID, ClientName, ClientUnitNum, ClientStreet, ClientPostCode, ClientCountry, PhoneNumber, Email) VALUES 
        (2222, 'Jane Smith', '2-202', 'Broadway Ave', 'B2B 2B2', 'Canada', 2345678901, 'jane@example.com')
    INTO Clients (ClientID, ClientName, ClientUnitNum, ClientStreet, ClientPostCode, ClientCountry, PhoneNumber, Email) VALUES 
        (3333, 'Alice Johnson', '3-303', 'Elm St', 'C3C 3C3', 'Canada', 3456789012, 'alice@example.com')
    INTO Clients (ClientID, ClientName, ClientUnitNum, ClientStreet, ClientPostCode, ClientCountry, PhoneNumber, Email) VALUES 
        (4444, 'Bob Brown', '404', 'Oak St', 'D4D 4D4', 'Canada', 4567890123, 'bob@example.com')
    INTO Clients (ClientID, ClientName, ClientUnitNum, ClientStreet, ClientPostCode, ClientCountry, PhoneNumber, Email) VALUES 
        (5555, 'Emily Davis', '5-505', 'Maple Ave', 'E5E 5E5', 'Canada', 5678901234, 'emily@example.com')
    INTO Clients (ClientID, ClientName, ClientUnitNum, ClientStreet, ClientPostCode, ClientCountry, PhoneNumber, Email) VALUES 
        (6666, 'Rhi Ann Ng', '2095', 'W 41st', 'V6M 1YZ', 'Canada', 6041111111, 'rhiann@example.com')
    INTO Clients (ClientID, ClientName, ClientUnitNum, ClientStreet, ClientPostCode, ClientCountry, PhoneNumber, Email) VALUES 
        (7777, 'Reuben Sinha', '12788', 'King George Blvd', 'V3V 3K5', 'Canada', 7787787777, 'reuben@example.com')
    INTO Clients (ClientID, ClientName, ClientUnitNum, ClientStreet, ClientPostCode, ClientCountry, PhoneNumber, Email) VALUES 
        (8888, 'Esther Hsueh', '7229', 'Kingsway', 'V5E 1G5', 'Canada', 6041231234, 'esther@example.com')
    INTO Clients (ClientID, ClientName, ClientUnitNum, ClientStreet, ClientPostCode, ClientCountry, PhoneNumber, Email) VALUES 
        (9999, 'Jean Luc Picard', '2800', 'Skymark Ave', 'L4W 5A6', 'Canada', 3803803801, 'jeanluc@example.com')
    SELECT 1 FROM DUAL;

INSERT ALL
    INTO Addresses (UnitNum, Street, PostalCode, Country, BuildingType, Resident, LocalPostOffice) VALUES 
        ('101', 'Main St', 'A1A 1A1', 'Canada', 'House', 1111, 1)
    INTO Addresses (UnitNum, Street, PostalCode, Country, BuildingType, Resident, LocalPostOffice) VALUES 
        ('2-202', 'Broadway Ave', 'B2B 2B2', 'Canada', 'Apartment', 2222, 2)
    INTO Addresses (UnitNum, Street, PostalCode, Country, BuildingType, Resident, LocalPostOffice) VALUES 
        ('3-303', 'Elm St', 'C3C 3C3', 'Canada', 'Condo', 3333, 3)
    INTO Addresses (UnitNum, Street, PostalCode, Country, BuildingType, Resident, LocalPostOffice) VALUES 
        ('404', 'Oak St', 'D4D 4D4', 'Canada', 'House', 4444, 4)
    INTO Addresses (UnitNum, Street, PostalCode, Country, BuildingType, Resident, LocalPostOffice) VALUES 
        ('5-505', 'Maple Ave', 'E5E 5E5', 'Canada', 'Apartment', 5555, 5)
    INTO Addresses (UnitNum, Street, PostalCode, Country, BuildingType, Resident, LocalPostOffice) VALUES 
        ('2095', 'W 41st', 'V6M 1YZ', 'Canada', 'Basement', 6666, 1)
    INTO Addresses (UnitNum, Street, PostalCode, Country, BuildingType, Resident, LocalPostOffice) VALUES 
        ('12788', 'King George Blvd', 'V3V 3K5', 'Canada', 'House', 7777, 1)
    INTO Addresses (UnitNum, Street, PostalCode, Country, BuildingType, Resident, LocalPostOffice) VALUES 
        ('7229', 'Kingsway', 'V5E 1G5', 'Canada', 'Apartment', 8888, 1)
    INTO Addresses (UnitNum, Street, PostalCode, Country, BuildingType, Resident, LocalPostOffice) VALUES 
        ('2800', 'Skymark Ave', 'L4W 5A6', 'Canada', 'House', 9999, 3)
    SELECT 1 FROM DUAL;

INSERT ALL
    INTO Truck (LicensePlate, Branch, MaintenanceDate) VALUES ('NE866W', 1, DATE '2023-11-01')
    INTO Truck (LicensePlate, Branch, MaintenanceDate) VALUES ('VJ3N8K', 2, DATE '2024-01-02')
    INTO Truck (LicensePlate, Branch, MaintenanceDate) VALUES ('2FAST4U', 3, DATE '2023-07-23')
    INTO Truck (LicensePlate, Branch, MaintenanceDate) VALUES ('L87NE2', 4, DATE '2023-09-15')
    INTO Truck (LicensePlate, Branch, MaintenanceDate) VALUES ('PK3N84', 5, DATE '2023-12-10')
    SELECT 1 FROM DUAL;

INSERT ALL
    INTO Mail (TrackingID, CurrentBranch, SenderUnitNum, SenderStreet, SenderPostCode, SenderCountry, NextUnitNum, NextStreet, NextPostCode, NextCountry, FinalUnitNum, FinalStreet, FinalPostcode, FinalCountry, Sender, Receiver, MailWeight, DeliveryType, Notes) VALUES 
        (1111, 1, '2-202', 'Broadway Ave', 'B2B 2B2', 'Canada', '404', 'Oak St', 'D4D 4D4', 'Canada', '404', 'Oak St', 'D4D 4D4', 'Canada', 2222, 4444, 1.2, 'Standard', 'Fragile')
    INTO Mail (TrackingID, CurrentBranch, SenderUnitNum, SenderStreet, SenderPostCode, SenderCountry, NextUnitNum, NextStreet, NextPostCode, NextCountry, FinalUnitNum, FinalStreet, FinalPostcode, FinalCountry, Sender, Receiver, MailWeight, DeliveryType, Notes) VALUES
        (2222, 2, NULL, NULL, NULL, NULL, '5-505', 'Maple Ave', 'E5E 5E5', 'Canada', '101', 'Main St', 'A1A 1A1', 'Canada', NULL, 1111, 0.8, 'Standard', NULL)
    INTO Mail (TrackingID, CurrentBranch, SenderUnitNum, SenderStreet, SenderPostCode, SenderCountry, NextUnitNum, NextStreet, NextPostCode, NextCountry, FinalUnitNum, FinalStreet, FinalPostcode, FinalCountry, Sender, Receiver, MailWeight, DeliveryType, Notes) VALUES
        (3333, 3, '5-505', 'Maple Ave', 'E5E 5E5', 'Canada', '2-202', 'Broadway Ave', 'B2B 2B2', 'Canada', '101', 'Main St', 'A1A 1A1', 'Canada', NULL, 1111, NULL, 'Express', NULL)
    INTO Mail (TrackingID, CurrentBranch, SenderUnitNum, SenderStreet, SenderPostCode, SenderCountry, NextUnitNum, NextStreet, NextPostCode, NextCountry, FinalUnitNum, FinalStreet, FinalPostcode, FinalCountry, Sender, Receiver, MailWeight, DeliveryType, Notes) VALUES
        (4444, 4, NULL, NULL, NULL, NULL, '101', 'Main St', 'A1A 1A1', 'Canada', '3-303', 'Elm St', 'C3C 3C3', 'Canada', 2222, 3333, NULL, 'Standard', NULL)
    INTO Mail (TrackingID, CurrentBranch, SenderUnitNum, SenderStreet, SenderPostCode, SenderCountry, NextUnitNum, NextStreet, NextPostCode, NextCountry, FinalUnitNum, FinalStreet, FinalPostcode, FinalCountry, Sender, Receiver, MailWeight, DeliveryType, Notes) VALUES
        (5555, 5, '404', 'Oak St', 'D4D 4D4', 'Canada', '3-303', 'Elm St', 'C3C 3C3', 'Canada', '2-202', 'Broadway Ave', 'B2B 2B2', 'Canada', 4444, 2222, 2.2, 'Standard', 'Fragile')
    INTO Mail (TrackingID, CurrentBranch, SenderUnitNum, SenderStreet, SenderPostCode, SenderCountry, NextUnitNum, NextStreet, NextPostCode, NextCountry, FinalUnitNum, FinalStreet, FinalPostcode, FinalCountry, Sender, Receiver, MailWeight, DeliveryType, Notes) VALUES
        (6666, 1, '2095', 'W 41st', 'V6M 1YZ', 'Canada', '2800', 'Skymark Ave', 'L4W 5A6', 'Canada', '2800', 'Skymark Ave', 'L4W 5A6', 'Canada', 6666, 9999, NULL, 'Standard', NULL)
    INTO Mail (TrackingID, CurrentBranch, SenderUnitNum, SenderStreet, SenderPostCode, SenderCountry, NextUnitNum, NextStreet, NextPostCode, NextCountry, FinalUnitNum, FinalStreet, FinalPostcode, FinalCountry, Sender, Receiver, MailWeight, DeliveryType, Notes) VALUES
        (7777, 1, '12788', 'King George Blvd', 'V3V 3K5', 'Canada', '2800', 'Skymark Ave', 'L4W 5A6', 'Canada', '2800', 'Skymark Ave', 'L4W 5A6', 'Canada', 7777, 9999, 0.8, 'Express', NULL)
    INTO Mail (TrackingID, CurrentBranch, SenderUnitNum, SenderStreet, SenderPostCode, SenderCountry, NextUnitNum, NextStreet, NextPostCode, NextCountry, FinalUnitNum, FinalStreet, FinalPostcode, FinalCountry, Sender, Receiver, MailWeight, DeliveryType, Notes) VALUES
        (8888, 3, NULL, NULL, NULL, NULL, '7229', 'Kingsway', 'V5E 1G5', 'Canada', '7229', 'Kingsway', 'V5E 1G5', 'Canada', 9999, 8888, NULL, 'Standard', NULL)
    SELECT 1 FROM DUAL;

INSERT ALL
    INTO PostOfficeBranch (BranchID, UnitNum, Street, PostalCode, BranchManager, Country) VALUES 
        (1, '101', 'Main St', 'A1A 1A1', 101, 'Canada')
    INTO PostOfficeBranch (BranchID, UnitNum, Street, PostalCode, BranchManager, Country) VALUES 
        (2, '5-505', 'Maple Ave', 'E5E 5E5', 102, 'Canada')
    INTO PostOfficeBranch (BranchID, UnitNum, Street, PostalCode, BranchManager, Country) VALUES 
        (3, '2-202', 'Broadway Ave', 'B2B 2B2', 103, 'Canada')
    INTO PostOfficeBranch (BranchID, UnitNum, Street, PostalCode, BranchManager, Country) VALUES 
        (4, '3-303', 'Elm St', 'C3C 3C3', 104, 'Canada')
    INTO PostOfficeBranch (BranchID, UnitNum, Street, PostalCode, BranchManager, Country) VALUES 
        (5, '404', 'Oak St', 'D4D 4D4', 105, 'Canada')
    SELECT 1 FROM DUAL;
    
INSERT ALL
    INTO Employees (EmployeeID, EmployeeName, WorksAtBranch, Department) VALUES 
        (1, 'Alice', 1, 'Delivery')
    INTO Employees (EmployeeID, EmployeeName, WorksAtBranch, Department) VALUES 
        (2, 'Bob', 2, 'Delivery')
    INTO Employees (EmployeeID, EmployeeName, WorksAtBranch, Department) VALUES 
        (3, 'Charlie', 5, 'Delivery')
    INTO Employees (EmployeeID, EmployeeName, WorksAtBranch, Department) VALUES 
        (4, 'David', 4, 'Delivery')
    INTO Employees (EmployeeID, EmployeeName, WorksAtBranch, Department) VALUES 
        (5, 'Emma', 5, 'Delivery' )
    INTO Employees (EmployeeID, EmployeeName, WorksAtBranch, Department) VALUES 
        (6, 'Rina', 1, 'Customer Service')
    INTO Employees (EmployeeID, EmployeeName, WorksAtBranch, Department) VALUES 
        (7, 'Kevin', 2, 'Customer Service')
    INTO Employees (EmployeeID, EmployeeName, WorksAtBranch, Department) VALUES 
        (8, 'Charlie', 3, 'Customer Service')
    INTO Employees (EmployeeID, EmployeeName, WorksAtBranch, Department) VALUES 
        (9, 'Sarah', 4, 'Customer Service')
    INTO Employees (EmployeeID, EmployeeName, WorksAtBranch, Department) VALUES 
        (10, 'Sean', 5, 'Customer Service')
    INTO Employees (EmployeeID, EmployeeName, WorksAtBranch, Department) VALUES 
        (101, 'Kayla', 1, NULL)
    INTO Employees (EmployeeID, EmployeeName, WorksAtBranch, Department) VALUES 
        (102, 'John', 2, NULL)
    INTO Employees (EmployeeID, EmployeeName, WorksAtBranch, Department) VALUES 
        (103, 'Maria', 3, NULL)
    INTO Employees (EmployeeID, EmployeeName, WorksAtBranch, Department) VALUES 
        (104, 'Lily', 4, NULL)
    INTO Employees (EmployeeID, EmployeeName, WorksAtBranch, Department) VALUES 
        (105, 'Penny', 5, NULL)
    INTO Employees (EmployeeID, EmployeeName, WorksAtBranch, Department) VALUES 
        (106, 'Alice', 5, 'Office Entertainment')
    INTO Employees (EmployeeID, EmployeeName, WorksAtBranch, Department) VALUES 
        (107, 'Alice', 5, 'Janitor')
    INTO Employees (EmployeeID, EmployeeName, WorksAtBranch, Department) VALUES 
        (108, 'Alex', 2, 'Vibes')
    INTO Employees (EmployeeID, EmployeeName, WorksAtBranch, Department) VALUES 
        (109, 'Rae', 2, 'Ghost Hunter')
    INTO Employees (EmployeeID, EmployeeName, WorksAtBranch, Department) VALUES 
        (110, 'Claire', 2, 'Exorcist')
    INTO Employees (EmployeeID, EmployeeName, WorksAtBranch, Department) VALUES 
        (111, 'Aria', 2, 'Security')
    INTO Employees (EmployeeID, EmployeeName, WorksAtBranch, Department) VALUES 
        (112, 'Joseph', 2, 'Inventory')
    SELECT 1 FROM DUAL;

INSERT ALL
    INTO Manager (EmployeeID, ManagesBranch) VALUES
        (101, 1)
    INTO Manager (EmployeeID, ManagesBranch) VALUES
        (102, 2)
    INTO Manager (EmployeeID, ManagesBranch) VALUES
        (103, 3)
    INTO Manager (EmployeeID, ManagesBranch) VALUES
        (104, 4)
    INTO Manager (EmployeeID, ManagesBranch) VALUES
        (105, 5)
    SELECT 1 FROM DUAL;

INSERT ALL
    INTO Driver (EmployeeID, LicenseNumber) VALUES
        (1, 123456)
    INTO Driver (EmployeeID, LicenseNumber) VALUES
        (2, 789012)
    INTO Driver (EmployeeID, LicenseNumber) VALUES
        (3, 345678)
    INTO Driver (EmployeeID, LicenseNumber) VALUES
        (4, 901234)
    INTO Driver (EmployeeID, LicenseNumber) VALUES
        (5, 567890)
    SELECT 1 FROM DUAL;
  
INSERT ALL
    INTO MailBoxes (MailBoxID, PostOfficeBranch, Street, PostalCode) VALUES 
        (1, 1, 'Mountain St', 'A1A 1A1')
    INTO MailBoxes (MailBoxID, PostOfficeBranch, Street, PostalCode) VALUES 
        (2, 1, 'Burrard St', 'A1A 1A1')
    INTO MailBoxes (MailBoxID, PostOfficeBranch, Street, PostalCode) VALUES 
        (3, 3, 'Aspen Ave', 'C3C 3C3')
    INTO MailBoxes (MailBoxID, PostOfficeBranch, Street, PostalCode) VALUES 
        (4, 4, 'Kirkland Drive', 'D4D 4D4')
    INTO MailBoxes (MailBoxID, PostOfficeBranch, Street, PostalCode) VALUES 
        (5, 5, 'Lenovo Rd', 'E5E 5E5')
    INTO MailBoxes (MailBoxID, PostOfficeBranch, Street, PostalCode) VALUES 
        (6, 5, 'Lenovo Rd', 'E5E 5E7')
    INTO MailBoxes (MailBoxID, PostOfficeBranch, Street, PostalCode) VALUES 
        (7, 2, 'Willow St', 'H0H 0H0')
    INTO MailBoxes (MailBoxID, PostOfficeBranch, Street, PostalCode) VALUES 
        (8, 3, 'Raymond Ave', 'V5Y 2R8')
    INTO MailBoxes (MailBoxID, PostOfficeBranch, Street, PostalCode) VALUES 
        (9, 4, 'Seva St', 'A12 B3C')
    INTO MailBoxes (MailBoxID, PostOfficeBranch, Street, PostalCode) VALUES 
        (10, 4, 'Ash St', 'F9K 3N2')
    INTO MailBoxes (MailBoxID, PostOfficeBranch, Street, PostalCode) VALUES 
        (11, 1, 'Main St', 'B1D S2F')
    SELECT 1 FROM DUAL;

INSERT ALL 
    INTO Payment (TransactionID, Payer, Mail, Price) VALUES 
        (1, 1111, 1111, 10.99)
    INTO Payment (TransactionID, Payer, Mail, Price) VALUES 
        (2, 2222, 2222, 15.75)
    INTO Payment (TransactionID, Payer, Mail, Price) VALUES 
        (3, 3333, 3333, 20.50)
    INTO Payment (TransactionID, Payer, Mail, Price) VALUES 
        (4, 4444, 4444, 12.25)
    INTO Payment (TransactionID, Payer, Mail, Price) VALUES 
        (5, 5555, 5555, 18.99)
    SELECT 1 FROM DUAL;


/*alter tables to create foreign key relations*/
ALTER TABLE PostOfficeBranch 
    ADD (
        FOREIGN KEY (PostalCode, Country) REFERENCES PostalCode(PostalCode, Country) ON DELETE CASCADE,
        FOREIGN KEY (UnitNum, Street, PostalCode, Country) REFERENCES Addresses(UnitNum, Street, PostalCode, Country) ON DELETE CASCADE,
        FOREIGN KEY (BranchManager) REFERENCES Manager(EmployeeID) ON DELETE CASCADE
);

ALTER TABLE MailBoxes
    ADD (
        FOREIGN KEY (PostOfficeBranch) REFERENCES PostOfficeBranch(BranchID) ON DELETE CASCADE
);

ALTER TABLE Clients
    ADD (
        FOREIGN KEY (ClientUnitNum, ClientStreet, ClientPostCode, ClientCountry) REFERENCES Addresses(UnitNum, Street, PostalCode, Country) ON DELETE CASCADE,
        FOREIGN KEY (ClientPostCode, ClientCountry) REFERENCES PostalCode(PostalCode, Country) ON DELETE CASCADE
);

ALTER TABLE Payment
    ADD (
        FOREIGN KEY (Payer) REFERENCES Clients(ClientID) ON DELETE CASCADE,
        FOREIGN KEY (Mail) REFERENCES Mail(TrackingID) ON DELETE CASCADE
);

ALTER TABLE Addresses
    ADD (
        FOREIGN KEY (PostalCode, Country) REFERENCES PostalCode(PostalCode, Country) ON DELETE CASCADE,
        FOREIGN KEY (Resident) REFERENCES Clients(ClientID) ON DELETE CASCADE,
        FOREIGN KEY (LocalPostOffice) REFERENCES PostOfficeBranch(BranchID) ON DELETE CASCADE
);

ALTER TABLE Truck
    ADD (
        FOREIGN KEY (Branch) REFERENCES PostOfficeBranch(BranchID) ON DELETE CASCADE
);

ALTER TABLE Employees
    ADD (
        FOREIGN KEY (WorksAtBranch) REFERENCES PostOfficeBranch(BranchID) ON DELETE CASCADE
);

ALTER TABLE Manager
    ADD (
        FOREIGN KEY (ManagesBranch) REFERENCES PostOfficeBranch(BranchID) ON DELETE CASCADE,
        FOREIGN KEY (EmployeeID) REFERENCES Employees(EmployeeID) ON DELETE CASCADE
);

ALTER TABLE Driver
    ADD (
        FOREIGN KEY (EmployeeID) REFERENCES Employees(EmployeeID) ON DELETE CASCADE
);

ALTER TABLE Mail
    ADD (
        FOREIGN KEY (CurrentBranch) REFERENCES PostOfficeBranch(BranchID) ON DELETE CASCADE,
        FOREIGN KEY (SenderUnitNum, SenderStreet, SenderPostCode, SenderCountry) REFERENCES Addresses(UnitNum, Street, PostalCode, Country) ON DELETE CASCADE,
        FOREIGN KEY (SenderPostCode, SenderCountry) REFERENCES PostalCode(PostalCode, Country) ON DELETE CASCADE,
        FOREIGN KEY (NextUnitNum, NextStreet, NextPostCode, NextCountry) REFERENCES Addresses(UnitNum, Street, PostalCode, Country) ON DELETE CASCADE,
        FOREIGN KEY (NextPostCode, NextCountry) REFERENCES PostalCode(PostalCode, Country) ON DELETE CASCADE,
        FOREIGN KEY (FinalUnitNum, FinalStreet, FinalPostCode, FinalCountry) REFERENCES Addresses(UnitNum, Street, PostalCode, Country) ON DELETE CASCADE,
        FOREIGN KEY (FinalPostCode, FinalCountry) REFERENCES PostalCode(PostalCode, Country) ON DELETE CASCADE,
        FOREIGN KEY (Sender) REFERENCES Clients(ClientID) ON DELETE CASCADE,
        FOREIGN KEY (Receiver) REFERENCES Clients(ClientID) ON DELETE CASCADE
);