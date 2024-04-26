

### University of British Columbia, Vancouver
#### Department of Computer Science

# Post Office Database
Group Number: 38

| Name          | Student Number | CS Alias | Email Address              |
|---------------|----------------|----------|----------------------------|
| Esther Hsueh  | 55621346       | e9x8k    | snow113@students.cs.ubc.ca |
| Reuben Sinha  | 48327316       | x4y2b    | reuben.sinha@gmail.com     |
| Rhi Ann Ng    | 10455657       | h5w2b    | 12rhiann@gmail.com         |


## Project Summary   

Our project is to make an application for a Post Office Mail Management System. The system is designed to enhance the efficiency and reliability of mail delivery services by providing real-time tracking and monitoring capabilities for various types of mail items. The system serves as a comprehensive platform for postal workers, clients, and administrative staff to monitor the progress, location, and the value of mail shipment throughout the delivery process.

## Timeline and Task Breakdown

**Milestone 4 Deliverables**
- SQL script creating tables and data in database
- PDF detailing what we've done
    - Short description
    - How the schema changed
    - Copy of schema and screenshots of data
    - List of all SQL queries and where it can be found in code
    - Screenshots of functionality of query using GUI
- README.txt for other stuff we wanna add

**Milestone 5: Project Demo**

Deadlines:
- Milestone 4 and Milestone 6: due Apr 5
- Project demo (Milestone 4): Apr 8-12

| Date | Tasks |
|----------|----------|
| Week 1 (March 11-15) | <ul> <li> - [x] Wireframe and plan out the GUI [Together]</li> <li> - [x] SQL - DDL <ul> <li> - [x] Mail [Reuben] <ul> <li> - [x] Mailboxes [Reuben] </ul> </li> <li> - [x] PostOfficeBranch [Reuben]</li> <li> - [x] Address [Reuben]</li> <li> - [x] Employees [Rhi Ann] <ul> <li> - [x] Manager [Rhi Ann]</li> <li> - [x] Driver [Rhi Ann]</li> </ul> </li> <li> - [x] Clients [Esther]</li> <li> - [x] Payment [Esther]</li> <li> - [x] Truck [Esther]</li> </ul> </li> </ul> |
| Week 2 (March 18-22) | <ul> <li> - [x] SQL - DML <ul> <li> - [x] Queries: INSERT Operation </li> <li> - [x] Queries: DELETE Operation </li> <li> - [x] Queries: UPDATE Operation </li> <li> - [x] Queries: Projection </li> </ul> </li> </ul> <br> <ul> <li> - [x] Brainstorm other queries [Together]</li> </ul> <br> <ul> <li> **Checklist** <ul> <li> - [x] Queries: Selection</li> <li> - [x] Queries: Projection</li> <li> - [x] Queries: Join</li> <ul> <li> [x] Payment Recipt joining on Payment and Mail tables </li> </ul> <li> - [x] Queries: Aggregation with Group By</li> <ul> <li> [x] Amount of Mail with each branch </li> </ul> <li> - [x] Queries: Aggregation with Having</li> <ul> <li> [x] Branches with a minimum number of employees as provided by the user </li> </ul> <li> - [x] Queries: Nested Aggregation with Group By</li> <ul> <li> [x] Find pieces of mail that have not byet been paid for </li> </ul> <li> - [x] Queries: Division</li> <ul> <li> [x] Gives contact info of clients sending mail to a particular province as chosen by the user </li> </ul> </ul> </li> </ul> |
| Week 3 (March 25-29) | <ul> <li> - [x] Finalizing GUI details and functions in Php </li> </ul> |
| Week 4 (April 1-5) | <ul> <li> - [x] Integrate GUI and SQL components </li> <li> - [x] Debugging [Together]</li> <li> - [x] Prepare Milestone 4 Deliverables</li> </ul> |
| April 5   | Milestone 4 and Milestone 6 due |
| April 8-12  | Project demo (Milestone 5)  |


![PERT Chart](/Python/PERT.gv.svg "PERT Chart in days")

**Fig 1.** Project PERT Chart in Days.


![Gantt Chart](/Python/gantt.svg "Project Gantt Chart")

**Fig 2.** Project Gantt Chart. **Shaded** area indicates total available time. **Dark** area indicates the quickest possible time.

![Scheduling Diagram](/Python/schedule.svg "Project Scheduling Diagram")

**Fig 3.** Project Scheduling Diagram.


## Queries by Customers

### Track a Piece of Mail
- Enter:
    - TrackingID
- Return:
    - All Mail attributes Except TrackingID and NULL values.

SELECT * <br>
FROM MAIL <br>
WHERE TRACKINGID = $TrackingID;

### Location of a Post office branch given BranchID
- Enter:
    - BranchID
- Return:
    - PostOfficeBranch(UnitNum, Street, PostalCode, Country)

```
SELECT UnitNum, Street, PostalCode, Country
FROM POSTOFFICEBRANCH
WHERE BranchID = $BranchID;
```

### Find the Manager of a given Post Office Branch
- Enter:
    - PostOfficeBranch(BranchID)
- Return:
    - Employees(EmployeeName)

```
SELECT E.EMPLOYEENAME
FROM POSTOFFICEBRANCH P, EMPLOYEES E
WHERE P.BRANCHID = $BranchID AND P.BRANCHMANAGER = E.EMPLOYEEID;
```

### Payment Recipt
- Enter:
    - TransactionID
- Return:
    - Payment(Payer, Mail, Price)
    - Mail(Sender ,Reciever, FinalUnitNum, FinalStreet, FinalPostCode, FinalCountry, MailWeight, DeliveryType, Notes)

```
SELECT P.PAYER, P.MAIL, P.PRICE, M.SENDER, M.RECEIVER, M.FINALUNITNUM, M.FINALSTREET, M.FINALPOSTCODE, M.FINALCOUNTRY, M.MAILWEIGHT, M.DELIVERYTYPE, M.NOTES
FROM PAYMENT P
INNER JOIN MAIL M ON P.MAIL = M.TRACKINGID
WHERE P.TRANSACTIONID = $transactionID;
```

### Mail heading to a client
- Enter:
    - Clients(ClientID)/Mail(Reciever)
- Return:
    - Mail(TrackingID, SenderUnitNum, SenderStreet, SenderPostCode, SenderCountry, Sender, Notes)

```
SELECT TrackingID, SenderUnitNum, SenderStreet, SenderPostCode, SenderCountry, Sender, Notes
FROM Mail 
WHERE Receiver = $ClientID;
```

## Queries by the Post Office Mangement

### Clients who have unpaid mail
- Return:
    - Clients(ClientID), COUNT(*)

```
SELECT Receiver AS Clients, COUNT(*)
FROM Mail M
WHERE NOT EXISTS (
    SELECT *
    FROM Payment P
    WHERE P.Mail = M.TrackingID
)
GROUP BY Receiver;
```

### Amount of mail per branch
- Return:
    - COUNT GROUP BY Mail(CurrentBranch) 

```
SELECT CurrentBranch, COUNT(*) 
FROM Mail 
GROUP BY CurrentBranch;
```

### Large Branches
- Enter:
    - numEmployees # Minimum number of employees we want to filter by
- Return:
    - COUNT GROUP BY Employees(WorksAtBranch) HAVING > $threshold

```
SELECT WorksAtBranch AS Branch, COUNT(*)
FROM Employees 
GROUP BY WorksAtBranch 
HAVING COUNT(*) >= $threshold;
```

### Clients who are sinding mail to a particular Province
- Enter:
    - PostalCode(Province)
- Return:
    - Clients(ClientName, Email, PhoneNumber)

```
SELECT C.ClientName, C.Email, C.PhoneNumber
FROM Clients C
WHERE EXISTS (
    SELECT M.Sender
    FROM Mail M
    WHERE EXISTS (
        SELECT M.Sender
        FROM PostalCode P
        WHERE C.ClientID = M.Sender AND M.FinalPostCode = P.PostalCode 
            AND 
        M.FinalCountry = P.Country AND P.Province = $province));
```

### Averge Price paid by each client
- Return:
    - Payment(Payer, AVG(Price))

```
SELECT Payer, AVG(Price) AS AveragePrice 
FROM Payment 
GROUP BY Payer;
```