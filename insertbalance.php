<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "shopkeeperdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$customerName = $balanceAmount = "";
$success_message = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Insert data into balance table
    $customerName = isset($_POST["customerName"]) ? $_POST["customerName"] : "";
    $balanceAmount = isset($_POST["balanceAmount"]) ? $_POST["balanceAmount"] : "";

    if (!empty($customerName) && !empty($balanceAmount)) {
        $sql = "INSERT INTO Balance (CustomerName, BalanceAmount) 
            VALUES ('$customerName', '$balanceAmount')";

        if ($conn->query($sql) === TRUE) {
            $success_message = "New record inserted successfully";
        } else {
            $success_message = "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        $success_message = "Please fill in all fields";
    }
}

// Check if View Balance Table button is clicked
if (isset($_POST["view_table"])) {
    // Retrieve data from the balance table
    $sql = "SELECT * FROM Balance";
    $result = $conn->query($sql);

    // Display the balance table
    if ($result->num_rows > 0) {
        echo "<h2>Balance Table</h2>";
        echo "<table border='1'>";
        echo "<tr><th>BalanceID</th><th>Customer Name</th><th>Balance Amount</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["BalanceID"] . "</td>";
            echo "<td>" . $row["CustomerName"] . "</td>";
            echo "<td>" . $row["BalanceAmount"] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "0 results";
    }
}

// Close connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Balance Data</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: AliceBlue;
        }
        form {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        button[type="submit"], .back-button {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button[type="submit"]:hover, .back-button:hover {
            background-color: #45a049;
        }
        .success-message {
            color: green;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <h2>Insert Balance Data</h2>
    <?php if (!empty($success_message)) { ?>
        <div class="success-message"><?php echo $success_message; ?></div>
    <?php } ?>
    <input type="text" name="customerName" placeholder="Customer Name" value="<?php echo $customerName; ?>" required><br>
    <input type="text" name="balanceAmount" placeholder="Balance Amount" value="<?php echo $balanceAmount; ?>" required><br>
    <button type="submit">Insert Data</button>
</form>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <button type="submit" name="view_table">View Balance Table</button>
</form>

<form action="INTERFACE.php" method="get">
    <button type="submit" class="back-button">Back to Interface</button>
</form>

</body>
</html>
