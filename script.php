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

$pKeys = array(
    "POSTALCODE" => array("PostalCode", "Country"),
    "CLIENTS" => array("ClientID"),
    "POSTOFFICEBRANCH" => array("BranchID"),
    "MAILBOXES" => array("MailBoxID"),
    "PAYMENT" => array("TransactionID"),
    "TRUCK" => array("LicensePlate"),
    "EMPLOYEES" => array("EmployeeID"),
    "MANAGER" => array("EmployeeID"),
    "DRIVER" => array("EmployeeID"),
    "ADDRESSES" => array("UnitNum", "Street", "PostalCode", "Country"),
    "MAIL" => array("TrackingID"),
);

$requiredFields = array(
    "POSTALCODE" => array("PostalCode", "Country", "City", "Province"),
    "CLIENTS" => array("ClientID", "ClientName", "PhoneNumber", "Email"),
    "POSTOFFICEBRANCH" => array("BranchID", "UnitNum", "Street", "PostalCode", "BranchManager"),
    "MAILBOXES" => array("MailBoxID", "PostOfficeBranch", "Street", "PostalCode"),
    "PAYMENT" => array("TransactionID", "Payer", "Mail", "Price"),
    "TRUCK" => array("LicensePlate", "Branch", "MaintenanceDate"),
    "EMPLOYEES" => array("EmployeeID", "EmployeeName", "WorksAtBranch"),
    "MANAGER" => array("EmployeeID", "ManagesBranch"),
    "DRIVER" => array("EmployeeID", "LicenseNumber"),
    "ADDRESSES" => array("UnitNum", "Street", "PostalCode", "Country", "LocalPostOffice"),
    "MAIL" => array(
        "TrackingID", "NextUnitNum", "NextStreet", "NextPostCode", "NextCountry", "FinalUnitNum",
        "FinalStreet", "FinalPostCode", "FinalCountry", "Receiver", "DeliveryType"
    ),
);

$show_debug_alert_messages = false;

?>

<html>

<head>
    <title>Post Office Management</title>

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
    <a href="./queries.php">
        <p><input type="submit" value="Queries"></p>
    </a>

    <hr />

    <h2>Display</h2>
    <p>Either display all the tables or pick one to display:</p>
    <form method="POST" action="script.php">
        <!-- "action" specifies the file or page that will receive the form data for processing. As with this example, it can be this same file. -->
        <input type="hidden" id="displayAllTables" name="displayAllTables">
        <p><input type="submit" value="Display All Tables" name="display"></p>
    </form>
    <form method='POST' action='script.php'>
        <select name='displayTable' id='displayTable'>
            <option value="">Select</option>
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
        <input type='submit' value='Submit' name='displayTableSubmit'>
    </form>

    <hr />

    <h2>Update</h2>
    <p>The values are case sensitive and if you enter in the wrong case, the update statement will not do anything.</p>
    <form method="POST" action="script.php">
        <!-- "action" specifies the file or page that will receive the form data for processing. As with this example, it can be this same file. -->
        <input type="hidden" id="updateTables" name="updateTables">
        <p><input type="submit" value="Update Tables" name="display"></p>
    </form>
    <hr />

    <h2>Insert</h2>
    <form method="POST" action="script.php">
        <!-- "action" specifies the file or page that will receive the form data for processing. As with this example, it can be this same file. -->
        <input type="hidden" id="insertTables" name="insertTables">
        <p><input type="submit" value="Insert Tables" name="display"></p>
    </form>
    <hr />

    <h2>Delete</h2>
    <p>The values are case sensitive and if you enter in the wrong case, the delete statement will not do anything.</p>
    <form method="POST" action="script.php">
        <input type="hidden" id="deleteTables" name="deleteTables">
        <p><input type="submit" value="Delete Tuples" name="display"></p>
    </form>
    <hr />

    <h2>Project</h2>
    <form method="POST" action="script.php">
        <p><input type="submit" value="Project Tables" name="projectTables"></p>
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

    function alertMessage($message)
    {
        echo "<script type='text/javascript'>alert('" . $message . "');</script>";
    }


    function executePlainSQL($cmdstr)
    { //takes a plain (no bound variables) SQL command and executes it
        global $db_conn, $success;

        $statement = oci_parse($db_conn, $cmdstr);

        if (!$statement) {
            $e = OCI_Error($db_conn); // For oci_parse errors pass the connection handle
            handleError($e);
            $success = False;
            return $e;
        }

        $r = oci_execute($statement, OCI_DEFAULT);
        if (!$r) {
            $e = oci_error($statement); // For oci_execute errors pass the statementhandle
            handleError($e);
            $success = False;
            return $e;
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

    function printResult($result, $table)
    { //prints results from a select statement
        echo "<br>Retrieved data from table " . $table . ":<br>";
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


    function printAll()
    {
        handleDisplayRequest("Mail");
        handleDisplayRequest("PostalCode");
        handleDisplayRequest("MailBoxes");
        handleDisplayRequest("Clients");
        handleDisplayRequest("Payment");
        handleDisplayRequest("Truck");
        handleDisplayRequest("PostOfficeBranch");
        handleDisplayRequest("Addresses");
        handleDisplayRequest("Employees");
        handleDisplayRequest("Manager");
        handleDisplayRequest("Driver");
    }


    function handleDisplayRequest($table)
    {
        global $db_conn;

        //TODO: Combine certain child tables with parnet tables

        // if ($table == "EMPLOYEES") {
        //     $result = executePlainSQL("
        //         SELECT * FROM 
        //         EMPLOYEES E, MANAGER M, DRIVER D
        //         WHERE
        //             (SELECT E.EmployeeID 
        //             UNION M.EmployeeID) OR
        //             SELECT E.EmployeeID 
        //             UNION D.EmployeeID");
        // } else if ($table == "MANAGER") {
        //     $result = executePlainSQL("
        //         SELECT * 
        //         FROM EMPLOYEES E, MANAGER M
        //         WHERE M.EmployeeID = E.EmployeeID");
        // } else if ($table == "DRIVER") {
        //     $result = executePlainSQL("
        //         SELECT * 
        //         FROM EMPLOYEES E, DRIVER D
        //         WHERE D.EmployeeID = E.EmployeeID");
        // } else {
        //     $result = executePlainSQL("SELECT * FROM " . $table);
        // }

        $result = executePlainSQL("SELECT * FROM " . $table);

        printResult($result, $table);
    }


    function pickTable($current)
    {
        global $db_conn;

        echo
        "<h2>Pick a table</h2>
        <form method='POST' action='script.php'>
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

    function handleUpdateTable($table)
    {
        global $db_conn;
        global $pKeys;
        $pKey = $pKeys[$table];
        $result = executePlainSQL("SELECT * FROM " . $table);
        printResult($result, $table);

        echo "<p>Values are case sensitive</p>";
        echo "<form method='POST' action='script.php'>";
        echo "<input type='hidden' id='updateQueryRequest' name='updateQueryRequest'>";
        echo "<input type='hidden' value='" . $table . "' id='table' name='table'>";

        echo "<p>Enter the primary keys of the tuple:</p>";

        //looping thru all primary keys of given table
        //for identification of the tuple
        for ($i = 0; $i < count($pKey); $i++) {
            $column_name = $pKey[$i];
            echo $column_name . ": <input type='text' name='old" . $column_name . "Name'> <br /><br />";
        }

        $ncols = oci_num_fields($result);


        echo "<p>Enter the values you wish to change:</p>";

        //loop thru all the columns of the table
        //what you want to update
        for ($i = 1; $i <= $ncols; $i++) {
            $column_name  = oci_field_name($result, $i);
            echo $column_name . ": <input type='text' name='new" . $column_name . "Name'> <br /><br />";
        }

        echo "<input type='submit' value='Update' name='updateSubmit'></p>";
        echo "</form>";

        oci_commit($db_conn);
    }

    function submitUpdate()
    {
        global $db_conn;
        global $pKeys;
        $keys = "WHERE ";
        $table = $_POST["table"];
        $result = executePlainSQL("SELECT * FROM " . $table);

        $pKey = $pKeys[$table];


        //loops thru all PKs
        //find the tuple we want to update
        //identify via $keys
        for ($i = 0; $i < count($pKey); $i++) {
            if ($i != 0) {
                $keys .= " AND ";
            }
            $column_name = $pKey[$i];
            $old = $_POST["old" . $column_name . "Name"];

            $keys .= $column_name . "='" . $old . "'";
        }

        $ncols = oci_num_fields($result);

        //loops thru all columns to update themn
        for ($i = 1; $i <= $ncols; $i++) {
            $column_name  = oci_field_name($result, $i);

            //what we want to update our tuple with

            if ($column_name == "MAINTENANCEDATE") {
                $new = "DATE '" . $_POST["new" . $column_name . "Name"] . "'";
            } else {
                $new = "'" . $_POST["new" . $column_name . "Name"] . "'";
            }

            //where you update stuff
            if ($new != "") {
                executePlainSQL("UPDATE " . $table . " SET " . $column_name . "=" . $new . $keys);
            }
        }

        oci_commit($db_conn);

        $after = executePlainSQL("SELECT * FROM " . $table);
        notifyTableChange($result, $after);
        handleDisplayRequest($table);
    }


    function handleInsertTable($table)
    {
        global $db_conn;
        global $requiredFields;
        $required = $requiredFields[$table];
        $result = executePlainSQL("SELECT * FROM " . $table);
        printResult($result, $table);

        echo "<p>Select a table to insert new data.Values are case sensitive.</p>";
        echo "<form method='POST' action='script.php'>";
        echo "<input type='hidden' id='insertQueryRequest' name='insertQueryRequest'>";
        echo "<input type='hidden' value='" . $table . "' id='table' name='table'>";

        echo "<p>Enter the primary keys and values of the tuple you want to add:</p>";
        $ncols = oci_num_fields($result);
        $uppercaseRequired = array_map('strtoupper', $required);

        //loop thru all the columns of the table
        for ($i = 1; $i <= $ncols; $i++) {
            $column_name  = oci_field_name($result, $i);

            if (in_array($column_name, $uppercaseRequired)) {
                echo $column_name . " (required): <input type='text' name='insert" . $column_name . "Name'> <br /><br />";
            } else {
                echo $column_name . ": <input type='text' name='insert" . $column_name . "Name'> <br /><br />";
            }
        }

        echo "<input type='submit' value='Insert' name='insertSubmit'></p>";
        echo "</form>";

        oci_commit($db_conn);
    }

    function submitInsert()
    {
        global $db_conn, $success;
        $table = $_POST["table"];
        $result = executePlainSQL("SELECT * FROM " . $table);
        $insertTable = $table . "(";
        $ncols = oci_num_fields($result);
        $insertVal = "";
        //loops thru all columns
        for ($i = 1; $i <= $ncols; $i++) {
            $column_name  = oci_field_name($result, $i);
            $insertTable .= $column_name;

            //insert our tuple with
            $insertColVal = $_POST["insert" . $column_name . "Name"];
            if ($column_name == "MAINTENANCEDATE") {
                $insertVal .= "DATE '" . $insertColVal . "'";
            } else {
                $insertVal .= "'" . $insertColVal . "'";
            }

            if ($i != $ncols) {
                $insertVal .= ", ";
                $insertTable .= ", ";
            }
        }
        $insertTable .= ")";
        $val = executePlainSQL("INSERT INTO " . $insertTable . " VALUES (" . $insertVal . ")");
        oci_commit($db_conn);
        $After = executePlainSQL("SELECT * FROM " . $table);
        notifyTableChange($result, $After);
        handleDisplayRequest($table);
    }

    function handleDeleteTable($table)
    {
        global $db_conn;
        global $pKeys;
        $pKey = $pKeys[$table];
        $result = executePlainSQL("SELECT * FROM " . $table);
        printResult($result, $table);

        echo "<p>Values are case sensitive:</p>";
        echo "<form method = 'POST' action ='script.php'>";
        echo "<input type ='hidden' id='deleteQueryRequest' name ='deleteQueryRequest'>";
        echo "<input type ='hidden' value='" . $table . "' id='table' name='table'>";

        echo "<p>Enter the primary keys of the tuple you want to delete:</p>";
        for ($i = 0; $i < count($pKey); $i++) {
            $column_name = $pKey[$i];
            echo $column_name . ": <input type='text' name='" . $column_name . "Name'> <br /><br />";
        }
        echo "<input type='submit' value='Delete' name='deleteSubmit'></p>";
        echo "</form>";

        oci_commit($db_conn);
    }

    function submitDelete()
    {
        global $db_conn;
        global $pKeys;
        $table = $_POST["table"];
        $pKey = $pKeys[$table];
        $pKeyValues = array();
        $before = executePlainSQL("SELECT * FROM " . $table);

        for ($i = 0; $i < count($pKey); $i++) {
            $column_name = $pKey[$i];
            $pKeyValues[$column_name] = $_POST[$column_name . "Name"];
        }

        $deleteQuery = "DELETE FROM " . $table . " WHERE ";
        $first = true;
        foreach ($pKeyValues as $column_name => $value) {
            if ($first) {
                $first = false;
            } else {
                $deleteQuery .= " AND ";
            }
            $deleteQuery .= $column_name . " ='" . $value . "'";
        }
        //delete the tuple with the specified primary keys
        executePlainSQL($deleteQuery);
        oci_commit($db_conn);
        $after = executePlainSQL("SELECT * FROM " . $table);
        notifyTableChange($before, $after);
        handleDisplayRequest($table);
    }


    function handleProject($table)
    {
        global $db_conn;

        $result = executePlainSQL("SELECT * FROM " . $table);
        $ncols = oci_num_fields($result);

        echo "<p>Select the columns you want to include in the projection:</p>";
        echo "<form method = 'POST' action ='script.php'>";
        echo "<input type ='hidden' id='projectionQueryRequest' name ='projectionQueryRequest'>";
        echo "<input type='hidden' value='" . $table . "' id='table' name='table'>";


        for ($i = 1; $i <= $ncols; $i++) {
            $column_name  = oci_field_name($result, $i);
            echo "<input type='checkbox' name='" . $column_name . "Checked' value='" . $column_name . "'>" . $column_name . "<br>";
        }

        echo "<input type='submit' value='Submit' name='projectionSubmit'></p>";
        echo "</form>";

        oci_commit($db_conn);
    }


    function submitProject()
    {
        global $db_conn;
        $table = $_POST["table"];

        $result = executePlainSQL("SELECT * FROM " . $table);
        $ncols = oci_num_fields($result);

        $cols = [];

        for ($i = 1; $i <= $ncols; $i++) {
            $column_name  = oci_field_name($result, $i);
            if (isset($_POST[$column_name . "Checked"])) {
                $cols[] = $column_name;
            }
        }

        $projectionQuery = "SELECT " . implode(", ", $cols) . " FROM " . $table;
        $result = executePlainSQL($projectionQuery);
        printResult($result, $table);
        oci_commit($db_conn);
    }



    function handlePickTable()
    {
        if (array_key_exists('updateTable', $_POST)) {
            $table = $_POST["updateTable"];
            handleUpdateTable($table);
        } else if (array_key_exists('insertTable', $_POST)) {
            $table = $_POST["insertTable"];
            handleInsertTable($table);
        } else if (array_key_exists('deleteTable', $_POST)) {
            $table = $_POST["deleteTable"];
            handleDeleteTable($table);
        } else if (array_key_exists('projectTable', $_POST)) {
            $table = $_POST["projectTable"];
            handleProject($table);
        }
    }



    function handlePOSTRequest()
    {
        if (connectToDB()) {
            if (array_key_exists('displayAllTables', $_POST)) {
                printAll();
            } else if (array_key_exists('updateTables', $_POST)) {
                pickTable("update");
            } else if (array_key_exists('updateQueryRequest', $_POST)) {
                submitUpdate();
            } else if (array_key_exists('insertQueryRequest', $_POST)) {
                submitInsert();
            } else if (array_key_exists('deleteQueryRequest', $_POST)) {
                submitDelete();
            } else if (array_key_exists('projectionQueryRequest', $_POST)) {
                submitProject();
            } else if (array_key_exists('PickTable', $_POST)) {
                handlePickTable();
            } else if (array_key_exists('insertTables', $_POST)) {
                pickTable("insert");
            } else if (array_key_exists('deleteTables', $_POST)) {
                pickTable("delete");
            } else if (array_key_exists('projectTables', $_POST)) {
                pickTable("project");
            } else if (array_key_exists('displayTable', $_POST)) {
                $table = $_POST["displayTable"];
                handleDisplayRequest($table);
            }

            disconnectFromDB();
        }
    }

    function notifyTableChange($before, $after)
    {
        $tableChanged = false;

        $numFieldsBefore = oci_num_fields($before);
        $numFieldsAfter = oci_num_fields($after);

        $nBefore = oci_num_rows($before);
        $nAfter = oci_num_rows($after);

        if ($numFieldsBefore == $numFieldsAfter && $nBefore == $nAfter) {
            $beforeArr = array();
            while ($rowBefore = oci_fetch_assoc($before)) {
                $beforeArr[] = $rowBefore;
            }
            $afterArr = array();
            while ($rowAfter = oci_fetch_assoc($after)) {
                $afterArr[] = $rowAfter;
            }

            $beforeVals = array_values($beforeArr);
            $afterVals = array_values($afterArr);

            if ($beforeVals != $afterVals) {
                $tableChanged = true;
            }
        }
        if ($tableChanged) {
            alertMessage("Tables updated successfully!");
        } else {
            alertMessage("Tables were not updated. Please try again.");
        }
    }


    if (
        isset($_POST['display']) || isset($_POST['updateTables']) || isset($_POST['PickTable']) || isset($_POST['updateSubmit']) ||
        isset($_POST['insertSubmit']) || isset($_POST['deleteSubmit']) || isset($_POST['displayTableSubmit']) || isset($_POST['projectTables'])
        || isset($_POST['projectionSubmit'])
    ) {
        handlePOSTRequest();
    }


    ?>
</body>

</html>