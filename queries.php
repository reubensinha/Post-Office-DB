<?php

// Forces Errors to print in HTML
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(0);

// Login Stuffz
$config["dbuser"] = "ora_rhiannng";            // change "cwl" to your own CWL
$config["dbpassword"] = "a10455657";    // change to 'a' + your student number
$config["dbserver"] = "dbhost.students.cs.ubc.ca:1522/stu";
$db_conn = NULL;

$success = true;

$show_debug_alert_messages = false;

?>

<html>

<head>
    <title>Queries</title>

    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td,
        th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }
    </style>
</head>

<body>
    <h1>CPSC 304 Group 38: Post Office Management Database</h1>

    <h2>Other Pages</h2>
    <a href="./script.php">
        <p><input type="submit" value="Main Page"></p>
    </a>

    <hr />

    <h1>Selection</h1>
    <p>The values are case sensitive and if you enter in the wrong case, the selection query will not do anything.</p>
    <form method="POST" action="queries.php">
        <input type="hidden" id="selection" name="selectionQuery">
        <p><input type="submit" value="Submit" name="selection"></p>
    </form>

    <hr />


    <h1>Queries</h1>
    <form method='POST' action='queries.php'>
        <label for='queries'>Select a query:</label>
        <select name="selectedQueries">
            <option value="" disabled selected>Select</option>
            <option value='trackMailQueryRequest'>Track a piece of mail</option>
            <option value='branchLocationQueryRequest'>Location of a Post Office Branch</option>
            <option value='branchManagerQueryRequest'>Manager of a branch</option>
            <option value='mailAmtQueryRequest'>Amount of mail in each branch</option>
            <option value='receiptQueryRequest'>Payment Receipt</option>
            <option value='unpaidMailQueryRequest'>Unpaid Mail</option>
            <option value='incomingMailQueryRequest'>Incoming Mail</option>
            <option value='manyEmployeesQueryRequest'>Branches with many employees</option>
            <option value='sentToProvinceQueryRequest'>Clients mailing to provinces</option>
            <option value='avgPriceQueryRequest'>Average Transaction Price per Sender and Receiver</option>
        </select>
        <br><br>
        <input type='submit' value='Submit' name='PickQuery'>
    </form>

    <hr />


    <?php

    function debugAlertMessage($message)
    {
        global $show_debug_alert_messages;
        if ($show_debug_alert_messages) {
            echo "<script type='text/javascript'>alert('" . $message . "');</script>";
        }
    }

    function executePlainSQL($cmdstr)
    { //takes a plain (no bound variables) SQL command and executes it
        global $db_conn, $success;

        $statement = oci_parse($db_conn, $cmdstr);
        //There are a set of comments at the end of the file that describe some of the OCI specific functions and how they work

        if (!$statement) {
            $e = OCI_Error($db_conn); // For oci_parse errors pass the connection handle
            handleError($e);
            $success = False;
        }

        $r = oci_execute($statement, OCI_DEFAULT);
        if (!$r) {
            $e = oci_error($statement); // For oci_execute errors pass the statementhandle
            handleError($e);
            $success = False;
        }

        return $statement;
    }

    function handleError($e)
    {
        $code = $e["code"];         // The Oracle error number.
        // E.g 1400
        $message = $e["message"];   // The Oracle error text.
        // E.g "ORA-01400: cannot insert NULL into ("ORA_REUBEN2"."DEMOTABLE"."ID")"
        $offset = $e["offset"];     // The byte position of an error in the SQL statement.
        // E.g. 31
        $sqlText = $e["sqltext"];   // The SQL statement text.
        // "insert into demoTable values (:bind1, :bind2)"

        switch ($code) {
            case 1400:
                alertMessage("Required fields are Blank! Try Again.");
                break;
            case 1722:
                $string = substr($sqlText, $offset);
                $wrongString = strtok($string, "'");
                alertMessage('Make sure you enter a number instead of "' . $wrongString . '"! Try again.');
                break;
            case 2291: //parent key constraint
                alertMessage("These tables do not exist in other tables. Place them there first.");
                break;
            case 1: //unique constraint
                alertMessage("Certain values already exist in the table, try different values");
                break;
        }
    }

    function printResult($result)
    { //prints results from a select statement
        echo "<table>";
        echo "<tr>";

        $ncols = oci_num_fields($result);

        for ($i = 1; $i <= $ncols; $i++) {
            $column_name  = oci_field_name($result, $i);
            echo "<th>$column_name</th>";
        }
        echo "</tr>";

        while ($row = OCI_Fetch_Array($result, OCI_ASSOC + OCI_RETURN_NULLS)) {
            echo "<tr>";
            $i = 0;
            foreach ($row as $value) {
                echo "<td>$value</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }

    function connectToDB()
    {
        global $db_conn;
        global $config;

        // Your username is ora_(CWL_ID) and the password is a(student number). For example,
        // ora_platypus is the username and a12345678 is the password.
        // $db_conn = oci_connect("ora_cwl", "a12345678", "dbhost.students.cs.ubc.ca:1522/stu");
        $db_conn = oci_connect($config["dbuser"], $config["dbpassword"], $config["dbserver"]);

        if ($db_conn) {
            debugAlertMessage("Database is Connected");
            return true;
        } else {
            debugAlertMessage("Cannot connect to Database");
            $e = OCI_Error(); // For oci_connect errors pass no handle
            echo htmlentities($e['message']);
            return false;
        }
    }

    function disconnectFromDB()
    {
        global $db_conn;

        debugAlertMessage("Disconnect from Database");
        oci_close($db_conn);
    }

    function mailTracker()
    {
        global $db_conn;
        echo
        "<h2>Track a piece of mail</h2>
            <form method='POST' action='queries.php'>
            Mail: <input type='text' name='trackMailText' placeholder='TrackingID'> <br /><br />
            <input type='submit' value='Submit' name='querySubmit'>
            <input type='hidden' value='trackMailQueryRequest' name='selectedQueries'>
            </form>";


        oci_commit($db_conn);

        if (isset($_POST['trackMailText'])) {
            $TrackingID = $_POST["trackMailText"];
            $result = executePlainSQL("SELECT * FROM MAIL WHERE TRACKINGID = " . $TrackingID);
            printResult($result);
        }
    }

    function branchLocation()
    {
        global $db_conn;
        echo "<h2>Location of a Post Office Branch</h2>
        <form method='POST' action='queries.php'>
            Post Office Branch ID: <input type='text' name='branchLocationText' placeholder='BranchID'> <br /><br />
            <input type='submit' value='Submit' name='querySubmit'>
            <input type='hidden' value='branchLocationQueryRequest' name='selectedQueries'>
        </form>";

        oci_commit($db_conn);

        if (isset($_POST['branchLocationText'])) {
            $BranchID = $_POST["branchLocationText"];
            $result = executePlainSQL("SELECT UnitNum, Street, PostalCode, Country 
            FROM POSTOFFICEBRANCH WHERE BranchID = " . $BranchID);
            printResult($result);
        }
    }

    function branchManager()
    {
        global $db_conn;
        echo "<h2>Manager of a branch</h2>
        <form method='POST' action='queries.php'>
            Post Office Branch ID: <input type='text' name='branchManagerText' placeholder='BranchID'> <br /><br />
            <input type='submit' value='Submit' name='querySubmit'>
            <input type='hidden' value='branchManagerQueryRequest' name='selectedQueries'>
        </form>";

        oci_commit($db_conn);

        if (isset($_POST['branchManagerText'])) {
            $BranchID = $_POST["branchManagerText"];
            $result = executePlainSQL("SELECT E.EMPLOYEENAME FROM POSTOFFICEBRANCH P, EMPLOYEES E
            WHERE P.BRANCHID = " . $BranchID . " AND P.BRANCHMANAGER = E.EMPLOYEEID");
            printResult($result);
        }
    }

    function mailAmt()
    {
        global $db_conn;
        echo "<h2>Amount of mail in each branch</h2>
        <form method='POST' action='queries.php'>
            <input type='submit' value='Submit' name='mailAmtSubmit'>
            <input type='hidden' value='Submit' name='querySubmit'>
            <input type='hidden' value='mailAmtQueryRequest' name='selectedQueries'>
        </form>";

        oci_commit($db_conn);

        if (isset($_POST['mailAmtSubmit'])) {
            $result = executePlainSQL("SELECT CurrentBranch, COUNT(*) FROM Mail GROUP BY CurrentBranch");
            printResult($result);
        }
    }

    function receipt()
    {
        global $db_conn;
        echo "<h2>Payment Receipt</h2>
        <form method='POST' action='queries.php'>
            Transaction ID: <input type='text' name='receiptText' placeholder='TransactionID'> <br /><br />
            <input type='submit' value='Submit' name='querySubmit'>
            <input type='hidden' value='receiptQueryRequest' name='selectedQueries'>
        </form>";

        oci_commit($db_conn);

        if (isset($_POST['receiptText'])) {
            $transactionID = $_POST["receiptText"];

            $result = executePlainSQL("SELECT P.PAYER, P.MAIL, P.PRICE, M.SENDER, M.RECEIVER, M.FINALUNITNUM,
                M.FINALSTREET, M.FINALPOSTCODE, M.FINALCOUNTRY, M.MAILWEIGHT, M.DELIVERYTYPE, M.NOTES
                FROM PAYMENT P INNER JOIN MAIL M ON P.MAIL = M.TRACKINGID
                WHERE P.TRANSACTIONID = " . $transactionID);
            printResult($result);
        }
    }

    function unpaidMail()
    {
        global $db_conn;
        echo "<h2>Unpaid mail</h2>
        <form method='POST' action='queries.php'>
            <input type='submit' value='Submit' name='unpaidMailSubmit'>
            <input type='hidden' value='Submit' name='querySubmit'>
            <input type='hidden' value='unpaidMailQueryRequest' name='selectedQueries'>
        </form>";

        oci_commit($db_conn);

        if (isset($_POST['unpaidMailSubmit'])) {
            $result = executePlainSQL("SELECT Receiver AS Clients, COUNT(*)
            FROM Mail M
            WHERE NOT EXISTS (
                SELECT *
                FROM Payment P
                WHERE P.Mail = M.TrackingID
            )
            GROUP BY Receiver");
            printResult($result);
        }
    }

    function incomingMail()
    {
        global $db_conn;
        echo "<h2>Incoming Mail</h2>
        <form method='POST' action='queries.php'>
            Client ID: <input type='text' name='incomingMailText' placeholder='ClientID'> <br /><br />
            <input type='submit' value='Submit' name='querySubmit'>
            <input type='hidden' value='incomingMailQueryRequest' name='selectedQueries'>
        </form>";

        oci_commit($db_conn);

        if (isset($_POST['incomingMailText'])) {
            $ClientID = $_POST["incomingMailText"];
            $result = executePlainSQL("SELECT TrackingID, SenderUnitNum, SenderStreet, SenderPostCode, SenderCountry, Sender, Notes
            FROM Mail WHERE Receiver = " . $ClientID);
            printResult($result);
        }
    }

    function manyEmployees()
    {
        global $db_conn;
        echo "<h2>Branches with many employees</h2>
        <form method='POST' action='queries.php'>
            Number of employees: <input type='text' name='manyEmployeesText' placeholder='Insert threshold here'> <br /><br />
            <input type='submit' value='Submit' name='querySubmit'>
            <input type='hidden' value='manyEmployeesQueryRequest' name='selectedQueries'>
        </form>";

        oci_commit($db_conn);

        if (isset($_POST['manyEmployeesText'])) {
            $threshold = $_POST["manyEmployeesText"];
            $result = executePlainSQL("SELECT WorksAtBranch AS Branch, COUNT(*)
            FROM Employees GROUP BY WorksAtBranch HAVING COUNT(*) >= " . $threshold);
            printResult($result);
        }
    }

    function sentToProvince()
    {
        global $db_conn;
        echo "<h2>Clients mailing to provinces</h2>
        <p>Find the name and contact info of clients who have sent mail to given province</p>
        <form method='POST' action='queries.php'>
            Insert Province name: <input type='text' name='sentToProvinceText' placeholder='Province'> <br /><br />
            <input type='submit' value='Submit' name='querySubmit'>
            <input type='hidden' value='sentToProvinceQueryRequest' name='selectedQueries'>
        </form>";

        oci_commit($db_conn);

        if (isset($_POST['sentToProvinceText'])) {
            $province = $_POST["sentToProvinceText"];
            $result = executePlainSQL("SELECT C.ClientName, C.Email, C.PhoneNumber
            FROM Clients C
            WHERE EXISTS (
                SELECT M.Sender
                FROM Mail M
                WHERE EXISTS (
                    SELECT M.Sender
                    FROM PostalCode P
                    WHERE C.ClientID = M.Sender AND M.FinalPostCode = P.PostalCode 
                        AND M.FinalCountry = P.Country AND P.Province = '" . $province . "'))");
            printResult($result);
        }
    }



    //    Average Transaction Price per Sender and Receiver
    function avgPrice()
    {
        global $db_conn;
        echo "<h2>Average Transaction Price per Payer</h2>       
        <form method='POST' action='queries.php'>
        <br /><br />
         <input type='submit' value='Submit' name='avgPriceSubmit'>
         <input type='hidden' value='Submit' name='querySubmit'>
         <input type='hidden' value='avgPriceQueryRequest' name='selectedQueries'>
        </form>";

        oci_commit($db_conn);

        if (isset($_POST['avgPriceSubmit'])) {
            $result = executePlainSQL("SELECT Payer, AVG(Price) AS AveragePrice FROM Payment GROUP BY Payer");
            printResult($result);
        }
    }



    function handleSelection($table)
    {
        global $db_conn;
        $result = executePlainSQL("SELECT * FROM " . $table);

        echo "<p>Values are case sensitive</p>";
        echo "<form method='POST' action='queries.php'>";
        echo "<input type='hidden' id='selectionQueryRequest' name='selectionQueryRequest'>";
        echo "<input type='hidden' value='" . $table . "' id='table' name='table'>";
        echo "<p>Enter the values:</p>";

        $ncols = oci_num_fields($result);

        //loop thru all the columns of the table
        //what you want to update
        for ($i = 1; $i <= $ncols; $i++) {
            $column_name  = oci_field_name($result, $i);
            echo "<br>" . $column_name . ": 
            <input type='text' name='selection" . $column_name . "Name'>
            <select name='andor" . $column_name . "' id='andor" . $column_name . "'>
                <option value='AND'>AND</option>
                <option value='OR'>OR</option>
            </select>
            <br>";
        }

        echo "<input type='submit' value='Submit Form' name='selectionSubmit'></p>";
        echo "</form>";

        oci_commit($db_conn);
    }

    function submitSelection()
    {
        global $db_conn;
        $table_name = $_POST["table"];
        $table = executePlainSQL("SELECT * FROM " . $table_name);
        $ncols = oci_num_fields($table);

        $conditions = "";
        $prev_col_have_val = false;
        //loops thru all columns
        for ($i = 1; $i <= $ncols; $i++) {
            $column_name  = oci_field_name($table, $i);
            $andor = $_POST["andor" . $column_name];
            $values = $_POST["selection" . $column_name . "Name"];

            //check that we actually have user-given inputs for this column
            //otherwise, skip this column
            if ($values != "") {
                if ($prev_col_have_val) {
                    $conditions .= " AND ";
                }

                $conditions .= "(";

                //set up user-given values as an array
                $arr = explode(",", $values);
                $values_arr = array_map('trim', $arr);
                $n_array = count($values_arr);

                //iterate through all the values provided by user
                //create conditions statement for this particular column
                for ($j = 0; $j < $n_array; $j++) {
                    if ($column_name == "MAINTENANCEDATE") {
                        $conditions .= $column_name . " = DATE '" . $values_arr[$j] . "'";
                    } else {
                        $conditions .= $column_name . " = '" . $values_arr[$j] . "'";
                    }

                    if ($j != $n_array - 1) {
                        $conditions .= " " . $andor . " ";
                    }
                }
                $conditions .= ")";
                $prev_col_have_val = true;
            }
        }

        $result = executePlainSQL("SELECT * FROM " . $table_name . " WHERE " . $conditions);
        printResult($result);
        oci_commit($db_conn);
    }

    function handlePOSTRequest()
    {
        if (connectToDB()) {
            if (isset($_POST['selectedQueries'])) {
                $query = $_POST['selectedQueries'];

                switch ($query) {
                    case 'trackMailQueryRequest':
                        mailTracker();
                        break;
                    case 'branchLocationQueryRequest':
                        branchLocation();
                        break;
                    case 'branchManagerQueryRequest':
                        branchManager();
                        break;
                    case 'mailAmtQueryRequest':
                        mailAmt();
                        break;
                    case 'receiptQueryRequest':
                        receipt();
                        break;
                    case 'unpaidMailQueryRequest':
                        unpaidMail();
                        break;
                    case 'incomingMailQueryRequest':
                        incomingMail();
                        break;
                    case 'manyEmployeesQueryRequest':
                        manyEmployees();
                        break;
                    case 'sentToProvinceQueryRequest':
                        sentToProvince();
                        break;
                    case 'avgPriceQueryRequest':
                        avgPrice();
                        break;
                }
            } else if (array_key_exists('selectionQuery', $_POST)) {
                pickTable("selection");
            } else if (array_key_exists('selectionTable', $_POST)) {
                $table = $_POST['selectionTable'];
                handleSelection($table);
            } else if (array_key_exists('selectionSubmit', $_POST)) {
                submitSelection();
            }
            disconnectFromDB();
        }
    }

    function pickTable($current)
    {
        global $db_conn;

        echo
        "<h2>Pick a table</h2>
        <form method='POST' action='queries.php'>
        <label for='tables'>Choose a table:</label>
        <select name='" . $current . "Table' id='" . $current . "Table'>
            <option value=''>Select</option>
            <option value='POSTALCODE'>Postal Code</option>
            <option value='CLIENTS'>Clients</option>
            <option value='POSTOFFICEBRANCH'>Post Office Branch</option>
            <option value='MAILBOXES'>Mailboxes</option>
            <option value='PAYMENT'>Payment</option>
            <option value='TRUCK'>Truck</option>
            <option value='EMPLOYEES'>Employees</option>
            <option value='MANAGER'>Manager</option>
            <option value='DRIVER'>Driver</option>
            <option value='ADDRESSES'>Addresses</option>
            <option value='MAIL'>Mail</option>
        </select>
        <br><br>
        <input type='submit' value='Submit' name='PickTable'>
        </form>";

        oci_commit($db_conn);
    }

    if (
        isset($_POST['selectedQueries']) ||
        isset($_POST['querySubmit']) ||
        isset($_POST['selection']) ||
        isset($_POST['selectionTable']) ||
        isset($_POST['selectionQueryRequest'])
    ) {

        handlePOSTRequest();
    }

    ?>
</body>

</html>