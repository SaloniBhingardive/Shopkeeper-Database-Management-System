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
$productID = $lastStocked = $quantityAvailable = $inDemand = "";
$success_message = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Insert data into inventory table
    $productID = isset($_POST["productID"]) ? $_POST["productID"] : "";
    $lastStocked = isset($_POST["lastStocked"]) ? $_POST["lastStocked"] : "";
    $quantityAvailable = isset($_POST["quantityAvailable"]) ? $_POST["quantityAvailable"] : "";
    $inDemand = isset($_POST["inDemand"]) ? $_POST["inDemand"] : "";

    if (!empty($productID) && !empty($lastStocked) && !empty($quantityAvailable) && !empty($inDemand)) {
        $inDemand = ($inDemand === "1") ? 1 : 0; // Convert checkbox value to 1 or 0
        $sql = "INSERT INTO Inventory (ProductID, LastStocked, QuantityAvailable, InDemand) 
            VALUES ('$productID', '$lastStocked', '$quantityAvailable', '$inDemand')";

        if ($conn->query($sql) === TRUE) {
            $success_message = "New record inserted successfully";
        } else {
            $success_message = "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        $success_message = "Please fill in all fields";
    }
}

// Check if View Inventory Table button is clicked
if (isset($_POST["view_table"])) {
    // Retrieve data from the inventory table
    $sql = "SELECT * FROM Inventory";
    $result = $conn->query($sql);

    // Display the inventory table
    if ($result->num_rows > 0) {
        echo "<h2>Inventory Table</h2>";
        echo "<table border='1'>";
        echo "<tr><th>ProductID</th><th>Last Stocked</th><th>Quantity Available</th><th>In Demand</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["ProductID"] . "</td>";
            echo "<td>" . $row["LastStocked"] . "</td>";
            echo "<td>" . $row["QuantityAvailable"] . "</td>";
            echo "<td>" . ($row["InDemand"] ? "Yes" : "No") . "</td>";
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
    <title>Insert Inventory Data</title>
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
        input[type="text"], input[type="date"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="checkbox"] {
            margin-bottom: 15px;
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
    <h2>Insert Inventory Data</h2>
    <?php if (!empty($success_message)) { ?>
        <div class="success-message"><?php echo $success_message; ?></div>
    <?php } ?>
    <input type="text" name="productID" placeholder="Product ID" value="<?php echo $productID; ?>" required><br>
    <input type="date" name="lastStocked" placeholder="Last Stocked" value="<?php echo $lastStocked; ?>" required><br>
    <input type="text" name="quantityAvailable" placeholder="Quantity Available" value="<?php echo $quantityAvailable; ?>" required><br>
    <label for="inDemand">In Demand: </label>
    <input type="checkbox" name="inDemand" value="1" <?php echo ($inDemand === "1") ? "checked" : ""; ?>><br>
    <button type="submit">Insert Data</button>
</form>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <button type="submit" name="view_table">View Inventory Table</button>
</form>

<form action="INTERFACE.php" method="get">
    <button type="submit" class="back-button">Back to Interface</button>
</form>

</body>
</html>
