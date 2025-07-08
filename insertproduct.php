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
$productName = $category = $supplierName = $price = "";
$success_message = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Insert data into product table
    $productName = isset($_POST["productName"]) ? $_POST["productName"] : "";
    $category = isset($_POST["category"]) ? $_POST["category"] : "";
    $supplierName = isset($_POST["supplierName"]) ? $_POST["supplierName"] : "";
    $price = isset($_POST["price"]) ? $_POST["price"] : "";

    if (!empty($productName) && !empty($category) && !empty($supplierName) && !empty($price)) {
        $sql = "INSERT INTO product (ProductName, Category, SupplierName, Price) 
            VALUES ('$productName', '$category', '$supplierName', '$price')";

        if ($conn->query($sql) === TRUE) {
            $success_message = "New record inserted successfully";
        } else {
            $success_message = "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        $success_message = "Please fill in all fields";
    }
}

// Check if View Product Table button is clicked
if (isset($_POST["view_table"])) {
    // Retrieve data from the product table
    $sql = "SELECT * FROM product";
    $result = $conn->query($sql);

    // Display the product table
    if ($result->num_rows > 0) {
        echo "<h2>Product Table</h2>";
        echo "<table border='1'>";
        echo "<tr><th>ProductID</th><th>Product Name</th><th>Category</th><th>Supplier Name</th><th>Price</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["ProductID"] . "</td>";
            echo "<td>" . $row["ProductName"] . "</td>";
            echo "<td>" . $row["Category"] . "</td>";
            echo "<td>" . $row["SupplierName"] . "</td>";
            echo "<td>" . $row["Price"] . "</td>";
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
    <title>Insert Product Data</title>
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
        input[type="text"], input[type="number"] {
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
    <h2>Insert Product Data</h2>
    <?php if (!empty($success_message)) { ?>
        <div class="success-message"><?php echo $success_message; ?></div>
    <?php } ?>
    <input type="text" name="productName" placeholder="Product Name" value="<?php echo $productName; ?>" required><br>
    <input type="text" name="category" placeholder="Category" value="<?php echo $category; ?>" required><br>
    <input type="text" name="supplierName" placeholder="Supplier Name" value="<?php echo $supplierName; ?>" required><br>
    <input type="number" step="0.01" name="price" placeholder="Price" value="<?php echo $price; ?>" required><br>
    <button type="submit">Insert Data</button>
</form>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <button type="submit" name="view_table">View Product Table</button>
</form>

<form action="INTERFACE.php" method="get">
    <button type="submit" class="back-button">Back to Interface</button>
</form>

</body>
</html>
