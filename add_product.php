<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

require 'db.php';

if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];

    // Menggabungkan RAM dengan satuannya
    $ram = $_POST['ram_value'] . " " . $_POST['ram_unit'];

    // Menggabungkan Storage dengan satuannya
    $storage = $_POST['storage_value'] . " " . $_POST['storage_unit'];

    // Mengambil kondisi produk
    $condition = $_POST['condition'];

    // Menggabungkan semua spesifikasi menjadi satu string
    $specifications = "RAM: $ram, Storage: $storage, Condition: $condition";

    // Handle file upload
    $target_dir = "upload/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

    // Insert product details into the database
    $sql = "INSERT INTO products (name, price, specifications, image) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdss", $name, $price, $specifications, $target_file);

    if ($stmt->execute()) {
        echo "Product added successfully.";
        header("Location: index.php");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <style>
        /* General body styling */
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f5f5f7;
            margin: 0;
            padding: 20px;
        }

        /* Fieldset styling */
        fieldset {
            background-color: #fff;
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Legend styling */
        legend {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        /* Form input styling */
        input[type="text"],
        input[type="number"],
        textarea,
        input[type="file"],
        select {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #d1d1d6;
            font-size: 16px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }

        /* Focused input styling */
        input[type="text"]:focus,
        input[type="number"]:focus,
        textarea:focus {
            border-color: #0071e3;
            outline: none;
        }

        /* Button styling */
        button {
            width: 100%;
            padding: 12px;
            background-color: #0071e3;
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 18px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 10px;
        }

        /* Button hover effect */
        button:hover {
            background-color: #005bb5;
        }

        /* Table styling */
        table {
            width: 100%;
            margin: 10px 0;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        /* Back to Home button styling */
        .back-home {
            background-color: #6c757d;
        }

        .back-home:hover {
            background-color: #5a6268;
        }

        /* Responsive styling */
        @media (max-width: 600px) {
            fieldset {
                padding: 20px;
            }

            input[type="text"],
            input[type="number"],
            button {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<fieldset>
    <legend>Add Product</legend>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Product Name" required size="40"><br>
        <input type="number" step="0.01" name="price" placeholder="Price" required size="20"><br>

        <!-- Specifications Table -->
        <table>
            <tr>
                <td>RAM:</td>
                <td>
                    <input type="number" name="ram_value" placeholder="RAM" required>
                    <select name="ram_unit">
                        <option value="GB">GB</option>
                        <option value="MB">MB</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Storage:</td>
                <td>
                    <input type="number" name="storage_value" placeholder="Storage" required>
                    <select name="storage_unit">
                        <option value="GB">GB</option>
                        <option value="TB">TB</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Condition:</td>
                <td>
                    <select name="condition">
                        <option value="new">New</option>
                        <option value="used">Used</option> 
                    </select>
                </td>
            </tr>
        </table>

        <input type="file" name="image" required><br>
        <button type="submit" name="add_product">Add Product</button>
    </form>

    <!-- Back to Home button -->
    <form action="index.php" method="get">
        <button type="submit" class="back-home">Back to Home</button>
    </form>
</fieldset>

</body>
</html>
