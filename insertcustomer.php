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
$name = $mobileNo = $favourites = $address = "";
$success_message = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Insert data into customer table
    $name = isset($_POST["name"]) ? $_POST["name"] : "";
    $mobileNo = isset($_POST["mobileNo"]) ? $_POST["mobileNo"] : "";
    $favourites = isset($_POST["favourites"]) ? $_POST["favourites"] : "";
    $address = isset($_POST["address"]) ? $_POST["address"] : "";

    if (!empty($name) && !empty($mobileNo) && !empty($favourites) && !empty($address)) {
        $sql = "INSERT INTO customer (CustomerName, CustomerFav, CustomerNum, CustomerAdd) 
            VALUES ('$name', '$favourites', '$mobileNo', '$address')";

        if ($conn->query($sql) === TRUE) {
            $success_message = "New record inserted successfully";
        } else {
            $success_message = "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        $success_message = "Please fill in all fields";
    }
}

// Check if View Customer Table button is clicked
if (isset($_POST["view_table"])) {
    // Retrieve data from the customer table
    $sql = "SELECT * FROM customer";
    $result = $conn->query($sql);

    // Display the customer table
    if ($result->num_rows > 0) {
        echo "<h2>Customer Table</h2>";
        echo "<table border='1'>";
        echo "<tr><th>CustomerID</th><th>Name</th><th>Favourites</th><th>Mobile No</th><th>Address</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["CustomerID"] . "</td>";
            echo "<td>" . $row["CustomerName"] . "</td>";
            echo "<td>" . $row["CustomerFav"] . "</td>";
            echo "<td>" . $row["CustomerNum"] . "</td>";
            echo "<td>" . $row["CustomerAdd"] . "</td>";
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
    <title>Insert Data</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        form {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: AliceBlue;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        input[type="text"], textarea {
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
    <h2>Insert Customer Data</h2>
    <?php if (!empty($success_message)) { ?>
        <div class="success-message"><?php echo $success_message; ?></div>
    <?php } ?>
    <input type="text" name="name" placeholder="Name" value="<?php echo $name; ?>" required><br>
    <input type="text" name="mobileNo" placeholder="Mobile No" value="<?php echo $mobileNo; ?>" required><br>
    <input type="text" name="favourites" placeholder="Favourites" value="<?php echo $favourites; ?>" required><br>
    <textarea name="address" placeholder="Address" rows="4" required><?php echo $address; ?></textarea><br>
    <button type="submit">Insert Data</button>
</form>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <button type="submit" name="view_table">View Customer Table</button>
</form>

<form action="INTERFACE.php" method="get">
    <button type="submit" class="back-button">Back to Interface</button>
</form>

</body>
</html>
