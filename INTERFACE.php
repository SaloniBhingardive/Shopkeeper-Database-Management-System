<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Interface</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('https://eat-marketing.co.uk/wp-content/uploads/2019/05/eat-header-1.jpg'); /* Replace 'background.jpg' with the path to your background image */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            padding: 20px;
            font-size: 18px;
        }

        h2 {
            color: #fff;
        }

        table {
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            border: 1px solid #fff;
            color: #fff;
        }

        th {
            background-color: #007bff;
        }

        td {
            background-color: #4CAF50;
        }

        .container {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }

        .buttons {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }

        button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
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

    // Retrieve data from each table
    $tables = array("customer", "supplier", "payment", "balance", "inventory", "product");

    foreach ($tables as $table) {
        // Retrieve data from the table
        $sql = "SELECT * FROM $table";
        $result = $conn->query($sql);

        // Display the table name
        echo "<div>";
        echo "<h2>$table Table</h2>";

        // Display the table data
        if ($result->num_rows > 0) {
            echo "<table>";
            
            // Fetch the first row to generate the headers
            $row = $result->fetch_assoc();
            echo "<tr>";
            foreach ($row as $key => $value) {
                echo "<th>$key</th>";
            }
            echo "</tr>";

            // Display the first row
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>$value</td>";
            }
            echo "</tr>";

            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                foreach ($row as $value) {
                    echo "<td>$value</td>";
                }
                echo "</tr>";
            }
            echo "</table>";

            // Generate buttons for each table
            echo "<div class='buttons'>";
            echo "<button onclick=\"window.location.href='insert{$table}.php?table=$table'\">Insert</button>";
            echo "<button onclick=\"window.location.href='update{$table}.php?table=$table'\">Update</button>";
            echo "<button onclick=\"window.location.href='delete{$table}.php?table=$table'\">Delete</button>";
            echo "</div>";
        } else {
            echo "0 results";
        }
        echo "</div>";
    }

    // Close connection
    $conn->close();
    ?>
</div>

</body>
</html>
